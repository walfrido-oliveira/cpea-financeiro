<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Mês') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Matrícula') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Colaborador') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Jornada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Saldo') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Situação') }}"/>
    </tr>
</thead>
<tbody id="check-points_table_content">
    @forelse ($checkPoints as $key => $checkpoint)
        <tr>
            <td>
                <input class="form-checkbox check-point-url" type="checkbox" name="check_point[{{ $checkpoint->id }}]" value="">
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    {{ ucfirst(substr($checkpoint->start->translatedFormat('l'), 0, 3)) . ' - ' .  $checkpoint->start->translatedFormat('d/m') }}
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->employee[0]->employee_id }}
                    @endif
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->full_name }}
                    @endif
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->employee[0]->workingDay->full_description }}
                    @endif
                </a>
            </td>
            <td>
                @if (count($checkpoint->user->employee) > 0)
                    {{ $checkpoint->user->employee[0]->balanceByMonthAndYear($maxMonth, $maxYear) }}
                @endif
            </td>
            <td>
                -
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
