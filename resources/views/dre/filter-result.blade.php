    <tr class="thead-light">
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Classificação') }}" class="sticky-col first-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{!! __('Acumulado') . '<br>' . '(' . $year . ')' !!}" class="sticky-col second-col text-center"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('(%) R.L')  }}" class="sticky-col third-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('(%) N.S.R.')  }}" class="sticky-col fourth-col" style="border-right: 2px solid #ccc" />
        @foreach ($months as $key => $month)
            <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="{{ $month . '/' . $year }}" columnText="{{ $month . '/' . $year }}"/>
        @endforeach
    </tr>
    @forelse ($accountingClassifications as $level => $accountingClassification)
        <tr class="@if ( $accountingClassification->featured) featured @endif total-classification
                   @if($accountingClassification->initial_state == 'open') expanded @else collapsed @endif"
            data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}" data-tt-id="{{ $level }}">
            <td class="sticky-col first-col"
                style="white-space: nowrap;
                  @if ($accountingClassification->featured_color) background-color: {{ $accountingClassification->featured_color }}; @endif
                  @if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                  @if ($accountingClassification->bolder) font-weight:bolder; @endif
                  padding-left: {{ $accountingClassification->depth + 0.5 }}rem"
                  title="{{ $accountingClassification->classification }}">
                  @if (count($accountingClassification->children) > 0)
                    <span class="indenter" style="padding-left: 0px;"><a href="#" title="Collapse">&nbsp;</a></span>
                  @endif
                 {{ $accountingClassification->name }}
            </td>

            <td class="sticky-col second-col amount disablecel" data-id="{{ $accountingClassification->id }}" data-unity="{{ $accountingClassification->unity }}"
                style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }};@endif
                       @if ($accountingClassification->featured_color) background-color: {{ $accountingClassification->featured_color }}; @endif
                       @if ($accountingClassification->bolder) font-weight:bolder; @endif"
                data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}">
                -
            </td>

            <td class="sticky-col third-col rl disablecel"
                @php $formula = App\Models\Formula::where("accounting_classification_id", $accountingClassification->id)->where("type_classification", "RL")->first() @endphp
                title="{{ "[$accountingClassification->id]-$accountingClassification->classification-$accountingClassification->name =" }}{{ $formula ? $formula->formula : "" }}"
                style=" @if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                        @if ($accountingClassification->featured_color) background-color: {{ $accountingClassification->featured_color }}; @endif
                        @if ($accountingClassification->bolder) font-weight:bolder; @endif"
                data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}">
                -
            </td>

            <td class="sticky-col fourth-col nsr disablecel"
                @php $formula = App\Models\Formula::where("accounting_classification_id", $accountingClassification->id)->where("type_classification", "NSR")->first() @endphp
                title="{{ "[$accountingClassification->id]-$accountingClassification->classification-$accountingClassification->name =" }}{{ $formula ? $formula->formula : "" }}"
                style="text-align: center; @if ($accountingClassification->color) color:{{ $accountingClassification->color }};@endif
                                           @if ($accountingClassification->bolder) font-weight:bolder; @endif
                                           @if ($accountingClassification->featured_color) background-color: {{ $accountingClassification->featured_color }}; @endif
                        border-right: 2px solid #ccc;"
                data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}">
                -
            </td>

            @foreach ($months as $key => $month)
                <td style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                           @if ($accountingClassification->featured_color) background-color: {{ $accountingClassification->featured_color }}; @endif
                           @if ($accountingClassification->bolder) font-weight:bolder; @endif"
                    data-id="{{ $accountingClassification->id }}" data-year="{{ $year }}" data-month="{{ $month }}" data-monthkey="{{ $key }}"
                    class="disablecel total-classification-result">
                    -
                </td>
            @endforeach
        </tr>
        @if (count($accountingClassification->children) > 0)
            @include('dre.classification-loop', [
                'accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfigs) {
                    $q->where('accounting_classification_accounting_config.accounting_config_id', count($accountingConfigs) > 0 ? $accountingConfigs[0]->id : 0)
                      ->where('visible', true)
                      ->where('type_classification', 'DRE Ajustável');
                })->orderBy('accounting_classifications.order')->get(),
                'parent' => $level
            ])
        @endif
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
