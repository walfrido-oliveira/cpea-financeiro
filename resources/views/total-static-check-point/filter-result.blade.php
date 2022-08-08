<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('#') }}" class="sticky-col first-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Diretoria') }}" class="sticky-col first-col"/>
        @foreach ($months as $month)
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="{{ $month . '/' . $year }}" columnText="{{ $month . '/' . $year }}"/>
        @endforeach
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{!! __('Total') !!}"/>

    </tr>
</thead>
<tbody id="withdrawals_table_content">
    @forelse ($totalStaticCheckPoints as $key => $accountingClassification)
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
                 {{ $accountingClassification->classification_id }}
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
                 {{ $accountingClassification->classification }}
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
                " title="{{ count($accountingClassification->formula) > 0 ? $accountingClassification->formula[0]->formula : '' }}">
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
        <td class="sticky-col first-col"></td>
        <td class="sticky-col second-col">{{ __('TOTAL') }}</td>
        @foreach ($months as $key => $month)
            <td>
                -
            </td>
        @endforeach
        <td></td>
    </tr>
</tfoot>
