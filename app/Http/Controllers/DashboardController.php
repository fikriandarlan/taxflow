<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Daftar periode yang didukung.
        // Default "all" memastikan data historis muncul.
        $periodOptions = [
            'all' => 'Semua Waktu',
            '24h' => '24 Jam Terakhir',
            '7d'  => '7 Hari Terakhir',
            '30d' => '30 Hari Terakhir',
            '3m'  => '3 Bulan Terakhir',
            '6m'  => '6 Bulan Terakhir',
            '1y'  => '1 Tahun Terakhir',
        ];

        $requestedPeriod = $request->query('period', 'all');

        $period = is_string($requestedPeriod)
            && array_key_exists($requestedPeriod, $periodOptions)
                ? $requestedPeriod
                : 'all';

        $now = CarbonImmutable::now();

        $startDate = match ($period) {
            '24h' => $now->subHours(24),
            '7d'  => $now->subDays(7),
            '30d' => $now->subDays(30),
            '3m'  => $now->subMonthsNoOverflow(3),
            '6m'  => $now->subMonthsNoOverflow(6),
            '1y'  => $now->subYearNoOverflow(),
            default => null,
        };

        $taxTypes = TaxType::query()
            ->orderBy('tax_code')
            ->get([
                'id',
                'tax_code',
                'name',
                'rate_percent',
                'is_active',
            ]);

        $requestedTaxType = $request->query(
            'tax_type',
            'all'
        );

        $selectedTaxType = 'all';

        if (
            is_string($requestedTaxType)
            && ctype_digit($requestedTaxType)
            && $taxTypes->contains(
                'id',
                (int) $requestedTaxType
            )
        ) {
            $selectedTaxType = $requestedTaxType;
        }

        // Statistik sistem: seluruh data.
        $totalMerchants = Merchant::count();

        $activeMerchants = Merchant::where(
            'is_active',
            true
        )->count();

        $totalTaxTypes = $taxTypes->count();

        $activeTaxTypes = $taxTypes
            ->where('is_active', true)
            ->count();

        $totalTransactions = Transaction::count();

        $draftTransactions = Transaction::where(
            'status',
            'draft'
        )->count();

        $completedTransactions = Transaction::where(
            'status',
            'completed'
        )->count();

        $cancelledTransactions = Transaction::where(
            'status',
            'cancelled'
        )->count();

        // Query transaksi yang berlaku hanya untuk
        // Ringkasan Perhitungan Pajak.
        $filteredQuery = DB::table('transactions');

        if ($startDate !== null) {
            $filteredQuery->whereBetween(
                'transaction_date',
                [$startDate, $now]
            );
        }

        if ($selectedTaxType !== 'all') {
            $filteredQuery->where(
                'tax_type_id',
                (int) $selectedTaxType
            );
        }

        // PostgreSQL NUMERIC mempertahankan presisi.
        $financialSummary = $filteredQuery
            ->selectRaw(
                'COUNT(*) AS transaction_count'
            )
            ->selectRaw(
                'COALESCE(SUM(base_amount), 0) AS base_total'
            )
            ->selectRaw(
                'COALESCE(SUM(tax_amount), 0) AS tax_total'
            )
            ->selectRaw(
                'COALESCE(SUM(total_amount), 0) AS grand_total'
            )
            ->first();

        $filteredTransactionCount =
            (int) $financialSummary->transaction_count;

        $totalBaseAmount =
            (string) $financialSummary->base_total;

        $totalTaxAmount =
            (string) $financialSummary->tax_total;

        $totalGrandAmount =
            (string) $financialSummary->grand_total;

        $effectivePercent = bccomp(
            $totalBaseAmount,
            '0',
            2
        ) > 0
            ? bcdiv(
                bcmul($totalTaxAmount, '100', 6),
                $totalBaseAmount,
                2
            )
            : '0.00';

        // Ringkasan per kategori mengikuti periode,
        // tetapi tetap menampilkan semua kategori.
        $taxTypeSummaries = DB::table('tax_types as tt')
            ->leftJoin(
                'transactions as t',
                function ($join) use ($startDate, $now) {
                    $join->on(
                        't.tax_type_id',
                        '=',
                        'tt.id'
                    );

                    if ($startDate !== null) {
                        $join->whereBetween(
                            't.transaction_date',
                            [$startDate, $now]
                        );
                    }
                }
            )
            ->select(
                'tt.id',
                'tt.tax_code',
                'tt.name',
                'tt.rate_percent',
                'tt.is_active'
            )
            ->selectRaw(
                'COUNT(t.id) AS transaction_count'
            )
            ->selectRaw(
                'COALESCE(SUM(t.base_amount), 0) AS base_total'
            )
            ->selectRaw(
                'COALESCE(SUM(t.tax_amount), 0) AS tax_total'
            )
            ->selectRaw(
                'COALESCE(SUM(t.total_amount), 0) AS grand_total'
            )
            ->groupBy(
                'tt.id',
                'tt.tax_code',
                'tt.name',
                'tt.rate_percent',
                'tt.is_active'
            )
            ->orderBy('tt.tax_code')
            ->get()
            ->map(function ($item) {
                $base = (string) $item->base_total;
                $tax = (string) $item->tax_total;

                $percent = bccomp($base, '0', 2) > 0
                    ? bcdiv(
                        bcmul($tax, '100', 6),
                        $base,
                        2
                    )
                    : '0.00';

                return [
                    'id' => $item->id,
                    'tax_code' => $item->tax_code,
                    'name' => $item->name,
                    'rate_percent' =>
                        (string) $item->rate_percent,
                    'is_active' =>
                        (bool) $item->is_active,
                    'transaction_count' =>
                        (int) $item->transaction_count,
                    'base_total' => $base,
                    'tax_total' => $tax,
                    'grand_total' =>
                        (string) $item->grand_total,
                    'effective_percent' => $percent,
                ];
            });

        // Tetap menampilkan transaksi terbaru
        // tanpa membatasi jenis pajak atau periode.
        $recentTransactions = Transaction::query()
            ->with([
                'merchant:id,merchant_code,name',
                'taxType:id,tax_code,name',
            ])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'periodOptions',
            'period',
            'taxTypes',
            'selectedTaxType',
            'startDate',
            'now',
            'totalMerchants',
            'activeMerchants',
            'totalTaxTypes',
            'activeTaxTypes',
            'totalTransactions',
            'draftTransactions',
            'completedTransactions',
            'cancelledTransactions',
            'filteredTransactionCount',
            'totalBaseAmount',
            'totalTaxAmount',
            'totalGrandAmount',
            'effectivePercent',
            'taxTypeSummaries',
            'recentTransactions'
        ));
    }
}
