<x-app-layout>
    <div class="py-6 index-accounting-classifications">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Classificações Contábeis') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <div class="m-2 ">
                        <a class="btn-outline-info" href="{{ route('accounting-classifications.create') }}" >{{ __('Cadastrar') }}</a>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn-outline-danger delete_accounting_classifications" data-type="multiple">{{ __('Apagar') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="filter-container">
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/5 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="id">
                                {{ __('ID') }}
                            </label>
                            <x-jet-input id="id" class="form-control block w-full filter-field" type="text" name="id" :value="app('request')->input('id')" autofocus autocomplete="id" />
                        </div>
                        <div class="w-full md:w-1/5 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="classification">
                                {{ __('Classificação') }}
                            </label>
                            <x-jet-input id="classification" class="form-control block w-full filter-field" type="text" name="classification" :value="app('request')->input('classification')" autofocus autocomplete="classification" />
                        </div>
                        <div class="w-full md:w-1/5 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="level">
                                {{ __('Nível') }}
                            </label>
                            <x-jet-input id="level" class="form-control block w-full filter-field" type="text" name="level" :value="app('request')->input('level')" autofocus autocomplete="level" />
                        </div>
                        <div class="w-full md:w-1/5 px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                                {{ __('Descrição') }}
                            </label>
                            <x-jet-input id="name" class="form-control block w-full filter-field" type="text" name="name" :value="app('request')->input('name')" autofocus autocomplete="name" />
                        </div>
                    </div>
                </div>
                <div class="flex mt-4">
                    <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                        @include('accounting-classifications.filter-result', ['accounting-classifications' => $accountingClassifications, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
                <div class="flex mt-4 p-2" id="pagination">
                        {{ $accountingClassifications->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-modal title="{{ __('Excluir Classificações Contábeis') }}"
             msg="{{ __('Deseja realmente apagar esse Classificações Contábeis?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_classification_modal"
             method="DELETE"
             redirect-url="{{ route('accounting-classifications.index') }}"/>

    <script>
        window.addEventListener("load", function() {
            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('accounting-classifications.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var name = document.getElementById("name").value;
                var classification = document.getElementById("classification").value;
                var level = document.getElementById("level").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_classifications_table").innerHTML = resp.filter_result;
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
                if(name) data.append('name', name);
                if(classification) data.append('classification', classification);
                if(level) data.append('level', level);

                ajax.send(data);
            }

            var ascending = "{!! $ascending !!}";
            var orderBY = "{!! $orderBy !!}";

            var orderByCallback = function (event) {
                orderBY = this.dataset.name;
                ascending = this.dataset.ascending;
                var that = this;
                var ajax = new XMLHttpRequest();
                var url = "{!! route('accounting-classifications.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var name = document.getElementById("name").value;
                var classification = document.getElementById("classification").value;
                var level = document.getElementById("level").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_classifications_table").innerHTML = resp.filter_result;
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
                if(name) data.append('name', name);
                if(classification) data.append('classification', classification);
                if(level) data.append('level', level);

                ajax.send(data);
            }

            function eventsFilterCallback() {
                document.querySelectorAll('.filter-field').forEach(item => {
                    item.addEventListener('change', filterCallback, false);
                    item.addEventListener('keyup', filterCallback, false);
                });
                document.querySelectorAll("#accounting_classifications_table thead [data-name]").forEach(item => {
                    item.addEventListener("click", orderByCallback, false);
                });
            }

            function eventsDeleteCallback() {
                document.querySelectorAll('.delete_accounting_classifications').forEach(item => {
                item.addEventListener("click", function() {
                    if(this.dataset.type != 'multiple') {
                        var url = this.dataset.url;
                        var modal = document.getElementById("delete_accounting_classification_modal");
                        modal.dataset.url = url;
                        modal.classList.remove("hidden");
                        modal.classList.add("block");
                    }
                    else {
                        var urls = '';
                        document.querySelectorAll('input:checked.accounting-classifications-url').forEach((item, index, arr) => {
                            urls += item.value ;
                            if(index < (arr.length - 1)) {
                                urls += ',';
                            }
                        });

                        if(urls.length > 0) {
                            var modal = document.getElementById("delete_accounting_classification_modal");
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
