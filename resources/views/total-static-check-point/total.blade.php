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
<tbody id="withdrawals_table_content" class="total">
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
                <td class="month" data-row="{{ $row }}" data-column="{{ $key }}"  style="
                    @if ($accountingClassification->color)
                        color:{{ $accountingClassification->color }};
                    @endif
                    @if ($accountingClassification->bolder)
                        font-weight:bolder;
                    @endif"
                    >
                    -
                </td>
            @endforeach

            <td class="month-total" data-row="{{ $row }}" data-column="{{ $key + 1 }}" style="
            @if ($accountingClassification->color)
                color:{{ $accountingClassification->color }};
            @endif
            @if ($accountingClassification->bolder)
                font-weight:bolder;
            @endif
            ">
                -
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
<tfoot class="total">
    <tr>
        <td class="sticky-col first-col"></td>
        <td class="sticky-col second-col" style="left: 41px;">{{ __('TOTAL') }}</td>
        @foreach ($months as $key => $month)
            <td class="month-footer" data-row="{{ $key }}" data-column="{{ $key }}">
                -
            </td>
        @endforeach
        <td class="month-total-footer" data-row="{{ -1 }}" data-column="{{ $key + 1 }}">
            -
        </td>
    </tr>
</tfoot>

<script>
    document.querySelectorAll(".total .month").forEach(element => {
        const a = document.querySelector(`.type_direto .month input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        const b = document.querySelector(`.type_indireto .month input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        element.innerHTML  = (parseFloat(a.value) + parseFloat(b.value)).toFixed(0) + '%';
    });

    document.querySelectorAll(".total .month-total").forEach(element => {
        const a = document.querySelector(`.type_direto .month-total input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        const b = document.querySelector(`.type_indireto .month-total input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        element.innerHTML  = (parseFloat(a.value) + parseFloat(b.value)).toFixed(0) + '%';
    });

    document.querySelectorAll(".total .month-footer").forEach(element => {
        const a = document.querySelector(`.type_direto .month-footer input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        const b = document.querySelector(`.type_indireto .month-footer input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        element.innerHTML  = (parseFloat(a.value) + parseFloat(b.value)).toFixed(0) + '%';

    });

    document.querySelectorAll(".total .month-total-footer").forEach(element => {
        const a = document.querySelector(`.type_direto .month-total-footer input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        const b = document.querySelector(`.type_indireto .month-total-footer input[data-row='${element.dataset.row}'][data-column='${element.dataset.column}']`);
        element.innerHTML  = (parseFloat(a.value) + parseFloat(b.value)).toFixed(0) + '%';
    });
</script>
