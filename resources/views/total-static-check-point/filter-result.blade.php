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
            style="left: 49px;
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                 {{ $accountingClassification->classification }}
            </td>


            @foreach ($months as $key => $month)
                <td  style="
                @if ($accountingClassification->color)
                    color:{{ $accountingClassification->color }};
                @endif
                @if ($accountingClassification->bolder)
                    font-weight:bolder;
                @endif"
                >
                {{ App\Models\TotalStaticCheckPoint::where('year', $year)->where('month', $key)
                        ->where('classification_id', $accountingClassification->classification_id)
                        ->sum('result')
                }}

                </td>
            @endforeach

            <td style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                {{ App\Models\TotalStaticCheckPoint::where('year', $year)
                ->where('classification_id', $accountingClassification->classification_id)
                ->sum('result')
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
                {{ App\Models\TotalStaticCheckPoint::where('year', $year)
                ->where('month', $key)
                ->sum('result') }}
            </td>
        @endforeach
        <td>
            {{ App\Models\TotalStaticCheckPoint::where('year', $year)
            ->sum('result') }}
        </td>
    </tr>
</tfoot>
