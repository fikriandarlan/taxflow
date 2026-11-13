<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan operasional TaxFlow.
     */
    public function index(): View
    {
        // Statistik Merchant
        $totalMerchants = Merchant::query()->count();

        $activeMerchants = Merchant::query()
            ->where('is_active', true)
            ->count();

        // Statistik Jenis Pajak
        $totalTaxTypes = TaxType::query()->count();

        $activeTaxTypes = TaxType::query()
            ->where('is_active', true)
            ->count();

        // Statistik transaksi
        $totalTransactions = Transaction::query()->count();

        $draftTransactions = Transaction::query()
            ->where('status', 'draft')
            ->count();

        // Total nominal pajak dari seluruh transaksi
        // termasuk Draft dan Cancelled.
        // Ini angka simulasi, bukan pajak terbayar.
        $totalTaxAmount = (string) Transaction::query()
            ->sum('tax_amount');

        // Lima transaksi terbaru
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
            'totalTaxAmount',
            'recentTransactions'
        ));
    }
}
