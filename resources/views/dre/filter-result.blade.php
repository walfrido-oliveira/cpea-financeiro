<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Classificação') }}" class="sticky-col first-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{!! __('Acumulado') . '<br>' . '(' . $year . ')' !!}" class="sticky-col second-col text-center"/>
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('(%) R.L')  }}" class="sticky-col third-col"/>
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('(%) N.S.R.')  }}" class="sticky-col fourth-col"/>
        @foreach ($months as $month)
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="{{ $month . '/' . $year }}" columnText="{{ $month . '/' . $year }}"/>
        @endforeach
    </tr>
</thead>
<tbody id="withdrawals_table_content">
    @forelse ($accountingClassifications as $key => $accountingClassification)
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
            padding-left: {{ $accountingClassification->depth + 0.5 }}rem" title="{{ $accountingClassification->classification }}">
                 {{ $accountingClassification->name }}
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
                -
            </td>

            <td class="sticky-col third-col"
            style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                -
            </td>

            <td class="sticky-col fourth-col"
            style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                -
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
                        $totalClassificationDRE = $accountingClassification->getTotalClassificationDRE($key, $year);
                        $decimal = $accountingClassification->unity == '%' ? 2 : 0;
                    @endphp
                    @if ($totalClassificationDRE > 0)
                        {{ ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '') .  number_format($totalClassificationDRE, $decimal, ',', '.') . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '') }}
                    @elseif($totalClassificationDRE < 0)
                        {{ ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '')  . '(' . number_format($totalClassificationDRE * -1, $decimal, ',', '.') . ')' . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '') }}
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
        <td class="sticky-col first-col">{{ __('TOTAL GERAL') }}</td>
        <td class="sticky-col second-col">-</td>
        <td class="sticky-col third-col">-</td>
        <td class="sticky-col fourth-col">-</td>
        @foreach ($months as $key => $month)
            <td>
                @php
                    $totalClassificationByMonthDRE = App\Models\AccountingClassification::getTotalClassificationByMonthDRE($key, $year);
                    $decimal = $accountingClassification->unity == '%' ? 2 : 0;
                @endphp
                @if ($totalClassificationByMonthDRE > 0)
                    {{  'R$' . number_format($totalClassificationByMonthDRE, $decimal, ',', '.') }}
                @elseif($totalClassificationByMonthDRE < 0)
                    {{ 'R$ (' . number_format($totalClassificationByMonthDRE * -1, $decimal, ',', '.') . ')' }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
</tfoot>
