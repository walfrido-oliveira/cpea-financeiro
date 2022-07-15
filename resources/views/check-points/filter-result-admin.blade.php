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
    @php
        $total = 0;
    @endphp
    @forelse ($checkPoints as $key => $checkpoint)
        @php
            $pointManagement = App\Models\PointManagement::where("employee_id", $checkpoint->user->employee[0]->id)
            ->where("month", $maxMonth)
            ->where("year", $maxYear)
            ->first();
        @endphp
        <tr>
            <td>
                @if (!$pointManagement)
                    <input class="form-checkbox check-point-employee-id" type="checkbox" value="{{ $checkpoint->user->employee[0]->id }}">
                @endif
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    {{ ucfirst(substr($checkpoint->start->translatedFormat('M'), 0, 3)) . $checkpoint->start->translatedFormat('/Y') }}
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->employee[0]->employee_id }}
                    @endif
                </a>
            </td>
            <td>
                <a class="text-item-table" href="{{ route('check-points.show', ['user_id' => $checkpoint->user_id]) }}">
                    @if (count($checkpoint->user->employee) > 0)
                        {{ $checkpoint->user->full_name }}
                    @endif
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
                @if (count($checkpoint->user->employee) > 0)
                    {{ $checkpoint->user->employee[0]->balanceByMonthAndYear($maxMonth, $maxYear) }}
                @endif
            </td>
            <td>
                @if ($pointManagement)
                   @switch($pointManagement->status)
                       @case('approved')
                            <svg title="Aprovado" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                           @break
                       @case('disapproved')
                            <svg title="Reprovado" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                           @break
                       @default

                   @endswitch
                @else
                    <svg title="Pendente" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </td>
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
        <td></td>
        <td></td>
        <td></td>
        <td>{{ App\Models\Employee::totalBalanceByMonthAndYear($maxMonth, $maxYear) }}</td>
        <td></td>
    </tr>
</tfoot>
