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
    @forelse ($withdrawals as $key => $withdrawal)
        <tr @if ( $withdrawal->accountingClassification->featured)
            class="featured"
        @endif>
            <td class="sticky-col first-col">
                {{ $withdrawal->accountingClassification->classification }}
            </td>
            <td class="sticky-col second-col">
                {{ $withdrawal->accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td>
                    @if($key == $withdrawal->month)
                        R${{ number_format ($withdrawal->value, 2, ',', '.')  }}
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
            <td class="sticky-col second-col">{{ __('TOTAL GERAL') }}</td>
            @foreach ($months as $key => $month)
                <td>R${{ number_format (App\Models\Withdrawal::getTotalByMonth($key, $year), 2, ',', '.')  }}</td>
            @endforeach
        </tr>
    </tfoot>
