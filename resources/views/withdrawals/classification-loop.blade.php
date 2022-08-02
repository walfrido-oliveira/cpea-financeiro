
@foreach ($accountingClassificationChildrens as $key => $t)
<tr @if ( $accountingClassification2->featured)
    class="featured"
     @endif
>
    <td class="sticky-col first-col"
    style="
    @if ($accountingClassification2->color)
        color:{{ $accountingClassification2->color }};
    @endif
    @if ($accountingClassification2->bolder)
        font-weight:bolder;
    @endif
    ">
        {{ $accountingClassification2->classification }}
    </td>
    <td class="sticky-col second-col"
    style="
    @if ($accountingClassification2->color)
        color:{{ $accountingClassification2->color }};
    @endif
    @if ($accountingClassification2->bolder)
        font-weight:bolder;
    @endif
    ">
        {{ $accountingClassification2->name }}
    </td>
    @foreach ($months as $key2 => $month)
        <td  style="
        @if ($accountingClassification2->color)
            color:{{ $accountingClassification2->color }};
        @endif
        @if ($accountingClassification2->bolder)
            font-weight:bolder;
        @endif
        ">
            @php
                $totalByMonthAndClassification = App\Models\Withdrawal::getTotalByMonthAndClassification($key2, $year, $accountingClassification2->id);
                $decimal = $accountingClassification2->unity == '%' ? 2 : 0;
            @endphp
            @if ($totalByMonthAndClassification > 0)
                {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '') .  $accountingClassification2->unity . number_format($totalByMonthAndClassification, $decimal, ',', '.') . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '') }}
            @elseif($totalByMonthAndClassification < 0)
                {{ ($accountingClassification2->unity == 'R$' ? $accountingClassification2->unity  : '') .  $accountingClassification2->unity . '(' . number_format($totalByMonthAndClassification * -1, $decimal, ',', '.') . ')' . ($accountingClassification2->unity == '%' ? $accountingClassification2->unity  : '')  }}
            @else
                -
            @endif
        </td>
    @endforeach
</tr>
@include('withdrawals.classification-loop', [
    'accountingClassificationChildrens' => $accountingClassification2->children()->whereHas('accountingConfigs', function($q) use($accountingConfigs) {
        $q->where('accounting_classification_accounting_config.accounting_config_id', count($accountingConfigs) > 0 ? $accountingConfigs[0]->id : 0);
    })->orderBy('accounting_classifications.order')->get()
])
@endforeach
