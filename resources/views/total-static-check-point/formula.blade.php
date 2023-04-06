@php
    $id1 = $type == 'Custo Direto' ? 0 : 1;
    $id2 = $type != 'Custo Indireto' ? 1 : 0;
@endphp
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
<tbody id="withdrawals_table_content" class="type_{{ $type == 'Custo Direto' ? 'direto' : 'indireto'}}">
    @forelse ($totalStaticCheckPoints as $row => $accountingClassification)
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
                <td class="month"  style="
                    @if ($accountingClassification->color)
                        color:{{ $accountingClassification->color }};
                    @endif
                    @if ($accountingClassification->bolder)
                        font-weight:bolder;
                    @endif"
                    >
                    @php
                        $result = App\Models\TotalStaticCheckPoint::getTotal($year, $key, $accountingClassification->classification_id,
                                                                            App\Models\TotalStaticCheckPoint::getTypes()[$id1],
                                                                            App\Models\TotalStaticCheckPoint::getTypes()[$id2]);
                    @endphp
                    {{ number_format($result * 100, 0)  }}%
                    <input type="hidden" data-row="{{ $row }}" data-column="{{ $key }}" value="{{ $result * 100 }}">
                </td>
            @endforeach

            <td class="month-total" style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                @php
                    $result = App\Models\TotalStaticCheckPoint::getTotal($year, null, $accountingClassification->classification_id,
                                                                        App\Models\TotalStaticCheckPoint::getTypes()[$id1],
                                                                        App\Models\TotalStaticCheckPoint::getTypes()[$id2]);
                @endphp
                {{ number_format($result * 100, 0)  }}%
                <input type="hidden" data-row="{{ $row }}" data-column="{{ $key + 1 }}" value="{{ $result * 100 }}">
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
<tfoot class="type_{{ $type == 'Custo Direto' ? 'direto' : 'indireto'}}">
    <tr>
        <td class="sticky-col first-col"></td>
        <td class="sticky-col second-col" style="left: 41px;">{{ __('TOTAL') }}</td>
        @foreach ($months as $key => $month)
            <td class="month-footer">
                @php
                    $result = App\Models\TotalStaticCheckPoint::getTotal($year, $key, null,
                                                                        App\Models\TotalStaticCheckPoint::getTypes()[$id1],
                                                                        App\Models\TotalStaticCheckPoint::getTypes()[$id2]);
                @endphp
                {{ number_format($result * 100, 0)  }}%
                <input type="hidden" data-row="{{ $key }}" data-column="{{ $key }}"  value="{{ $result * 100 }}">
            </td>
        @endforeach
        <td class="month-total-footer">
            @php
                $result = App\Models\TotalStaticCheckPoint::getTotal($year, null, null,
                                                                    App\Models\TotalStaticCheckPoint::getTypes()[$id1],
                                                                    App\Models\TotalStaticCheckPoint::getTypes()[$id2]);
            @endphp
            {{ number_format($result, 0)  }}%
            <input type="hidden" data-row="-1" data-column="{{ $key + 1 }}" value="{{ $result }}">
        </td>
    </tr>
</tfoot>
