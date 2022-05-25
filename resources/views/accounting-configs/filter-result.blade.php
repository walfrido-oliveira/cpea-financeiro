<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
    </tr>
</thead>
<tbody id="accounting-classifications_table_content">
    @forelse ($accountingConfigs as $index => $accountingConfig)
        @if (($index > 0 && $accountingConfigs[$index]->year !=
              $accountingConfigs[$index - 1]->year) || $index == 0)
            <tr>
                <td colspan="5" class="bg-gray-100 font-bold">
                    <button type="button" class="show-accounting-config" data-accountingConfig="{{ $accountingConfig->year }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $accountingConfig->year }}
                    </button>
                </td>
            </tr>
        @endif

        @if (($index > 0 && $accountingConfigs[$index]->month !=
              $accountingConfigs[$index - 1]->month) || $index == 0)
            <tr>
                <td colspan="5" class="bg-gray-100 font-bold" style="padding-left: 2rem !important">
                    <button type="button" class="show-accounting-config" data-accountingConfig="{{ $accountingConfig->month }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ months()[$accountingConfig->month] }}
                    </button>
                </td>
            </tr>
        @endif
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
