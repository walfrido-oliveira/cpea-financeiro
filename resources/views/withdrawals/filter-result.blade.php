<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Classificação') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Diretoria') }}"/>
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
            <td>
            </td>
            <td>
                {{ $accountingClassification->classification }}
            </td>
            <td>
                {{ $accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td>
                    R${{ number_format (0, 2, ',', '.')  }}
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
            <td></td>
            <td></td>
            <td>{{ __('TOTAL GERAL') }}</td>
            @foreach ($months as $key => $month)
                <td>R${{ number_format (0, 2, ',', '.')  }}</td>
            @endforeach
        </tr>
    </tfoot>
