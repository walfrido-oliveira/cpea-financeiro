<x-app-layout>
    <div class="py-6 index-accounting-controls">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Lista de Controles Contabeis') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <form action="{{ route('accounting-controls.import') }}" method="POST">
                        @csrf
                        @method("POST")
                        <div class="m-2 ">
                            <button id="import" type="button" class="btn-outline-info">{{ __('Importar') }}</button>
                        </div>
                    </form>
                    <div class="m-2">
                        <button type="button" class="btn-outline-danger delete-accounting-controls" data-type="multiple">{{ __('Apagar') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="filter-container">
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="id">
                                {{ __('ID') }}
                            </label>
                            <x-jet-input id="id" class="form-control block w-full filter-field" type="text" name="id" :value="app('request')->input('id')" autofocus autocomplete="id" />
                        </div>
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="month">
                                {{ __('Mês') }}
                            </label>
                            <x-jet-input id="month" class="form-control block w-full filter-field" type="text" name="month" :value="app('request')->input('month')" autofocus autocomplete="month" />
                        </div>
                    </div>
                </div>
                <div class="flex mt-4">
                    <table id="accounting_controls_table" class="table table-responsive md:table w-full">
                        @include('accounting-controls.filter-result', ['accounting-controls' => $accountingControls, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
                <div class="flex mt-4 p-2" id="pagination">
                        {{ $accountingControls->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('accounting-controls.create-modal')
    <x-spin-load />

    <x-modal title="{{ __('Excluir Classificações Contábeis') }}"
             msg="{{ __('Deseja realmente apagar esse Classificações Contábeis?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_control_modal"
             method="DELETE"
             redirect-url="{{ route('accounting-controls.index') }}"/>

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
            let url = "{!! route('accounting-controls.import') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let file = document.getElementById("file")
            let files = file.files;
            let month = document.querySelector("#import_modal #month").value;
            let year = document.querySelector("#import_modal #year").value;
            let obs = document.getElementById("obs").value;

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
            data.append('_method', method);
            data.append('file', files[0]);
            data.append('month', month);
            data.append('year', year);
            data.append('obs', obs);

            ajax.send(data);

        });

    </script>

    <script>
        window.addEventListener("load", function() {
            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('accounting-controls.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var month = document.getElementById("month").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_controls_table").innerHTML = resp.filter_result;
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
                if(id) data.append('id', id);
                if(month) data.append('month', month);

                ajax.send(data);
            }

            var ascending = "{!! $ascending !!}";
            var orderBY = "{!! $orderBy !!}";

            var orderByCallback = function (event) {
                orderBY = this.dataset.name;
                ascending = this.dataset.ascending;
                var that = this;
                var ajax = new XMLHttpRequest();
                var url = "{!! route('accounting-controls.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var month = document.getElementById("month").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_controls_table").innerHTML = resp.filter_result;
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
                if(id) data.append('id', id);
                if(month) data.append('month', month);

                ajax.send(data);
            }

            function eventsFilterCallback() {
                document.querySelectorAll('.filter-field').forEach(item => {
                    item.addEventListener('change', filterCallback, false);
                    item.addEventListener('keyup', filterCallback, false);
                });
                document.querySelectorAll("#accounting_controls_table thead [data-name]").forEach(item => {
                    item.addEventListener("click", orderByCallback, false);
                });
            }

            function eventsDeleteCallback() {
                document.querySelectorAll('.delete-accounting-controls').forEach(item => {
                item.addEventListener("click", function() {
                    if(this.dataset.type != 'multiple') {
                        var url = this.dataset.url;
                        var modal = document.getElementById("delete_accounting_control_modal");
                        modal.dataset.url = url;
                        modal.classList.remove("hidden");
                        modal.classList.add("block");
                    }
                    else {
                        var urls = '';
                        document.querySelectorAll('input:checked.accounting-controls-url').forEach((item, index, arr) => {
                            urls += item.value ;
                            if(index < (arr.length - 1)) {
                                urls += ',';
                            }
                        });

                        if(urls.length > 0) {
                            var modal = document.getElementById("delete_accounting_control_modal");
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
