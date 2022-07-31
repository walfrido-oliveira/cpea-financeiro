<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="classification" columnText="{{ __('Classificação') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="name" columnText="{{ __('Descrição') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="value" columnText="{{ __('Valor') }}"/>
        <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Ações
        </th>
    </tr>
</thead>
<tbody id="accounting-analytics_table_content">
    @forelse ($accountingAnalytics as $key => $analytics)
        <tr>
            <td>
                <input class="form-checkbox accounting-analytics-url" type="checkbox" name="accounting_analytics[{{ $analytics->id }}]" value="{!! route('accounting-analytics.destroy', ['accounting_analytics' => $analytics->id]) !!}">
            </td>
            <td>
                <a class="text-item-table" href="{{ route('accounting-analytics.show', ['accounting_analytics' => $analytics->id]) }}">{{ $analytics->accountingClassification->classification }}</a>
            </td>
            <td>
                <a class="text-item-table" style="padding-left: {{ $analytics->accountingClassification->depth }}rem" href="{{ route('accounting-analytics.show', ['accounting_analytics' => $analytics->id]) }}">{{ $analytics->accountingClassification->name }}</a>
            </td>
            <td >
                <input type="hidden" id="accounting_analytics_value_{{ $key }}" value="{{ $analytics->value }}">
                <input type="hidden" id="accounting_analytics_justification_{{ $key }}" value="{{ $analytics->justification }}">
                <input type="hidden" id="accounting_analytics_id_{{ $key }}" value="{{ $analytics->id }}">
                <a @if($analytics->justification) style="border-bottom: dotted 1px #000000 !important; color: #000000 !important;" title="{{ $analytics->justification }}" @endif
                    class="text-item-table" href="{{ route('accounting-analytics.show', ['accounting_analytics' => $analytics->id]) }}">R${{ number_format ($analytics->value, 2, ',', '.')  }}</a>
            </td>
            <td>
                <button class="btn-transition-warning edit-accounting-analytics" data-id="{{ $key }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button style="display: none" class="hidden btn-transition-danger delete-accounting-analytics" data-url="{!! route('accounting-analytics.destroy', ['accounting_analytics' => $analytics->id]) !!}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="4">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
