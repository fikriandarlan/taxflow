<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistik master data
        $totalMerchants = Merchant::count();

        $activeMerchants = Merchant::where(
            'is_active', true
        )->count();

        $totalTaxTypes = TaxType::count();

        $activeTaxTypes = TaxType::where(
            'is_active', true
        )->count();

        // Statistik status transaksi
        $totalTransactions = Transaction::count();

        $draftTransactions = Transaction::where(
            'status', 'draft'
        )->count();

        $completedTransactions = Transaction::where(
            'status', 'completed'
        )->count();

        $cancelledTransactions = Transaction::where(
            'status', 'cancelled'
        )->count();

        // Nilai keuangan merupakan ringkasan simulasi.
        // Tidak menghitung ulang atau mengubah transaksi.
        $financialSummary = DB::table('transactions')
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

        $totalBaseAmount =
            (string) $financialSummary->base_total;

        $totalTaxAmount =
            (string) $financialSummary->tax_total;

        $totalGrandAmount =
            (string) $financialSummary->grand_total;

        $completedTaxAmount = (string) Transaction::where(
            'status', 'completed'
        )->sum('tax_amount');

        // Data dinamis untuk setiap jenis pajak.
        // LEFT JOIN memastikan jenis pajak yang
        // belum mempunyai transaksi tetap muncul.
        $taxTypeSummaries = DB::table('tax_types as tt')
            ->leftJoin(
                'transactions as t',
                't.tax_type_id',
                '=',
                'tt.id'
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
                // Persentase efektif berdasarkan
                // nominal pajak / nominal sebelum pajak.
                // BCMath menghindari pembagian float.
                $base = (string) $item->base_total;
                $tax = (string) $item->tax_total;

                $effectivePercent = bccomp(
                    $base, '0', 2
                ) > 0
                    ? bcdiv(
                        bcmul($tax, '100', 6),
                        $base,
                        2
                    )
                    : '0.00';

                return [
                    'tax_code' => $item->tax_code,
                    'name' => $item->name,
                    'rate_percent' =>
                        (string) $item->rate_percent,
                    'is_active' => (bool) $item->is_active,
                    'transaction_count' =>
                        (int) $item->transaction_count,
                    'base_total' =>
                        $base,
                    'tax_total' =>
                        $tax,
                    'grand_total' =>
                        (string) $item->grand_total,
                    'effective_percent' =>
                        $effectivePercent,
                ];
            });

        $recentTransactions = Transaction::query()
            ->with([
                'merchant:id,merchant_code,name',
                'taxType:id,tax_code,name',
            ])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalMerchants',
            'activeMerchants',
            'totalTaxTypes',
            'activeTaxTypes',
            'totalTransactions',
            'draftTransactions',
            'completedTransactions',
            'cancelledTransactions',
            'totalBaseAmount',
            'totalTaxAmount',
            'totalGrandAmount',
            'completedTaxAmount',
            'taxTypeSummaries',
            'recentTransactions'
        ));
    }
}
