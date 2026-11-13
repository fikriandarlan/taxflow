<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerchantRequest;
use App\Http\Requests\UpdateMerchantRequest;
use App\Models\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MerchantController extends Controller
{
    /**
     * Menampilkan daftar merchant.
     */
    public function index(): View
    {
        $merchants = Merchant::query()
            ->orderBy('name')
            ->paginate(10);

        return view('merchants.index', [
            'merchants' => $merchants,
        ]);
    }

    /**
     * Menampilkan form tambah merchant.
     */
    public function create(): View
    {
        return view('merchants.create');
    }

    /**
     * Menyimpan merchant baru.
     */
    public function store(
        StoreMerchantRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['merchant_code'] = strtoupper(
            $validated['merchant_code']
        );

        $validated['is_active'] = (bool) $validated['is_active'];

        Merchant::create($validated);

        return redirect()
            ->route('merchants.index')
            ->with('success', 'Merchant berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit merchant.
     */
    public function edit(Merchant $merchant): View
    {
        return view('merchants.edit', [
            'merchant' => $merchant,
        ]);
    }

    /**
     * Memperbarui data merchant.
     */
    public function update(
        UpdateMerchantRequest $request,
        Merchant $merchant
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['is_active'] = (bool) $validated['is_active'];

        $merchant->update($validated);

        return redirect()
            ->route('merchants.index')
            ->with('success', 'Merchant berhasil diperbarui.');
    }
}
