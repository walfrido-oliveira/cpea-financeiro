<!-- Modal -->
<div class="modal fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="accounting_config_duplicate_modal" data-url="">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" style="height: 600px;">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900 sm:ml-4 " id="modal-title">
                {{ __('Duplicar Mês/Ano') }}
              </h3>
              <div class="mt-2">
                <form method="POST" id="accounting_config_duplicate_form" action="">
                    @csrf
                    @method("POST")
                    <div class="w-full">
                        <h4>Referência</h4>
                        <div class="flex -mx-3 mb-6 p-0 md:flex-row flex-col w-full">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-jet-label for="month_ref" value="{{ __('Mês') }}" required />
                                <x-custom-select no-filter class="mt-1" :options="$months" name="month_ref" id="month_ref" :value="app('request')->input('month_ref')"/>
                            </div>
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-jet-label for="year_ref" value="{{ __('Ano') }}" required />
                                <x-jet-input id="year_ref" class="form-control block mt-1 w-full" type="number" name="year_ref" step="1" required autofocus autocomplete="year_ref" :value="old('year_ref')"/>
                            </div>
                        </div>
                        <div class="flex -mx-3 mb-6 p-0 md:flex-row flex-col w-full flex-wrap">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                                <label for="retiradas_gerenciais" class="flex items-center">
                                    <input id="retiradas_gerenciais" type="checkbox" class="form-checkbox" name="retiradas_gerenciais" value="false">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Retiradas Gerenciais') }}</span>
                                </label>
                            </div>
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                                <label for="resultado_exercicio" class="flex items-center">
                                    <input id="resultado_exercicio" type="checkbox" class="form-checkbox" name="resultado_exercicio" value="false">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Resultado do Exercício') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex -mx-3 mb-6 p-0 md:flex-row flex-col w-full flex-wrap">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                                <label for="dre" class="flex items-center">
                                    <input id="dre" type="checkbox" class="form-checkbox" name="dre" value="false">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('DRE') }}</span>
                                </label>
                            </div>
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                                <label for="dre_ajustavel" class="flex items-center">
                                    <input id="dre_ajustavel" type="checkbox" class="form-checkbox" name="dre_ajustavel" value="false">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('DRE Ajustável') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex -mx-3 mb-6 p-0 md:flex-row flex-col w-full flex-wrap">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                                <label for="formula" class="flex items-center">
                                    <input id="formula" type="checkbox" class="form-checkbox" name="formula" value="false">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Formulas') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <h4>Novo Valor</h4>
                        <div class="flex -mx-3 mb-6 p-0 md:flex-row flex-col w-full">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-jet-label for="month" value="{{ __('Mês') }}" required />
                                <x-custom-select no-filter class="mt-1" :options="$months" name="month" id="month" :value="app('request')->input('month')"/>
                            </div>
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <x-jet-label for="year" value="{{ __('Ano') }}" required />
                                <x-jet-input id="year" class="form-control block mt-1 w-full" type="number" name="year" step=".01" required autofocus autocomplete="year" :value="old('year')"/>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button type="button" id="accounting_config_duplicate_confirm_modal" class="btn-confirm">
            {{ __('Confirmar') }}
          </button>
          <button type="button" id="accounting_config_duplicate_cancel_modal" class="btn-cancel">
            {{ __('Cancelar') }}
          </button>
        </div>
      </div>
    </div>
  </div>
