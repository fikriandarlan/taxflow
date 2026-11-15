@php
    $isActive = $sort === $column;
@endphp

<th scope="col"
    class="taxflow-sort-heading {{ $align ?? '' }}">

    <div class="taxflow-sort-heading-inner">

        <span class="taxflow-sort-label">
            {{ $label }}
        </span>

        <span class="taxflow-sort-controls">

            {{-- Urutan naik --}}
            <a
                href="{{ route('transactions.index', [
                    'sort' => $column,
                    'direction' => 'asc'
                ]) }}"
                class="taxflow-sort-arrow
                    {{ $isActive && $direction === 'asc' ? 'active' : '' }}"
                title="Urutkan naik"
                aria-label="Urutkan {{ $label }} naik"
                @if($isActive && $direction === 'asc')
                    aria-current="true"
                @endif>
                &#8593;
            </a>

            {{-- Urutan turun --}}
            <a
                href="{{ route('transactions.index', [
                    'sort' => $column,
                    'direction' => 'desc'
                ]) }}"
                class="taxflow-sort-arrow
                    {{ $isActive && $direction === 'desc' ? 'active' : '' }}"
                title="Urutkan turun"
                aria-label="Urutkan {{ $label }} turun"
                @if($isActive && $direction === 'desc')
                    aria-current="true"
                @endif>
                &#8595;
            </a>

        </span>

    </div>

</th>
