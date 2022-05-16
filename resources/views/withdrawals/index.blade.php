<x-app-layout>
    <div class="py-6 index-withdrawals">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Retiradas Gerenciais') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <div class="m-2 ">
                        <a class="btn-outline-info" href="{{ route('withdrawals.create') }}" >{{ __('Cadastrar') }}</a>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn-outline-danger delete_accounting_classifications" data-type="multiple">{{ __('Apagar') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full justify-end">
                    <form id="search_year_form" action="{{ route('withdrawals.index') }}" method="GET">
                        <div class="w-full md:w-auto px-2 mb-6 md:mb-0">
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="[2022 => 2022, 2021 => 2021, 2020 => 2020]" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : now()->year"/>
                        </div>
                    </form>
                </div>
                <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                    <div style="height: 1px;"></div>
                </div>
                <div class="flex mt-4 accounting-table">
                    <div class="view">
                        <div class="wrapper" id="scroll_bottom">
                            <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                @include('withdrawals.filter-result', ['accountingClassifications1' => $accountingClassifications1, 'ascending' => $ascending, 'orderBy' => $orderBy])
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-4 accounting-table">
                    <div class="flex items-center mx-4 my-2">
                        <h2>{{ __('Resultado do Exercício') }}</h2>
                        <button type="button" class="btn-transition-primary px-2" id="btn_withdrawal_add" title="Adicionar nova classificação">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                    <div id="scroll_top_2" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="view">
                        <div class="wrapper" id="scroll_bottom_2">
                            <table id="accounting_classifications_table2" class="table table-responsive md:table w-full">
                                @include('withdrawals.filter-result2', ['accountingClassifications2' => $accountingClassifications2, 'ascending' => $ascending, 'orderBy' => $orderBy])
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('withdrawals.create-modal')
    <x-spin-load />

    <x-modal title="{{ __('Excluir Retiradas Gerenciais') }}"
             msg="{{ __('Deseja realmente apagar esse Retiradas Gerenciais?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_classification_modal"
             method="DELETE"
             redirect-url="{{ route('withdrawals.index') }}"/>

    <script>
        window.addEventListener("load", function() {
            document.querySelector("#scroll_top div").style.width = document.querySelector("#accounting_classifications_table").clientWidth + 'px';
        });

        var wrapper1 = document.getElementById('scroll_top');
        var wrapper2 = document.getElementById('scroll_bottom');

        wrapper1.onscroll = function() {
            wrapper2.scrollLeft = wrapper1.scrollLeft;
        };

        wrapper2.onscroll = function() {
            wrapper1.scrollLeft = wrapper2.scrollLeft;
        };
    </script>

    <script>
        window.addEventListener("load", function() {
            document.querySelector("#scroll_top_2 div").style.width = document.querySelector("#accounting_classifications_table2").clientWidth + 'px';
        });

        var wrapper3 = document.getElementById('scroll_top_2');
        var wrapper4 = document.getElementById('scroll_bottom_2');

        wrapper3.onscroll = function() {
            wrapper4.scrollLeft = wrapper3.scrollLeft;
        };

        wrapper4.onscroll = function() {
            wrapper3.scrollLeft = wrapper4.scrollLeft;
        };
    </script>

    <script>
        document.getElementById("btn_withdrawal_add").addEventListener("click", function() {
            var modal = document.getElementById("withdrawal_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("withdrawal_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("withdrawal_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("withdrawal_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let url = "{!! route('withdrawals.store') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let month = document.querySelector("#withdrawal_modal #month").value;
            let year = document.querySelector("#year").value;
            let value = document.getElementById("value").value;
            let accountingClassification = document.querySelector("#withdrawal_modal #accounting_classification_id").value;

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
            data.append('value', value);
            data.append('accounting_classification_id', accountingClassification);

            ajax.send(data);

        });

    </script>

    <script>
        window.addEventListener("load", function() {
            document.getElementById("year").addEventListener("change", function() {
                document.getElementById("search_year_form").submit();
            });

            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('withdrawals.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var name = document.getElementById("name").value;
                var classification = document.getElementById("classification").value;
                var typeClassification = document.getElementById("type_classification").value;
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
                if(name) data.append('name', name);
                if(classification) data.append('classification', classification);
                if(typeClassification) data.append('type_classification', typeClassification);
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
                var url = "{!! route('withdrawals.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var name = document.getElementById("name").value;
                var classification = document.getElementById("classification").value;
                var typeClassification = document.getElementById("type_classification").value;
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
                if(name) data.append('name', name);
                if(classification) data.append('classification', classification);
                if(typeClassification) data.append('type_classification', typeClassification);
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
                        document.querySelectorAll('input:checked.withdrawals-url').forEach((item, index, arr) => {
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
