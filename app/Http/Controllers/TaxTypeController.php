<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaxTypeRequest;
use App\Http\Requests\UpdateTaxTypeRequest;
use App\Models\TaxType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxTypeController extends Controller
{
    /**
     * Menampilkan daftar jenis pajak simulasi.
     */
    public function index(): View
    {
        $taxTypes = TaxType::query()
            ->orderBy('name')
            ->paginate(10);

        return view('tax-types.index', [
            'taxTypes' => $taxTypes,
        ]);
    }

    /**
     * Menampilkan form tambah jenis pajak.
     */
    public function create(): View
    {
        return view('tax-types.create');
    }

    /**
     * Menyimpan jenis pajak baru.
     */
    public function store(
        StoreTaxTypeRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['is_active'] = (bool) $validated['is_active'];

        TaxType::create($validated);

        return redirect()
            ->route('tax-types.index')
            ->with(
                'success',
                'Jenis pajak berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit jenis pajak.
     */
    public function edit(TaxType $taxType): View
    {
        return view('tax-types.edit', [
            'taxType' => $taxType,
        ]);
    }

    /**
     * Memperbarui jenis pajak yang sudah ada.
     */
    public function update(
        UpdateTaxTypeRequest $request,
        TaxType $taxType
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['is_active'] = (bool) $validated['is_active'];

        $taxType->update($validated);

        return redirect()
            ->route('tax-types.index')
            ->with(
                'success',
                'Jenis pajak berhasil diperbarui.'
            );
    }
}
