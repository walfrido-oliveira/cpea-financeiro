<x-app-layout>
    <div class="py-6 edit-employees">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('employees.update', ['employee' => $employee->id]) }}">
                @csrf
                @method("PUT")
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
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="employee_id" value="{{ __('Matrícula') }}" required/>
                            <x-jet-input id="employee_id" class="form-control block mt-1 w-full" type="text" :value="$employee->employee_id" name="employee_id" maxlength="255" required autofocus autocomplete="employee_id" placeholder="{{ __('Matrícula') }}"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="work_regime_id" value="{{ __('Regime') }}" required/>
                            <x-custom-select :options="$workRegimes" name="work_regime_id" id="work_regime_id" required :value="$employee->work_regime_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="admitted_at" value="{{ __('Data de Amissão') }}" required/>
                            <x-jet-input id="admitted_at" class="form-control block mt-1 w-full" type="date" :value="$employee->admitted_at->format('Y-m-d')" name="admitted_at" maxlength="255" required autofocus autocomplete="admitted_at" placeholder="{{ __('Data de Amissão') }}"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="working_day_id" value="{{ __('Jornada de Trabalho') }}" required/>
                            <x-custom-select class="mt-1"  :options="$workingDays" name="working_day_id" id="working_day_id" required :value="$employee->working_day_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Nome Completo') }}" required/>
                            <x-jet-input id="name" class="form-control block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" placeholder="{{ __('Nome Completo') }}" :value="$employee->name"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="manager_id" value="{{ __('Gestor Imediato') }}" required/>
                            <x-custom-select :options="$employees" name="manager_id" id="manager_id" required :value="$employee->manager_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="occupation_id" value="{{ __('Cargo') }}" required/>
                            <x-custom-select :options="$occupations" name="occupation_id" id="occupation_id" required :value="$employee->occupation_id"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="occupation_type_id" value="{{ __('Tipo') }}" required/>
                            <x-custom-select :options="$occupationTypes" name="occupation_type_id" id="occupation_type_id" required :value="$employee->occupation_type_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="direction_id" value="{{ __('Diretoria') }}" required/>
                            <x-custom-select :options="$directions" name="direction_id" id="direction_id" required :value="$employee->direction_id"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="department_id" value="{{ __('Departamento') }}" required/>
                            <x-custom-select :options="$departments" name="department_id" id="department_id" required :value="$employee->department_id"/>
                        </div>
                    </div>

                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="month_cost" value="{{ __('Custo Mês') }}" required/>
                            <x-jet-input id="month_cost" class="form-control block mt-1 w-full" type="number" name="month_cost" step="any" required autofocus autocomplete="month_cost" :value="$employee->month_cost"/>
                            <small><a href="#" class="show_logs" data-name="month_cost">Histórico de alterações</a></small>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="hour_cost" value="{{ __('Custo Hora') }}" required/>
                            <x-jet-input id="hour_cost" class="form-control block mt-1 w-full" type="number" name="hour_cost" step="any" required autofocus autocomplete="hour_cost" :value="$employee->hour_cost"/>
                            <small><a href="#" class="show_logs" data-name="hour_cost">Histórico de alterações</a></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="logs_container_modal">

    </div>

    <x-spin-load />

    <script>
        document.querySelectorAll(".show_logs").forEach(item => {
            item.addEventListener("click", function() {
                document.getElementById("spin_load").classList.remove("hidden");

                let name = item.dataset.name;
                let ajax = new XMLHttpRequest();
                let url = "{!! route('employees.log', ['employee_id' => $employee->id, 'name' => '#']) !!}".replace("#", name);
                let token = document.querySelector('meta[name="csrf-token"]').content;
                let method = 'POST';

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("logs_container_modal").classList.remove("hidden");
                        document.getElementById("spin_load").classList.add("hidden");
                        document.getElementById("logs_container_modal").innerHTML = resp.modal;
                        document.getElementById("logs_container_modal").addEventListener("click", function() {
                            document.getElementById("logs_container_modal").classList.add("hidden");
                        });
                    } else if(this.readyState == 4 && this.status != 200) {
                        document.getElementById("spin_load").classList.add("hidden");
                        toastr.error("{!! __('Um erro ocorreu ao solicitar a consulta') !!}");
                    }
                }

                var data = new FormData();
                data.append('_token', token);
                data.append('_method', method);

                ajax.send(data);

            });
        });
    </script>


</x-app-layout>
