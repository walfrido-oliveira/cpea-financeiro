<x-app-layout>
    <div class="py-6 index-accounting-configs">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Configurações de Retirada') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <div class="m-2 ">
                        <button id="btn_duplicate" type="button" class="btn-outline-info">{{ __('+ Duplicar') }}</button>
                    </div>
                    <div class="m-2 ">
                        <button id="btn_add_classification" type="button" class="btn-outline-info">{{ __('+ Classificação') }}</button>
                    </div>
                    <div class="m-2 ">
                        <button id="btn_add_formula" type="button" class="btn-outline-info">{{ __('+ Formula') }}</button>
                    </div>
                    <div class="m-2 ">
                        <button id="btn_add_config" type="button" class="btn-outline-info">{{ __('+ Mês/Ano') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="flex mt-4">
                    <table id="accounting_configs_table" class="table table-responsive md:table w-full">
                        @include('accounting-configs.filter-result', ['accounting-configs' => $accountingConfigs, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('accounting-configs.create-modal')

    <x-spin-load />

    <x-modal title="{{ __('Excluir Classificações Contábeis') }}"
             msg="{{ __('Deseja realmente apagar esse Classificações Contábeis?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_config_modal"
             method="DELETE"
             redirect-url="{{ route('accounting-configs.index') }}"/>

    <script>
        function showPoint() {
            document.querySelectorAll(".show-accounting-config").forEach(item => {
                item.addEventListener("click", function() {
                    item.childNodes[1].classList.toggle("hidden");
                    item.childNodes[3].classList.toggle("hidden");

                    if(item.dataset.type == 'year') {
                        document.querySelectorAll(`tr[data-year='${item.dataset.year}'][data-type='month']`).forEach(item2 => {
                            item2.classList.toggle("active");

                            if(!item2.classList.contains('active')) {
                                document.querySelectorAll(`tr[data-type='classification'][data-year='${item2.dataset.year}'][data-month='${item2.dataset.month}'].active`).forEach(item3 => {
                                    item3.classList.toggle("active");
                                    item2.querySelector("button").childNodes[1].classList.toggle("hidden");
                                    item2.querySelector("button").childNodes[3].classList.toggle("hidden");
                                });
                            }
                        });
                    }

                    if(item.dataset.type == 'month') {
                        document.querySelectorAll(`tr[data-type='classification'][data-year='${item.dataset.year}'][data-month='${item.dataset.month}']`).forEach(item2 => {
                            item2.classList.toggle("active");
                        });
                    }

                    if(item.dataset.type == 'classification') {
                        document.querySelectorAll(".point-items-" + item.dataset.point).forEach(item => {
                            if(item.dataset.type == 'item') item.classList.toggle("active");
                        });
                    }

                });
            });
        }

        showPoint();
    </script>

    <script>
        document.getElementById("btn_add_config").addEventListener("click", function() {
            var modal = document.getElementById("accounting_config_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

    document.getElementById("accounting_config_cancel_modal").addEventListener("click", function(e) {
        var modal = document.getElementById("accounting_config_modal");
        modal.classList.add("hidden");
    });

    document.getElementById("accounting_config_confirm_modal").addEventListener("click", function(e) {
        document.getElementById("spin_load").classList.remove("hidden");

        let ajax = new XMLHttpRequest();
        let url = "{!! route('accounting-configs.store') !!}";
        let token = document.querySelector('meta[name="csrf-token"]').content;
        let method = 'POST';
        let month = document.querySelector("#accounting_config_modal #month").value;
        let year = document.querySelector("#year").value;

        ajax.open(method, url);

        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var resp = JSON.parse(ajax.response);
                document.getElementById("spin_load").classList.add("hidden");
                toastr.success(resp.message);

                location.reload();
            } else if(this.readyState == 4 && this.status != 200) {
                document.getElementById("spin_load").classList.add("hidden");
                toastr.error("{!! __('Um erro ocorreu ao solicitar a consulta') !!}");
            }
        }

        var data = new FormData();
        data.append('_token', token);
        data.append('_method', method);
        data.append('month', month);
        data.append('year', year);

        ajax.send(data);

    });

</script>

    <script>
        window.addEventListener("load", function() {
            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('accounting-configs.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var month = document.getElementById("month").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_configs_table").innerHTML = resp.filter_result;
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
                var url = "{!! route('accounting-configs.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var month = document.getElementById("month").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("accounting_configs_table").innerHTML = resp.filter_result;
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
                document.querySelectorAll("#accounting_configs_table thead [data-name]").forEach(item => {
                    item.addEventListener("click", orderByCallback, false);
                });
            }

            function eventsDeleteCallback() {
                document.querySelectorAll('.delete-accounting-configs').forEach(item => {
                item.addEventListener("click", function() {
                    if(this.dataset.type != 'multiple') {
                        var url = this.dataset.url;
                        var modal = document.getElementById("delete_accounting_config_modal");
                        modal.dataset.url = url;
                        modal.classList.remove("hidden");
                        modal.classList.add("block");
                    }
                    else {
                        var urls = '';
                        document.querySelectorAll('input:checked.accounting-configs-url').forEach((item, index, arr) => {
                            urls += item.value ;
                            if(index < (arr.length - 1)) {
                                urls += ',';
                            }
                        });

                        if(urls.length > 0) {
                            var modal = document.getElementById("delete_accounting_config_modal");
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
