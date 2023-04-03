@foreach ($accountingClassificationChildrens as $accountingClassification2)
    <tr @if ($accountingClassification2->featured) class="featured" @endif>
        <td class="sticky-col first-col"
            style="
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
            <td style="@if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif @if ($accountingClassification2->bolder) font-weight:bolder; @endif"
                title="{{ count($accountingClassification2->formula) > 0 ? $accountingClassification2->formula[0]->formula : '' }}">
                <a href="#" class="edit-dre" data-id="{{ $accountingClassification2->id }}" data-month="{{ $key }}" data-year="{{ $year }}" data-value="">
                    @if(count(App\Models\Dre::where("accounting_classification_id", $accountingClassification2->id)->
                                   where("month", $key)->where("year", $year)->get()))
                        @php
                            $totalClassificationDRE = App\Models\Dre::where("accounting_classification_id", $accountingClassification2->id)->
                                                                    where("month", $key)->where("year", $year)->latest('created_at')->first()->value;
                        @endphp
                    @else
                        @php
                            $totalClassificationDRE = $accountingClassification2->getTotalClassificationDRE($key, $year);
                        @endphp
                    @endif

                    @php $decimal = $accountingClassification2->unity == '%' ? 2 : 0; @endphp

                    @if ($totalClassificationDRE > 0)
                        {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '') .  number_format($totalClassificationDRE, $decimal, ',', '.') . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '') }}
                    @elseif($totalClassificationDRE < 0)
                        {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '')  . '(' . number_format($totalClassificationDRE * -1, $decimal, ',', '.') . ')' . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '') }}
                    @else
                        -
                    @endif
                </a>
            </td>
        @endforeach
    <tr>
        @if (count($accountingClassification2->children) > 0)
            @include('dre.classification-loop', [
                'accountingClassificationChildrens' => $accountingClassification2->children()->whereHas('accountingConfigs', function($q) use($accountingConfigs) {
                    $q->where('accounting_classification_accounting_config.accounting_config_id', count($accountingConfigs) > 0 ? $accountingConfigs[0]->id : 0)
                    ->where('type_classification', 'DRE Ajustável');
                })->orderBy('accounting_classifications.order')->get()
            ])
        @endif

@endforeach
