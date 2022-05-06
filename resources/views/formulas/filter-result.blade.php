<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="accounting_classification_id" columnText="{{ __('Classificação') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="formula" columnText="{{ __('Fórmula') }}"/>
        <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Ações
        </th>
    </tr>
</thead>
<tbody id="formulas_table_content">
    @forelse ($formulas as $key => $formula)
        <tr>
            <td>
                <input class="form-checkbox formulas-url" type="checkbox" name="formulas[{{ $formula->id }}]" value="{!! route('formulas.destroy', ['formula' => $formula->id]) !!}">
            </td>
            <td>
                <a class="text-item-table text-green-600 underline" href="{{ route('formulas.show', ['formula' => $formula->accounting_classification_id]) }}">{{ $formula->accountingClassification->description }}</a>
            </td>
            <td>
                {{ $formula->formula  }}
            </td>
            <td>
                <a class="btn-transition-warning" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <button class="btn-transition-danger delete-formulas" data-url="{!! route('formulas.destroy', ['formula' => $formula->id]) !!}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
