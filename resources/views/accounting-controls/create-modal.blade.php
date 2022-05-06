<!-- Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden modal" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="import_modal" data-url="">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900 sm:ml-4 " id="modal-title">
                {{ __('Adicionar novo controle contábil') }}
              </h3>
              <div class="mt-2">
                <form method="POST" id="import_form" action="">
                    @csrf
                    @method("POST")

                    <div class="flex flex-wrap py-2 mt-4">
                        <div class="flex justify-center">
                            <div class="px-3 mb-6 md:mb-0 w-96">
                                <x-jet-label for="file" value="{{ __('Selecione uma rquivo') }}" required />
                                <input class="form-control
                                block
                                w-full
                                px-2
                                py-1
                                text-sm
                                font-normal
                                text-gray-700
                                bg-white bg-clip-padding
                                border border-solid border-gray-300
                                rounded
                                transition
                                ease-in-out
                                m-0
                                focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="file" type="file">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="month" value="{{ __('Mês') }}" required />
                            <x-custom-select no-filter class="mt-1" :options="$months" name="month" id="month" :value="app('request')->input('month')"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="year" value="{{ __('Ano') }}" required />
                            <x-jet-input id="year" class="form-control block mt-1 w-full" type="number" name="year" required autofocus autocomplete="year" :value="old('year')"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="obs" value="{{ __('Observações') }}" />
                            <textarea class="form-input w-full" name="obs" id="obs" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button type="button" id="import_confirm_modal" class="btn-confirm">
            {{ __('Confirmar') }}
          </button>
          <button type="button" id="import_cancel_modal" class="btn-cancel">
            {{ __('Cancelar') }}
          </button>
        </div>
      </div>
    </div>
  </div>
