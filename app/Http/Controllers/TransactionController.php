<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use App\Services\TransactionCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
   
/**
 * Menampilkan daftar transaksi dengan
 * sorting server-side dan pagination.
 */
public function index(Request $request): View
{
    // Hanya enam kolom ini yang boleh diurutkan.
    $allowedSorts = [
        'no' => 'transactions.id',
        'date' => 'transactions.transaction_date',
        'tax_type' => 'sort_tax_types.name',
        'base_amount' => 'transactions.base_amount',
        'tax_amount' => 'transactions.tax_amount',
        'total_amount' => 'transactions.total_amount',
    ];

    $requestedSort = $request->query('sort', 'no');
    $requestedDirection = $request->query(
        'direction',
        'desc'
    );

    // Validasi query string agar tidak bisa
    // memasukkan nama kolom sembarangan.
    $sort = is_string($requestedSort)
        && array_key_exists($requestedSort, $allowedSorts)
            ? $requestedSort
            : 'no';

    $direction = is_string($requestedDirection)
        && in_array(
            $requestedDirection,
            ['asc', 'desc'],
            true
        )
            ? $requestedDirection
            : 'desc';

    $transactions = Transaction::query()
        ->select('transactions.*')
        ->leftJoin(
            'tax_types as sort_tax_types',
            'sort_tax_types.id',
            '=',
            'transactions.tax_type_id'
        )
        ->with([
            'merchant:id,merchant_code,name',
            'taxType:id,tax_code,name',
        ])
        ->orderBy(
            $allowedSorts[$sort],
            $direction
        );

    // Urutan kedua memastikan hasil stabil
    // ketika banyak transaksi memiliki
    // tanggal, kategori, atau nominal sama.
    if ($sort !== 'no') {
        $transactions->orderBy(
            'transactions.id',
            $direction
        );
    }

    $transactions = $transactions
        ->paginate(10)
        ->withQueryString();

    return view('transactions.index', [
        'transactions' => $transactions,
        'sort' => $sort,
        'direction' => $direction,
    ]);
}

    /**
     * Menampilkan form Create Transaction.
     */
    public function create(): View
    {
        $merchants = Merchant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'merchant_code',
                'name',
            ]);

        $taxTypes = TaxType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'tax_code',
                'name',
                'rate_percent',
            ]);

        return view('transactions.create', [
            'merchants' => $merchants,
            'taxTypes' => $taxTypes,
        ]);
    }

    /**
     * Memvalidasi, menghitung, dan menyimpan transaksi.
     */
    public function store(
        StoreTransactionRequest $request,
        TransactionCalculator $calculator
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use (
            $validated,
            $calculator
        ): void {
            // Kunci dan verifikasi ulang Merchant.
            $merchant = Merchant::query()
                ->whereKey($validated['merchant_id'])
                ->lockForUpdate()
                ->first();

            if (! $merchant || ! $merchant->is_active) {
                throw ValidationException::withMessages([
                    'merchant_id' =>
                    'Merchant tidak ditemukan atau tidak aktif.',
                ]);
            }

            // Kunci dan verifikasi ulang TaxType.
            $taxType = TaxType::query()
                ->whereKey($validated['tax_type_id'])
                ->lockForUpdate()
                ->first();

            if (! $taxType || ! $taxType->is_active) {
                throw ValidationException::withMessages([
                    'tax_type_id' =>
                    'Jenis pajak tidak ditemukan atau tidak aktif.',
                ]);
            }

            // Tarif hanya berasal dari database.
            $calculation = $calculator->calculate(
                (string) $validated['base_amount'],
                (string) $taxType->rate_percent
            );

            // Konversi input datetime-local ke timestamp SQL.
            $transactionDate = Carbon::createFromFormat(
                '!Y-m-d\TH:i',
                $validated['transaction_date']
            )->format('Y-m-d H:i:s');

            // Susun data secara eksplisit.
            Transaction::create([
                'transaction_code' =>
                $validated['transaction_code'],

                'merchant_id' => $merchant->id,

                'tax_type_id' => $taxType->id,

                'transaction_date' => $transactionDate,

                'base_amount' =>
                $calculation['base_amount'],

                'tax_rate_percent' =>
                $calculation['tax_rate_percent'],

                'tax_amount' =>
                $calculation['tax_amount'],

                'total_amount' =>
                $calculation['total_amount'],

                'status' => 'draft',

                'description' =>
                $validated['description'] ?? null,
            ]);
        }, 3);

        return redirect()
            ->route('transactions.index')
            ->with(
                'success',
                'Transaksi berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan halaman edit transaksi.
     */
    public function edit(
        \App\Models\Transaction $transaction
    ) {
        abort_unless(
            $transaction->status === 'draft',
            403,
            'Hanya transaksi Draft yang dapat diedit.'
        );

        $merchants = \App\Models\Merchant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $taxTypes = \App\Models\TaxType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact(
            'transaction',
            'merchants',
            'taxTypes'
        ));
    }

    /**
     * Menyimpan perubahan transaksi Draft.
     */
    public function update(
        \App\Http\Requests\UpdateTransactionRequest $request,
        \App\Models\Transaction $transaction,
        \App\Services\TransactionCalculator $calculator
    ) {
        $data = $request->validated();

        \Illuminate\Support\Facades\DB::transaction(
            function () use ($transaction, $data, $calculator) {

                // Kunci transaksi agar tidak diubah bersamaan.
                $lockedTransaction = \App\Models\Transaction::query()
                    ->whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                abort_unless(
                    $lockedTransaction->status === 'draft',
                    403,
                    'Hanya transaksi Draft yang dapat diedit.'
                );

                // Periksa kembali status Merchant.
                $merchant = \App\Models\Merchant::query()
                    ->whereKey($data['merchant_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                // Periksa kembali status TaxType.
                $taxType = \App\Models\TaxType::query()
                    ->whereKey($data['tax_type_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (! $merchant || ! $taxType) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'tax_type_id' =>
                        'Merchant atau Jenis Pajak sudah tidak aktif.',
                    ]);
                }

                // Ambil tarif dari database, bukan input pengguna.
                $rate = (string) $taxType->rate_percent;

                // Hitung ulang menggunakan kalkulator TaxFlow.
                $result = $calculator->calculate(
                    (string) $data['base_amount'],
                    $rate
                );

                $lockedTransaction->update([
                    'transaction_code' =>
                    $data['transaction_code'],

                    'merchant_id' =>
                    $merchant->id,

                    'tax_type_id' =>
                    $taxType->id,

                    'transaction_date' =>
                    \Carbon\Carbon::createFromFormat(
                        'Y-m-d\TH:i',
                        $data['transaction_date']
                    ),

                    'base_amount' =>
                    $result['base_amount'],

                    'tax_rate_percent' =>
                    $rate,

                    'tax_amount' =>
                    $result['tax_amount'],

                    'total_amount' =>
                    $result['total_amount'],

                    'description' =>
                    $data['description'] ?? null,
                ]);
            }
        );

        return redirect()
            ->route('transactions.index')
            ->with(
                'success',
                'Transaksi berhasil diperbarui.'
            );
    }
}
