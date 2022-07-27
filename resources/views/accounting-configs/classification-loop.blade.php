@if (count($accountingClassificationChildrens) > 0)
{{ dd($accountingClassificationChildrens) }}
@endif
@foreach ($accountingClassificationChildrens as $accountingClassification2)
    <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="{{ $type }}">
        <td style="padding-left: {{ $accountingClassification2->depth + 0.5 }}rem">
            <div class="flex">
                <input class="form-checkbox accounting-classification-url mr-2" type="checkbox" name="accounting_classification[{{ $accountingClassification2->id }}]" value="{{ $accountingClassification2->id }}">
                <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification2->id]) }}">
                    {{ $accountingClassification2->classification }}
                </a>
            </div>
        </td>
        <td style="padding-left: {{ $accountingClassification2->depth + 0.5 }}rem">
            <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification2->id]) }}">
                {{ $accountingClassification2->name }}
            </a>
        </td>
        <td style="padding-left: 3.5rem!important;">
            {{ $accountingClassification2->obs }}
        </td>
        <td style="padding-left: 3.5rem!important;">
            <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-classification', ['classification' => $accountingClassification2->id, 'config' => $accountingConfig->id]) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </td>
    </tr>
    @include('accounting-configs.classification-loop', [
        'accountingClassificationChildrens' => $accountingClassification2->children()->whereHas('accountingConfigs', function($q) use($accountingConfig) {
    $q->where('accounting_classification_accounting_config.accounting_config_id', $accountingConfig->id);
})->orderBy('classification')->get(),
        'type' => $type])
@endforeach

