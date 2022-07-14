<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="month" columnText="{{ __('Mês') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Matrícula') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Colaborador') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Jornada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Saldo') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="obs" columnText="{{ __('Situação') }}"/>
    </tr>
</thead>
<tbody id="check-points_table_content">
    @forelse ($checkPoints as $key => $checkpoint)
        <tr>
            <td>
                <input class="form-checkbox check-points-url" type="checkbox" name="check-points[{{ $checkpoint->id }}]" value="{!! route('check-points.destroy', ['check_point' => $checkpoint->id]) !!}">
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">{{ $checkpoint->name }}</a>
            </td>
            <td>
                {{ $checkpoint->obs ? $checkpoint->obs : '-'  }}
            </td>
            <td>
            </td>
        <tr>
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
