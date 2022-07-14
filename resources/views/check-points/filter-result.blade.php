<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="null" :ascending="null" columnName="" columnText="{{ __('') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Sem/Dia') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Jornada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Entrada') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Saída') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Saldo') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Colaborador') }}"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Situação') }}"/>
    </tr>
</thead>
<tbody id="check-points_table_content">
    @forelse (App\Models\CheckPoint::where("user_id", auth()->user()->id)->groupBy('start')->get() as $key => $checkpoint)
        <tr>
            <td></td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    {{ ucfirst(substr($checkpoint->start->translatedFormat('l'), 0, 3)) . ' - ' .  $checkpoint->start->translatedFormat('d/m') }}
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
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    {{ $checkPoints->where('start', $checkpoint->start)->min('start')->format('H:i') }}
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    {{ $checkPoints->where('start', $checkpoint->start)->max('end')->format('H:i') }}
                </a>
            </td>
            <td>
                @if (count($checkpoint->user->employee) > 0)
                    {{ $checkpoint->user->employee[0]->balance($checkpoint->start) }}
                @endif
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['check_point' => $checkpoint->id]) }}">
                    {{ $checkpoint->user->full_name }}
                </a>
            </td>
            <td></td>
        <tr>
        <tr class="checkpoint-table">
            <td class="header"></td>
            <td class="header">ID</td>
            <td class="header">Atividade</td>
            <td class="header">Entrada</td>
            <td class="header">Saída</td>
            <td class="header">Descrição</td>
            <td class="header"></td>
            <td class="header"></td>
        </tr>
        @foreach ($checkPoints->where('start', $checkpoint->start) as $checkpoint2)
            <tr class="checkpoint-table">
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
