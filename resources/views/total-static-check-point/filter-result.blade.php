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
        <tr>
            <td class="sticky-col first-col"
                style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                       @if ($accountingClassification->bolder) font-weight:bolder; @endif padding-left: {{ $accountingClassification->depth + 0.5 }}rem"
                title="{{ $accountingClassification->classification }}">
                 {{ $accountingClassification->classification_id }}
            </td>

            <td class="sticky-col second-col" style="left: 49px; @if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                                                                 @if ($accountingClassification->bolder) font-weight:bolder; @endif">
                {{ $accountingClassification->classification }}
            </td>

            @foreach ($months as $key => $month)
                <td class="edit-dre"  style="white-space: nowrap; @if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif
                                                                  @if ($accountingClassification->bolder) font-weight:bolder; @endif"
                >

                    @php
                        $checkPoint = App\Models\TotalStaticCheckPoint::where('year', $year)
                            ->where('month', $key)
                            ->where('classification_id', $accountingClassification->classification_id)
                            ->where('type', $type)
                            ->first();

                    @endphp
                    <a href="#" class="edit-check-point"
                       style="@if($checkPoint->justification) text-decoration:underline; text-decoration-style: dotted; @endif"
                       title="{{ $checkPoint->justification }}"
                       data-id="{{ $accountingClassification->classification_id }}" data-month="{{ $key }}" data-year="{{ $year }}" data-type="{{ $type }}">
                        {{ number_format( App\Models\TotalStaticCheckPoint::where('year', $year)
                            ->where('month', $key)
                            ->where('classification_id', $accountingClassification->classification_id)
                            ->where('type', $type)
                            ->sum('result'), 2, ",", ".")
                        }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                </td>
            @endforeach

            <td style="@if ($accountingClassification->color) color:{{ $accountingClassification->color }}; @endif @if ($accountingClassification->bolder) font-weight:bolder; @endif">
                {{ number_format(App\Models\TotalStaticCheckPoint::where('year', $year)
                ->where('classification_id', $accountingClassification->classification_id)
                ->where('type', $type)
                ->sum('result'), 2, ",", ".")
                }}
            </td>
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
        <td class="sticky-col second-col" style="left: 41px;">{{ __('TOTAL') }}</td>
        @foreach ($months as $key => $month)
            <td>
                {{ number_format(App\Models\TotalStaticCheckPoint::where('year', $year)
                ->where('month', $key)
                ->where('type', $type)
                ->sum('result'), 2, ",", ".") }}
            </td>
        @endforeach
        <td>
            {{ number_format(App\Models\TotalStaticCheckPoint::where('year', $year)
            ->where('type', $type)
            ->sum('result'), 2, ",", ".") }}
        </td>
    </tr>
</tfoot>
