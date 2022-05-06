<x-app-layout>
    <div class="py-6 edit-formulas">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('formulas.update', ['formula' => $formula->id]) }}">
                @csrf
                @method("PUT")
                <div class="flex md:flex-row flex-col">
                    <div class="w-full flex items-center">
                        <h1>{{ __('Formula') }}</h1>
                    </div>
                    <div class="w-full flex justify-end">
                        <div class="m-2 ">
                            <button type="submit" class="btn-outline-success">{{ __('Confirmar') }}</button>
                        </div>
                        <div class="m-2">
                            <a href="{{ route('formulas.index')}}" class="btn-outline-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:flex-row flex-col">
                    <x-jet-validation-errors class="mb-4" />
                </div>

                <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Classificação') }}" required/>
                            <x-custom-select :options="$accountingClassifications" name="accounting_classification_id" id="accounting_classification_id" required :value="$formula->accounting_classification_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="accounting_classification_formula" value="{{ __('Classificação Cálculo') }}"/>
                            <div class="w-full flex py-2">
                                <x-custom-select :options="$accountingClassificationsCalc" name="accounting_classification_formula" id="accounting_classification_formula" value=""/>
                                <button type="button" class="btn-transition-primary px-2" id="btn_accounting_classification_formula_add" title="Adicionar nova classificação">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="formula" value="{{ __('Fórmula') }}" required/>
                            <textarea class="form-input w-full" name="formula" id="formula" cols="30" rows="10" required>{{ $formula->formula }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="accounting_classification_conditional" value="{{ __('Classificação Condição') }}"/>
                            <div class="w-full flex py-2">
                                <x-custom-select :options="$accountingClassificationsCalc" name="accounting_classification_conditional" id="accounting_classification_conditional" value=""/>
                                <button type="button" class="btn-transition-primary px-2" id="btn_accounting_classification_conditional_add" title="Adicionar nova classificação">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="conditional" value="{{ __('Condição') }}"/>
                            <textarea class="form-input w-full" name="conditional" id="conditional" cols="30" rows="3">{{ $formula->conditional }}</textarea>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="conditional_type" value="{{ __('Tipo de Condição') }}"/>
                            <x-custom-select select-class="no-nice-select" :options="$conditionalTypes" name="conditional_type" id="conditional_type" :value="$formula->conditional_type"/>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="conditional_value" value="{{ __('Valor da Condição') }}"/>
                            <x-jet-input id="conditional_value" class="form-control block mt-1 w-full" type="number" name="conditional_value" maxlength="255" :value="$formula->conditional_value"/>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="accounting_classification_conditional_formula" value="{{ __('Classificação Formula Negação') }}"/>
                            <div class="w-full flex py-2">
                                <x-custom-select :options="$accountingClassificationsCalc" name="accounting_classification_conditional_formula" id="accounting_classification_conditional_formula" value=""/>
                                <button type="button" class="btn-transition-primary px-2" id="btn_accounting_classification_conditional_formula_add" title="Adicionar nova classificação">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-1">
                            <x-jet-label for="conditional_formula" value="{{ __('Formula da Negação') }}"/>
                            <textarea class="form-input w-full" name="conditional_formula" id="conditional_formula" cols="30" rows="3">{{ $formula->conditional_formula }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="type_classification" value="{{ __('Tipo de Classificação') }}" required/>
                            <x-custom-select :options="$types" name="type_classification" id="type_classification" required :value="$formula->type_classification"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="months" value="{{ __('Meses') }}"/>
                            <x-custom-multi-select multiple :options="$months" name="months[]" id="months" :value="$monthsFormula"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Observações') }}" />
                            <textarea class="form-input w-full" name="obs" id="obs" cols="30" rows="10">{{ $formula->obs }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('formulas.scripts')

</x-app-layout>
