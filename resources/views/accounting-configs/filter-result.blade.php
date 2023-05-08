<tbody id="accounting-classifications_table_content">
    @forelse ($accountingConfigs as $index => $accountingConfig)
        @if (($index > 0 && $accountingConfigs[$index]->year !=
              $accountingConfigs[$index - 1]->year) || $index == 0)
            <tr>
                <td colspan="6" class="bg-gray-100 font-bold">
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
                <td colspan="6" class="bg-gray-100 font-bold" style="padding-left: 2rem !important">
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
            <td colspan="6" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
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
            <td colspan="6" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
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
            <td colspan="6" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
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
            <td colspan="6" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
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
            <td colspan="6" class="text-white font-bold" style="padding-left: 3.5rem !important; background-color: rgb(0, 94, 16)">
                <button type="button" class="show-accounting-config inline-flex" data-point="{{ $accountingConfig->id }}" data-type="formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline minus hidden text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 plus inline text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ "Formulas" }} ({{ $accountingConfig->formulas()->count() }})
                </button>
                <div class="inline-block" style="width: 90%">
                    <div class="w-full flex justify-end" x-data="{ open: false }">
                        <div class="pr-4 flex">
                            <button @click="open = !open" id="nav-toggle" class="w-full block btn-transition-secondary">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                              </svg>
                            </button>
                        </div>
                        <!--Search-->
                        <div :class="{'block': open, 'hidden': !open}" class="w-full block" id="search-content">
                            <div class="container mx-auto">
                                <input id="search_formula" name="q" type="search" placeholder="Buscar..." autofocus="autofocus" class="filter-field w-full form-control no-border"
                                style="color: #fff; background-color: transparent; border-color: #fff;">
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr class="point-items-{{ $accountingConfig->id }} header-formula" data-type="item-formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}" data-classification="Resultado do Exercicio">
            <td style="padding-left: 3.5rem !important;" class="font-bold">
                <input class="form-checkbox formula-select-all mr-2" data-type="item-formula" data-id="{{$accountingConfig->id }}"  type="checkbox" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                Classificação
            </td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Descrição da Classificação</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">ID Fórmula</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Fórmula</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">DT/HR</td>
            <td style="padding-left: 3.5rem !important;" class="font-bold">Ação</td>
        </tr>
        @foreach ($accountingConfig->formulas as $formula)
            <tr class="point-items-{{ $accountingConfig->id }}" data-id="{{$accountingConfig->id }}" data-type="item-formula" data-year="{{ $accountingConfig->year }}" data-month="{{ $accountingConfig->month }}">
                <td style="padding-left: 3.5rem!important;">
                    <div class="flex">
                        <input class="form-checkbox formula-url mr-2" data-id="{{$accountingConfig->id }}"  type="checkbox" name="formula[{{ $formula->id }}]" value="{{ $formula->id }}">
                        <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">
                            {{ $formula->accountingClassification->classification }}
                        </a>
                    </div>
                </td>
                <td style="padding-left: 3.5rem !important;">
                    <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">{{ $formula->accountingClassification->name }}</a>
                </td>
                <td style="padding-left: 3.5rem !important;">
                    <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">{{ $formula->id }}</a>
                </td>
                <td style="padding-left: 3.5rem !important;">
                    <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">{{ $formula->formula }}</a>
                </td>
                <td style="padding-left: 3.5rem !important;">
                    <a class="text-item-table" href="{{ route('formulas.edit', ['formula' => $formula->id]) }}">{{ $formula->pivot->updated_at ? $formula->pivot->updated_at->format("d/m/Y H:i") : '' }}</a>
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
