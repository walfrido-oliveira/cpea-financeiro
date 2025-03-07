<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Sem/Dia') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Jornada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Entrada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Saída') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Saldo') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Colaborador') }}"/>
    </tr>
</thead>
<tbody id="check-points_table_content">
    @forelse ($checkPoints as $key => $checkpoint)
        <tr>
            <td>
                <button type="button" class="show-check-points" data-day="{{ $checkpoint->start }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    {{ ucfirst(substr($checkpoint->start->translatedFormat('l'), 0, 3)) . ' - ' .  $checkpoint->start->translatedFormat('d/m') }}
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->employee[0]->workingDay->full_description }}
                    @endif
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    {{ $checkPoints->where('start', $checkpoint->start)->min('start')->format('H:i') }}
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    {{ $checkPoints->where('start', $checkpoint->start)->max('end')->format('H:i') }}
                </a>
            </td>
            <td>
                @if (count($checkpoint->user->employee) > 0)
                    {{ $checkpoint->user->employee[0]->balance($checkpoint->start) }}
                @endif
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    {{ $checkpoint->user->full_name }}
                </a>
            </td>
        <tr>
        <tr class="checkpoint-table" style="display: none;" data-day="{{ $checkpoint->start }}">
            <td class="header"></td>
            <td class="header">ID</td>
            <td class="header">Atividade</td>
            <td class="header">Entrada</td>
            <td class="header">Saída</td>
            <td class="header">Descrição</td>
            <td class="header"></td>
        </tr>
        @foreach ($checkPoints->where('start', $checkpoint->start) as $checkpoint2)
            <tr class="checkpoint-table" style="display: none;" data-day="{{ $checkpoint->start }}">
                <td></td>
                <td>{{ $checkpoint2->activity_id ? str_pad($checkpoint2->activity->id, 3, "0", STR_PAD_LEFT) : $checkpoint2->project_id  }}</td>
                <td>{{ $checkpoint2->activity_id ? $checkpoint2->activity->name : $checkpoint2->project_id  }}</td>
                <td>{{ $checkpoint2->start->format("H:i") }}</td>
                <td>{{ $checkpoint2->end->format("H:i") }}</td>
                <td>{{ $checkpoint2->description }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
