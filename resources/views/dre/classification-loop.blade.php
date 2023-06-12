@foreach ($accountingClassificationChildrens as $accountingClassification2)
    <tr class="@if ( $accountingClassification2->featured) featured @endif total-classification"
        data-id="{{ $accountingClassification2->id }}" data-year="{{ $year }}">
        <td class="sticky-col first-col"
            style="white-space: nowrap;
                @if ($accountingClassification2->featured_color) background-color: {{ $accountingClassification2->featured_color }}; @endif
                @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                @if ($accountingClassification2->bolder) font-weight:bolder; @endif
                padding-left: {{ $accountingClassification2->depth + 0.5 }}rem"
            title="{{ $accountingClassification2->classification }}">
            {{ $accountingClassification2->name }}
        </td>

        <td class="sticky-col second-col amount disablecel" data-id="{{ $accountingClassification2->id }}" data-unity="{{ $accountingClassification2->unity }}"
            style="@if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                                       @if ($accountingClassification2->bolder) font-weight:bolder; @endif"
            data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}">
            -
        </td>

        <td class="sticky-col third-col rl disablecel"
            @php $formula = App\Models\Formula::where("accounting_classification_id", $accountingClassification2->id)->where("type_classification", "RL")->first() @endphp
            title="{{ "[$accountingClassification2->id]-$accountingClassification2->classification-$accountingClassification2->name =" }}{{ $formula ? $formula->formula : "" }}"
            style="@if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                                       @if ($accountingClassification2->bolder) font-weight:bolder; @endif"
            data-id="{{ $accountingClassification2->id }}" data-year="{{ $year }}">
            -
        </td>

        <td class="sticky-col fourth-col nsr disablecel"
            @php $formula = App\Models\Formula::where("accounting_classification_id", $accountingClassification2->id)->where("type_classification", "NSR")->first() @endphp
            title="{{ "[$accountingClassification2->id]-$accountingClassification2->classification-$accountingClassification2->name =" }}{{ $formula ? $formula->formula : "" }}"
            style="text-align: center; @if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                                       @if ($accountingClassification2->bolder) font-weight:bolder; @endif border-right: 2px solid #ccc;"
            data-id="{{ $accountingClassification2->id }}" data-year="{{ $year }}">
            -
        </td>

        @foreach ($months as $key => $month)
            <td style="@if ($accountingClassification2->color) color:{{ $accountingClassification2->color }}; @endif
                        @if ($accountingClassification2->bolder) font-weight:bolder; @endif"
                data-id="{{ $accountingClassification2->id }}" data-year="{{ $year }}" data-month="{{ $month }}" data-monthkey="{{ $key }}"
                class="disablecel total-classification-result">
                -
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
