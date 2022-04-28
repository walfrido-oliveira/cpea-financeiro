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
    @forelse ($accountingClassifications1 as $key => $accountingClassification)
        <tr @if ( $accountingClassification->featured)
            class="featured"
        @endif>
            <td class="sticky-col first-col">
                {{ $accountingClassification->classification }}
            </td>
            <td class="sticky-col second-col">
                {{ $accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td>
                    R${{ number_format($accountingClassification->getTotalClassification($key, $year), 2, ',', '.') }}
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
                <td>R${{ number_format (App\Models\AccountingClassification::getTotalClassificationByMonth($key, $year), 2, ',', '.')  }}</td>
            @endforeach
        </tr>
    </tfoot>
