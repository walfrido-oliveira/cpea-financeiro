<!-- Modal -->
<div class="modal fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="check_points_modal" data-url="">
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
                {{ now()->translatedFormat('l, d M Y') }}
              </h3>
              <h1 class="text-lg leading-6 font-medium text-gray-900 sm:ml-4 " id="modal-title">
                {{ now()->translatedFormat('H:i') }}
              </h1>
              <div class="mt-2">
                <form method="POST" id="import_form" action="">
                    @csrf
                    @method("POST")
                    <div class="flex -mx-3 mb-1 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                            <label for="activity_id_type" class="flex items-center">
                                <input id="activity_id_type" type="radio" class="form-checkbox" name="type" value="activity_id" checked>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Atividade') }}</span>
                            </label>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 flex">
                            <label for="project_id_type" class="flex items-center">
                                <input id="project_id_type" type="radio" class="form-checkbox" name="type" value="project_id">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Projeto') }}</span>
                            </label>
                        </div>
                    </div>
                    </div>

                    <div class="flex -mx-3 mb-1 p-3 md:flex-row flex-col w-full" id="activity_id_container">
                        <div class="w-full md:w-full px-2 md:mb-0">
                            <x-jet-label for="activity_id" value="{{ __('Cód. Atividade') }}" required />
                            <x-custom-select class="mt-1" :options="$activities" name="activity_id" id="activity_id" value="" required/>
                        </div>
                    </div>
                    <div class="flex -mx-3 mb-1 p-3 md:flex-row flex-col w-full" id="project_id_container" style="display:none;">
                        <div class="w-full md:w-full px-2 md:mb-0">
                            <x-jet-label for="project_id" value="{{ __('Cód. Projeto') }}" required />
                            <x-jet-input id="project_id" class="form-control block mt-1 w-full" type="text" name="project_id" maxlength="191" value="" required  placeholder="{{ __('Projeto') }}"/>
                        </div>
                    </div>
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="start" value="{{ __('Início') }}" required />
                            <x-jet-input id="start" class="form-control block mt-1 w-full" type="date" name="start" value="" required  placeholder="{{ __('Início') }}"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="end" value="{{ __('Fim') }}" required />
                            <x-jet-input id="end" class="form-control block mt-1 w-full" type="date" name="end" value="" required  placeholder="{{ __('Fim') }}"/>
                        </div>
                    </div>
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="description" value="{{ __('Descrição') }}" />
                            <textarea class="form-input w-full" name="description" id="description" cols="10" rows="5" ></textarea>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button type="button" id="check_points_confirm_modal" class="btn-confirm">
            {{ __('Confirmar') }}
          </button>
          <button type="button" id="check_points_cancel_modal" class="btn-cancel">
            {{ __('Cancelar') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("activity_id_type").addEventListener("change", function() {
        document.getElementById("activity_id_container").style.display = this.checked ? 'flex' : 'none';
        document.getElementById("project_id_container").style.display = this.checked ? 'none' : 'flex';
    });

    document.getElementById("project_id_type").addEventListener("change", function() {
        document.getElementById("activity_id_container").style.display = this.checked ? 'none' : 'flex';
        document.getElementById("project_id_container").style.display = this.checked ? 'flex' : 'none';
    });
  </script>
