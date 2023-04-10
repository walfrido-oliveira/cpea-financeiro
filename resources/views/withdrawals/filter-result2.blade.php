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
    @forelse ($accountingClassifications2 as $key => $accountingClassification)
        <tr @if ( $accountingClassification->featured) class="featured" @endif>
            <td class="sticky-col first-col"
                style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                      @if ($accountingClassification->bolder) font-weight:bolder; @endif ">
                {{ $accountingClassification->classification }}
            </td>
            <td class="sticky-col second-col" style="left: 100px; @if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                                                      @if ($accountingClassification->bolder) font-weight:bolder; @endif">
                {{ $accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td class="edit-dre"  style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                                             @if ($accountingClassification->bolder) font-weight:bolder; @endif">

                    @php
                        $withdrawal = App\Models\Withdrawal::where('year', $year)
                            ->where('month', $key)
                            ->where('accounting_classification_id', $accountingClassification->id)
                            ->first();
                    @endphp

                    <a href="#" class="edit-withdrawal"
                       style="@if($withdrawal) @if($withdrawal->justification)text-decoration:underline; text-decoration-style: dotted; @endif @endif"
                       title="@if($withdrawal) {{ $withdrawal->justification }} @endif"
                       data-id="{{ $accountingClassification->id }}" data-month="{{ $key }}" data-year="{{ $year }}">
                        @php
                            $totalByMonthAndClassification = App\Models\Withdrawal::getTotalByMonthAndClassification($key, $year, $accountingClassification->id);
                            $decimal = $accountingClassification->unity == '%' ? 2 : 0;
                        @endphp
                        @if ($totalByMonthAndClassification > 0)
                            {{ ($accountingClassification->unity == 'R$' ?
                            $accountingClassification->unity  : '') .  number_format($totalByMonthAndClassification, $decimal, ',', '.') .
                            ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '') }}
                        @elseif($totalByMonthAndClassification < 0)
                            {{ ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '') .
                            '(' . number_format($totalByMonthAndClassification * -1, $decimal, ',', '.') . ')' .
                            ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '')  }}
                        @else
                            -
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                </td>
            @endforeach
        </tr>
        @include('withdrawals.classification-loop', [
            'accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfigs) {
                $q->where('accounting_classification_accounting_config.accounting_config_id', count($accountingConfigs) > 0 ? $accountingConfigs[0]->id : 0);
            })->orderBy('accounting_classifications.order')->get()
        ])
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
                    $totalClassificationByMonthDRE = App\Models\Withdrawal::getTotalByMonth($key, $year);
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
