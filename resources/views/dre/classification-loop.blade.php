@foreach ($accountingClassificationChildrens as $accountingClassification2)
    <tr @if ($accountingClassification2->featured) class="featured" @endif>
        <td class="sticky-col first-col"
            style="white-space: nowrap;
                @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                @if ($accountingClassification2->bolder) font-weight:bolder; @endif
                padding-left: {{ $accountingClassification2->depth + 0.5 }}rem"
            title="{{ $accountingClassification2->classification }}">
            {{ $accountingClassification2->name }}
        </td>

        <td class="sticky-col second-col"
            style="
                @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                @if ($accountingClassification2->bolder) font-weight:bolder; @endif
        ">
            -
        </td>

        <td class="sticky-col third-col"
            style="
                    @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                    @if ($accountingClassification2->bolder) font-weight:bolder; @endif
        ">
            -
        </td>

        <td class="sticky-col fourth-col"
            style="
                    @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                    @if ($accountingClassification2->bolder) font-weight:bolder; @endif
                ">
            -
        </td>

        @foreach ($months as $key => $month)
            <td  style="@if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif @if ($accountingClassification2->bolder) font-weight:bolder; @endif">
                @php
                    $dre = App\Models\Dre::where("accounting_classification_id", $accountingClassification2->id)->where("month", $key)->where("year", $year)->latest('created_at')->first();
                @endphp

                @if($dre)
                    @php $totalClassificationDRE = $dre->value; @endphp
                @else
                    @php $totalClassificationDRE = $accountingClassification2->getTotalClassificationDRE($key, $year); @endphp
                @endif

                @php $decimal = $accountingClassification2->unity == '%' ? 2 : 0; @endphp

                <a href="#" class="edit-dre inline-flex" data-id="{{ $accountingClassification2->id }}" data-month="{{ $key }}" data-year="{{ $year }}"
                    data-value="{{ $dre ? $dre->value : null }}" data-justification="{{ $dre ? $dre->justification : null }}" data-destroy="{{ $dre ? true : false }}" data-dre="{{ $dre ? $dre->id : null }}"
                    style="@if($dre) text-decoration:underline; text-decoration-style: dotted; @endif"
                    title="@if($dre) {{ $dre->justification }} @else {{ count($accountingClassification2->formula) ? $accountingClassification2->formula[0]->formula : null }} @endif">
                    @if ($totalClassificationDRE > 0)
                        {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '') .  number_format($totalClassificationDRE, $decimal, ',', '.') . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '') }}
                    @elseif($totalClassificationDRE < 0)
                        {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '')  . '(' . number_format($totalClassificationDRE * -1, $decimal, ',', '.') . ')' . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '') }}
                    @else
                        -
                    @endif
                    @if(!$dre)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                    @endif
                </a>
            </td>
        @endforeach
    <tr>
        @if (count($accountingClassification2->children) > 0)
            @include('dre.classification-loop', [
                'accountingClassificationChildrens' => $accountingClassification2->children()->whereHas('accountingConfigs', function($q) use($accountingConfigs) {
                    $q->where('accounting_classification_accounting_config.accounting_config_id', count($accountingConfigs) > 0 ? $accountingConfigs[0]->id : 0)
                      ->where('type_classification', 'DRE Ajustável')
                      ->where('visible', true);
                })->orderBy('accounting_classifications.order')->get()
            ])
        @endif

@endforeach
