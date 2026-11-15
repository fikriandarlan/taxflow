@php
    $cityGroups = config('java_cities', []);

    $availableCities = collect($cityGroups)
        ->flatten()
        ->values()
        ->all();

    $currentAddress = old(
        'address',
        $merchant->address ?? ''
    );

    $currentAddress = is_string($currentAddress)
        ? $currentAddress
        : '';

    // Alamat lama tetap dapat ditampilkan
    // apabila tidak ada dalam daftar wilayah baru.
    $isLegacyAddress =
        $currentAddress !== ''
        && !in_array(
            $currentAddress,
            $availableCities,
            true
        );
@endphp

<div class="col-12">

    <label
        for="address"
        class="form-label fw-semibold">
        Kota / Kabupaten Merchant
    </label>

    <select
        id="address"
        name="address"
        class="form-select rounded-3
            @error('address') is-invalid @enderror">

        <option
            value=""
            @selected($currentAddress === '')>
            Pilih Kota / Kabupaten
        </option>

        @if ($isLegacyAddress)
            <optgroup label="Alamat Tersimpan Sebelumnya">
                <option
                    value="{{ $currentAddress }}"
                    selected>
                    {{ $currentAddress }}
                    (Alamat Lama)
                </option>
            </optgroup>
        @endif

        @foreach ($cityGroups as $province => $cities)

            <optgroup label="{{ $province }}">

                @foreach ($cities as $city)

                    <option
                        value="{{ $city }}"
                        @selected(
                            $currentAddress === $city
                        )>
                        {{ $city }}
                    </option>

                @endforeach

            </optgroup>

        @endforeach

    </select>

    @error('address')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-text">
        Pilih lokasi Merchant berdasarkan
        Kota/Kabupaten di Pulau Jawa.
    </div>

    @if ($isLegacyAddress)
        <div class="alert alert-warning
                    rounded-3 small mt-3 mb-0">
            Alamat yang tersimpan sebelumnya
            belum menggunakan format pilihan
            Kota/Kabupaten.

            Anda dapat mempertahankan alamat
            tersebut atau memilih lokasi baru.
        </div>
    @endif

</div>
