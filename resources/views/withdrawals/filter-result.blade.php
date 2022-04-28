<thead>
    <tr class="thead-light">
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Classificação') }}" class="sticky-col first-col"/>
        <x-table-sort-header :orderBy="$orderBy" :ascending="$ascending" columnName="" columnText="{{ __('Diretoria') }}" class="sticky-col second-col"/>
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
            <td class="sticky-col first-col">
                {{ $accountingClassification->classification }}
            </td>
            <td class="sticky-col second-col">
                {{ $accountingClassification->name }}
            </td>
            @foreach ($months as $key => $month)
                <td>
                    @php
                        $formula = App\Models\Formula::where('accounting_classification_id', $accountingClassification->id)->get();
                        if($formula)
                        {
                            $re = '/{(.*?)}/m';
                            $formulaText = $formula->formula;
                            preg_match_all($re, $formula, $matches, PREG_SET_ORDER, 0);

                            foreach ($matches as $key2 => $value2)
                            {
                                $result = explode("&", $value2[1]);
                                $classification = App\Models\accountingClassification::where('classification', $result[0])->get();
                                if($classification)
                                {
                                    $sum = 0;
                                    $withdrawals = App\Models\Withdrawal::where('accounting_classification_id', $classification->id)
                                    ->where('month', $month)
                                    ->where(DB::raw('YEAR(created_at)'), '=', $year)
                                    ->get();
                                    foreach ($withdrawals as $key => $withdrawal)
                                    {
                                        $sum += $withdrawal->value;
                                    }
                                }
                                $formulaText = Str::replace($value2[0], $sum);
                            }

                            $stringCalc = new StringCalc();
                            $result = $stringCalc->calculate($formulaText);

                            echo $result;
                        }
                    @endphp
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
            <td class="sticky-col first-col"></td>
            <td class="sticky-col second-col">{{ __('TOTAL GERAL') }}</td>
            @foreach ($months as $key => $month)
                <td>R${{ number_format (0, 2, ',', '.')  }}</td>
            @endforeach
        </tr>
    </tfoot>
