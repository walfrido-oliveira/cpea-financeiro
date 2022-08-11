<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="month" columnText="{{ __('Mês/Ano') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="type" columnText="{{ __('Tipo de Arquivo') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Observações') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="updated_at" columnText="{{ __('Data de Modificação') }}"/>
        <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Ações
        </th>
    </tr>
</thead>
<tbody id="accounting-controls_table_content">
    @forelse ($accountingControls as $key => $accountingControl)
        <tr>
            <td>
                <input class="form-checkbox accounting-controls-url" type="checkbox" name="accounting_controls[{{ $accountingControl->id }}]" value="{!! route('accounting-controls.destroy', ['accounting_control' => $accountingControl->id]) !!}">
            </td>
            <td>
                <a class="text-item-table text-green-600 underline" href="{{ route('accounting-controls.show', ['accounting_control' => $accountingControl->id]) }}">{{ months()[$accountingControl->month] }}/{{ $accountingControl->year }}</a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('accounting-controls.show', ['accounting_control' => $accountingControl->id]) }}">{{ $accountingControl->type }}</a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('accounting-controls.show', ['accounting_control' => $accountingControl->id]) }}">{{ $accountingControl->obs }}</a>
            </td>
            <td>
                {{ $accountingControl->updated_at->format("d/m/Y H:i")  }}
            </td>
            <td>
                <a class="btn-transition-warning" href="{{ route('accounting-controls.edit', ['accounting_control' => $accountingControl->id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <button class="btn-transition-danger delete-accounting-controls" data-url="{!! route('accounting-controls.destroy', ['accounting_control' => $accountingControl->id]) !!}">
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
