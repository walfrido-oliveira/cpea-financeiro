<tbody id="accounting-classifications_table_content">
    @forelse ($accountingConfigs as $index => $accountingConfig)
        @if (($index > 0 && $accountingConfigs[$index]->year !=
              $accountingConfigs[$index - 1]->year) || $index == 0)
            <tr>
                <td colspan="4" class="bg-gray-100 font-bold">
                    <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="year" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus  text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus hidden inline text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $accountingConfig->year }} ({{ $accountingConfig->accountingClassifications()->count() + $accountingConfig->formulas()->count() }})
                    </button>
                </td>
            </tr>
        @endif

        @if (($index > 0 && $accountingConfigs[$index]->month !=
              $accountingConfigs[$index - 1]->month) || $index == 0)
            <tr class="point-items-{{ $accountingConfig->id }} active" data-type="month" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                <td colspan="4" class="bg-gray-100 font-bold" style="padding-left: 2rem !important">
                    <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="month" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus  text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus hidden inline text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ months()[$accountingConfig->month] }} ({{ $accountingConfig->accountingClassifications()->count() + $accountingConfig->formulas()->count() }})
                    </button>
                </td>
            </tr>
        @endif

        <tr class="point-items-{{ $accountingConfig->id }} active" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
            <td colspan="4" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-type="classification" data-classification="DRE">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Classificações Contábeis - DRE" }} ({{ $accountingConfig->accountingClassifications()->where('type_classification', 'DRE')->count() }})
                </button>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="DRE">
            <td style="padding-left: 3.5rem !important;" class="font-bold">Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Descrição</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Observações</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->accountingClassifications()->where('type_classification', 'DRE')
        ->where('accounting_classifications.accounting_classification_id', null)
        ->orderBy("accounting_classifications.order")->get() as $accountingClassification)
            <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="DRE">
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <div class="flex">
                        <input class="form-checkbox accounting-classification-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="accounting_classification[{{ $accountingClassification->id }}]" value="{{ $accountingClassification->id }}">
                        <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                            {{ $accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                        {{ $accountingClassification->name }}
                    </a>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    {{ $accountingClassification->obs }}
                </td>
                <td style="padding-left: 3.5rem!important;">
                    <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-classification', ['classification' => $accountingClassification->id, 'config' => $accountingConfig->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </td>
            </tr>
            @include('accounting-configs.classification-loop', ['accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfig) {
                $q->where('accounting_classification_accounting_config.accounting_config_id', $accountingConfig->id);
            })->orderBy('order')->get(), 'type' => 'DRE'])
        @endforeach

        <tr class="point-items-{{ $accountingConfig->id }} active" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
            <td colspan="4" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Retiradas Gerenciais">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Classificações Contábeis - Retiradas Gerenciais" }} ({{ $accountingConfig->accountingClassifications()->where('type_classification', 'RETIRADAS GERENCIAIS')->count() }})
                </button>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Retiradas Gerenciais">
            <td style="padding-left: 3.5rem !important;" class="font-bold">Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Descrição</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Observações</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->accountingClassifications()
        ->where('type_classification', 'RETIRADAS GERENCIAIS')
        ->where('accounting_classifications.accounting_classification_id', null)
        ->orderBy("accounting_classifications.order")->get() as $accountingClassification)
            <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Retiradas Gerenciais">
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <div class="flex">
                        <input class="form-checkbox accounting-classification-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="accounting_classification[{{ $accountingClassification->id }}]" value="{{ $accountingClassification->id }}">
                        <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                            {{ $accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                        {{ $accountingClassification->name }}
                    </a>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    {{ $accountingClassification->obs }}
                </td>
                <td style="padding-left: 3.5rem!important;">
                    <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-classification', ['classification' => $accountingClassification->id, 'config' => $accountingConfig->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </td>
            </tr>
            @include('accounting-configs.classification-loop', ['accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfig) {
                $q->where('accounting_classification_accounting_config.accounting_config_id', $accountingConfig->id);
            })->orderBy('order')->get(), 'type' => 'Retiradas Gerenciais'])
        @endforeach

        <tr class="point-items-{{ $accountingConfig->id }} active" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
            <td colspan="4" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Resultado do Exercicio">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Classificações Contábeis - Resultado do Exercício" }} ({{ $accountingConfig->accountingClassifications()->where('type_classification', 'RESULTADOS DO EXERCICIO')->count() }})
                </button>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Resultado do Exercicio">
            <td style="padding-left: 3.5rem !important;" class="font-bold">Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Descrição</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Observações</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->accountingClassifications()
        ->where('type_classification', 'RESULTADOS DO EXERCICIO')
        ->where('accounting_classifications.accounting_classification_id', null)
        ->orderBy("accounting_classifications.order")->get() as $accountingClassification)
            <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Resultado do Exercicio">
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <div class="flex">
                        <input class="form-checkbox accounting-classification-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="accounting_classification[{{ $accountingClassification->id }}]" value="{{ $accountingClassification->id }}">
                        <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                            {{ $accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                        {{ $accountingClassification->name }}
                    </a>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    {{ $accountingClassification->obs }}
                </td>
                <td style="padding-left: 3.5rem!important;">
                    <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-classification', ['classification' => $accountingClassification->id, 'config' => $accountingConfig->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </td>
            </tr>
            @include('accounting-configs.classification-loop', ['accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfig) {
                $q->where('accounting_classification_accounting_config.accounting_config_id', $accountingConfig->id);
            })->orderBy('order')->get(), 'type' => 'Resultado do Exercicio'])
        @endforeach

        <tr class="point-items-{{ $accountingConfig->id }} active" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
            <td colspan="4" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="DRE Ajustável">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Classificações Contábeis - DRE Ajustável" }} ({{ $accountingConfig->accountingClassifications()->where('type_classification', 'DRE AJUSTÁVEL')->count() }})
                </button>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="DRE Ajustável">
            <td style="padding-left: 3.5rem !important;" class="font-bold">Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Descrição</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Observações</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->accountingClassifications()->where('type_classification', 'DRE AJUSTÁVEL')
        ->where('accounting_classifications.accounting_classification_id', null)
        ->orderBy("accounting_classifications.order")->get() as $accountingClassification)
            <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="DRE Ajustável">
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <div class="flex">
                        <input class="form-checkbox accounting-classification-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="accounting_classification[{{ $accountingClassification->id }}]" value="{{ $accountingClassification->id }}">
                        <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                            {{ $accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: {{ $accountingClassification->depth + 0.5 }}rem">
                    <a class="text-item-table" href="{{ route('accounting-classifications.edit', ['accounting_classification' => $accountingClassification->id]) }}">
                        {{ $accountingClassification->name }}
                    </a>
                </td>
                <td style="padding-left: 3.5rem!important;">
                    {{ $accountingClassification->obs }}
                </td>
                <td style="padding-left: 3.5rem!important;">
                    <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-classification', ['classification' => $accountingClassification->id, 'config' => $accountingConfig->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </td>
            </tr>
            @include('accounting-configs.classification-loop', [
                'accountingClassificationChildrens' => $accountingClassification->children()->whereHas('accountingConfigs', function($q) use($accountingConfig) {
                        $q->where('accounting_classification_accounting_config.accounting_config_id', $accountingConfig->id);
                    })->orderBy('accounting_classifications.order')->get(),
                'type' => 'DRE Ajustável'])
        @endforeach

        <tr class="point-items-{{ $accountingConfig->id }} active" data-type="classification" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
            <td colspan="4" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config" data-point="{{ $accountingConfig->id }}" data-type="formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Formulas" }} ({{ $accountingConfig->formulas()->count() }})
                </button>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Resultado do Exercicio">
            <td style="padding-left: 3.5rem !important;" class="font-bold">Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Fórmula</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Observações</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->formulas as $formula)
            <tr class="point-items-{{ $accountingConfig->id }}" data-type="item-formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                <td style="padding-left: 3.5rem!important;">
                    <div class="flex">
                        <input class="form-checkbox formula-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="formula[{{ $formula->id }}]" value="{{ $formula->id }}">
                        <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">
                            {{ $formula->accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: 3.5rem !important;">
                    <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">{{ $formula->formula }}</a>
                </td>
                <td style="padding-left: 3.5rem!important;">
                    {{ $formula->obs }}
                </td>
                <td style="padding-left: 3.5rem!important;">
                    <button type="button" class="delete-accounting-configs" data-url="{{ route('accounting-configs.delete-formula', ['formula' => $formula->id, 'config' => $accountingConfig->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </td>
                </tr>
        @endforeach
    @empty
        <tr>
            <td class="text-center" colspan="5">{{ __("Nenhum resultado encontrado") }}</td>
        </tr>
    @endforelse
<tbody>
