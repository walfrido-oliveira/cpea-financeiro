<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Classificação') }}" class="sticky-col first-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Diretoria') }}" class="sticky-col second-col"/>
        @foreach ($months as $month)
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="{{ $month . '/' . $year }}" columnText="{{ $month . '/' . $year }}"/>
        @endforeach
    </tr>
</thead>
<tbody id="withdrawals_table_content">
    @forelse ($accountingClassifications1 as $key => $accountingClassification)
        <tr @if ( $accountingClassification->featured)
            class="featured"
             @endif
        >
            <td class="sticky-col first-col"
            style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                {{ $accountingClassification->classification }}
            </td>
            <td class="sticky-col second-col"
            style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                {{ $accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td  style="
                @if ($accountingClassification->color)
                    color:{{ $accountingClassification->color }};
                @endif
                @if ($accountingClassification->bolder)
                    font-weight:bolder;
                @endif
                ">
                    @php
                        $totalClassificationDRE = $accountingClassification->getTotalClassificationWithdrawal($key, $year);
                    @endphp
                    @if ($totalClassificationDRE > 0)
                        {{ $accountingClassification->unity . number_format($totalClassificationDRE, 0, ',', '.') }}
                    @elseif($totalClassificationDRE < 0)
                        {{ $accountingClassification->unity . '(' . number_format($totalClassificationDRE * -1, 0, ',', '.') . ')' }}
                    @else
                        -
                    @endif
                </td>
            @endforeach
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
    <tfoot>
        <tr>
            <td class="sticky-col first-col"></td>
            <td class="sticky-col second-col">{{ __('TOTAL GERAL') }}</td>
            @foreach ($months as $key => $month)
                <td>
                    @php
                        $totalClassificationByMonthDRE = App\Models\AccountingClassification::getTotalClassificationByMonthWithdrawal($key, $year);
                    @endphp
                    @if ($totalClassificationByMonthDRE > 0)
                        {{  'R$' . number_format($totalClassificationByMonthDRE, 0, ',', '.') }}
                    @elseif($totalClassificationByMonthDRE < 0)
                        {{ 'R$ (' . number_format($totalClassificationByMonthDRE * -1, 0, ',', '.') . ')' }}
                    @else
                        -
                    @endif
                </td>
            @endforeach
        </tr>
    </tfoot>
