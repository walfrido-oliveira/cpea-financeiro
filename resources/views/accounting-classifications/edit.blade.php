<x-app-layout>
    <div class="py-6 edit-accounting-classifications">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('accounting-classifications.update', ['accounting_classification' => $accountingClassification->id]) }}">
                @csrf
                @method("PUT")
                <div class="flex md:flex-row flex-col">
                    <div class="w-full flex items-center">
                        <h1>{{ __('Classificações Contábeis') }}</h1>
                    </div>
                    <div class="w-full flex justify-end">
                        <div class="m-2 ">
                            <button type="submit" class="btn-outline-success">{{ __('Confirmar') }}</button>
                        </div>
                        <div class="m-2">
                            <a href="{{ route('accounting-classifications.index')}}" class="btn-outline-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:flex-row flex-col">
                    <x-jet-validation-errors class="mb-4" />
                </div>

                <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="classification" value="{{ __('Classificação') }}" required/>
                            <x-jet-input id="classification" class="form-control block mt-1 w-full" type="text" name="classification" maxlength="255" required autofocus autocomplete="classification" placeholder="{{ __('Classificação') }}" :value="$accountingClassification->classification" />
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="type_classification" value="{{ __('Tipo de Classificação') }}" required/>
                            <x-custom-select :options="$types" name="type_classification" id="type_classification" required :value="$accountingClassification->type_classification"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="unity" value="{{ __('Unidade') }}" required/>
                            <x-custom-select :options="$unitys" name="unity" id="unity" required :value="$accountingClassification->unity"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Descrição') }}" required/>
                            <x-jet-input id="name" class="form-control block mt-1 w-full" type="text" name="name" maxlength="255" required autofocus autocomplete="name" placeholder="{{ __('Descrição') }}" :value="$accountingClassification->name"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="accounting_classification_id" value="{{ __('Classificação Antecessora') }}" />
                            <x-custom-select no-filter :options="$accountingClassifications" name="accounting_classification_id" id="accounting_classification_id" :value="$accountingClassification->accounting_classification_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="level" value="{{ __('Nível de Recuo (1 à 10)') }}" required/>
                            <x-jet-input id="level" class="form-control block mt-1 w-full" type="number" name="level" min="1" max="10" required autofocus autocomplete="level" placeholder="{{ __('Nível') }}" :value="$accountingClassification->level"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="order" value="{{ __('Ordem') }}" required/>
                            <x-jet-input id="order" class="form-control block mt-1 w-full" type="text" name="order" maxlength="45" required autofocus autocomplete="order" placeholder="{{ __('Ordem') }}" :value="$accountingClassification->order"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label for="featured" class="flex items-center">
                                <input id="featured" type="checkbox" class="form-checkbox" name="featured" @if ($accountingClassification->featured)
                                    checked
                                @endif>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Destaque') }}</span>
                            </label>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label for="visible" class="flex items-center">
                                <input id="visible" type="checkbox" class="form-checkbox" name="visible" @if ($accountingClassification->visible)
                                checked
                            @endif>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Visível') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4 items-center">
                        <div class="w-full md:w-1/12 px-3 mb-6 md:mb-0">
                            <label for="color" class="flex items-center">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Cor') }}</span>
                                <x-jet-input id="color" class="form-control block mt-1 w-full" type="color" name="color"  placeholder="{{ __('Cor') }}" :value="$accountingClassification->color" />
                            </label>
                        </div>
                        <div class="w-full md:w-auto px-3 mb-6 md:mb-0">
                            <label for="bolder" class="flex items-center">
                                <input id="bolder" type="checkbox" class="form-checkbox" name="bolder" @if ($accountingClassification->bolder)
                                checked
                            @endif>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Negrito') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Observações') }}" />
                            <textarea class="form-input w-full" name="obs" id="obs" cols="30" rows="10">{{ $accountingClassification->obs }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
