<x-app-layout>
    <div class="py-6 create-employees">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                @method("POST")
                <div class="flex md:flex-row flex-col">
                    <div class="w-full flex items-center">
                        <h1>{{ __('Colaborador') }}</h1>
                    </div>
                    <div class="w-full flex justify-end">
                        <div class="m-2 ">
                            <button type="submit" class="btn-outline-success">{{ __('Confirmar') }}</button>
                        </div>
                        <div class="m-2">
                            <a href="{{ route('employees.index')}}" class="btn-outline-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:flex-row flex-col">
                    <x-jet-validation-errors class="mb-4" />
                </div>

                <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <x-jet-label for="employee_id" value="{{ __('Matrícula') }}" required/>
                            <x-jet-input id="employee_id" class="form-control block mt-1 w-full" type="text" name="employee_id" maxlength="255" required autofocus autocomplete="employee_id" placeholder="{{ __('Matrícula') }}"/>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <x-jet-label for="admitted_at" value="{{ __('Data de Amissão') }}" required/>
                            <x-jet-input id="admitted_at" class="form-control block mt-1 w-full" type="date" name="admitted_at" required autofocus autocomplete="admitted_at" placeholder="{{ __('Data de Amissão') }}"/>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <x-jet-label for="working_day_id" value="{{ __('Jornada de Trabalho') }}" required/>
                            <x-custom-select class="mt-1" :options="$workingDays" name="working_day_id" id="working_day_id" required :value="old('working_day_id')"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="user_id" value="{{ __('Usuário') }}" required/>
                            <x-custom-select :options="$users" name="user_id" id="user_id" required :value="old('Usuário')"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="manager_id" value="{{ __('Gestor Imediato') }}" required/>
                            <x-custom-select :options="$users" name="manager_id" id="manager_id" required :value="old('manager_id')"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="occupation_id" value="{{ __('Cargo') }}" required/>
                            <x-custom-select :options="$occupations" name="occupation_id" id="occupation_id" required :value="old('occupation_id')"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="occupation_type" value="{{ __('Tipo') }}" required/>
                            <x-custom-select :options="$occupationTypes" name="occupation_type" id="occupation_type" required :value="old('occupation_type')"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="direction_id" value="{{ __('Diretoria') }}" required/>
                            <x-custom-select :options="$directions" name="direction_id" id="direction_id" required :value="old('direction_id')"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="department_id" value="{{ __('Departamento') }}" required/>
                            <x-custom-select :options="$departments" name="department_id" id="department_id" required :value="old('department_id')"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="hour_cost" value="{{ __('Custo Hora') }}" required/>
                            <x-jet-input id="hour_cost" class="form-control block mt-1 w-full" type="number" name="hour_cost" step="any" required autofocus autocomplete="hour_cost"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
