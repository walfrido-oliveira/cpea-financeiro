<x-app-layout>
    <div class="py-6 index-employees">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Colaborador') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <form action="{{ route('accounting-controls.import') }}" method="POST">
                        @csrf
                        @method("POST")
                        <div class="m-2 ">
                            <button id="import" type="button" class="btn-outline-info">{{ __('Importar') }}</button>
                        </div>
                    </form>
                    <div class="m-2 ">
                        <a class="btn-outline-info" href="{{ route('employees.create') }}" >{{ __('Cadastrar') }}</a>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn-outline-danger delete-employees" data-type="multiple">{{ __('Apagar') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="filter-container">
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="employee_id">
                                {{ __('Matrícula') }}
                            </label>
                            <x-jet-input id="employee_id" class="form-control block w-full filter-field" type="text" name="employee_id" :value="app('request')->input('employee_id')" autofocus autocomplete="employee_id" />
                        </div>
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                                {{ __('Colaborador') }}
                            </label>
                            <x-jet-input id="name" class="form-control block w-full filter-field" type="text" name="name" :value="app('request')->input('name')" autofocus autocomplete="name" />
                        </div>
                    </div>
                </div>
                <div class="flex mt-4">
                    <table id="employees_table" class="table table-responsive md:table w-full">
                        @include('employees.filter-result', ['employees' => $employees, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
                <div class="flex mt-4 p-2" id="pagination">
                        {{ $employees->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('employees.create-modal')

    <x-spin-load />

    <x-modal title="{{ __('Excluir Colaborador') }}"
             msg="{{ __('Deseja realmente apagar esse Colaborador?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_employee_modal"
             method="DELETE"
             redirect-url="{{ route('employees.index') }}"/>

    <script>
        document.getElementById("import").addEventListener("click", function() {
            var modal = document.getElementById("import_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("import_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("import_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("import_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let url = "{!! route('employees.import') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let file = document.getElementById("file")
            let files = file.files;

            ajax.open(method, url);

            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var resp = JSON.parse(ajax.response);
                    document.getElementById("spin_load").classList.add("hidden");
                    toastr.success(resp.message);
                    file.value='';

                    location.reload();
                } else if(this.readyState == 4 && this.status != 200) {
                    document.getElementById("spin_load").classList.add("hidden");
                    toastr.error("{!! __('Um erro ocorreu ao solicitar a consulta') !!}");
                    file.value = '';
                }
            }

            var data = new FormData();
            data.append('_token', token);
            data.append('_method', method);
            data.append('file', files[0]);

            ajax.send(data);

        });

        window.addEventListener("load", function() {
            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('employees.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var employee_id = document.getElementById("employee_id").value;
                var name = document.getElementById("name").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("employees_table").innerHTML = resp.filter_result;
                        document.getElementById("pagination").innerHTML = resp.pagination;
                        eventsFilterCallback();
                        eventsDeleteCallback();
                    } else if(this.readyState == 4 && this.status != 200) {
                        toastr.error("{!! __('Um erro ocorreu ao gerar a consulta') !!}");
                        eventsFilterCallback();
                        eventsDeleteCallback();
                    }
                }

                var data = new FormData();
                data.append('_token', token);
                data.append('_method', method);
                data.append('paginate_per_page', paginationPerPage);
                data.append('ascending', ascending);
                data.append('order_by', orderBY);
                if(employee_id) data.append('employee_id', employee_id);
                if(name) data.append('name', name);

                ajax.send(data);
            }

            var ascending = "{!! $ascending !!}";
            var orderBY = "{!! $orderBy !!}";

            var orderByCallback = function (event) {
                orderBY = this.dataset.name;
                ascending = this.dataset.ascending;
                var that = this;
                var ajax = new XMLHttpRequest();
                var url = "{!! route('employees.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var employee_id = document.getElementById("employee_id").value;
                var name = document.getElementById("name").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("employees_table").innerHTML = resp.filter_result;
                        document.getElementById("pagination").innerHTML = resp.pagination;
                        that.dataset.ascending = that.dataset.ascending == 'asc' ? that.dataset.ascending = 'desc' : that.dataset.ascending = 'asc';
                        eventsFilterCallback();
                        eventsDeleteCallback();
                    } else if(this.readyState == 4 && this.status != 200) {
                        toastr.error("{!! __('Um erro ocorreu ao gerar a consulta') !!}");
                        eventsFilterCallback();
                        eventsDeleteCallback();
                    }
                }

                var data = new FormData();
                data.append('_token', token);
                data.append('_method', method);
                data.append('paginate_per_page', paginationPerPage);
                data.append('ascending', ascending);
                data.append('order_by', orderBY);
                if(employee_id) data.append('employee_id', employee_id);
                if(name) data.append('name', name);

                ajax.send(data);
            }

            function eventsFilterCallback() {
                document.querySelectorAll('.filter-field').forEach(item => {
                    item.addEventListener('change', filterCallback, false);
                    item.addEventListener('keyup', filterCallback, false);
                });
                document.querySelectorAll("#employees_table thead [data-name]").forEach(item => {
                    item.addEventListener("click", orderByCallback, false);
                });
            }

            function eventsDeleteCallback() {
                document.querySelectorAll('.delete-employees').forEach(item => {
                item.addEventListener("click", function() {
                    if(this.dataset.type != 'multiple') {
                        var url = this.dataset.url;
                        var modal = document.getElementById("delete_employee_modal");
                        modal.dataset.url = url;
                        modal.classList.remove("hidden");
                        modal.classList.add("block");
                    }
                    else {
                        var urls = '';
                        document.querySelectorAll('input:checked.employees-url').forEach((item, index, arr) => {
                            urls += item.value ;
                            if(index < (arr.length - 1)) {
                                urls += ',';
                            }
                        });

                        if(urls.length > 0) {
                            var modal = document.getElementById("delete_employee_modal");
                            modal.dataset.url = urls;
                            modal.classList.remove("hidden");
                            modal.classList.add("block");
                        }
                    }
                })
            });
            }

            eventsDeleteCallback();
            eventsFilterCallback();
        });
    </script>

</x-app-layout>
