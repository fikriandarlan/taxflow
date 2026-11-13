<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use App\Services\TransactionCalculator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index(): View
    {
        $transactions = Transaction::query()
            ->with([
                'merchant:id,merchant_code,name',
                'taxType:id,tax_code,name',
            ])
            ->orderByDesc('id')
            ->paginate(10);

        return view('transactions.index', [
            'transactions' => $transactions,
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
}
