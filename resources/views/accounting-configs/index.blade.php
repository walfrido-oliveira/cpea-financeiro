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
                <div class="w-full">
                    <form id="search_year_form" class="flex -mx-3 mb-6 p-3 md:flex-column flex-row w-full justify-end" action="{{ route('accounting-configs.index') }}" method="GET">
                        <div class="w-full md:w-auto px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="year">
                                {{ __('Ano') }}
                            </label>
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="$years" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : $maxYear"/>
                        </div>
                        <div class="w-full md:w-auto px-2 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="month">
                                {{ __('Mês') }}
                            </label>
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="months()" name="month" id="month"
                                :value="app('request')->has('month') ? app('request')->input('month') : $maxMonth"/>
                        </div>
                    </form>
                </div>
                <div class="flex mt-4">
                    <table id="accounting_configs_table" class="table table-responsive md:table w-full">
                        @include('accounting-configs.filter-result', ['accounting-configs' => $accountingConfigs, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('accounting-configs.create-modal')
    @include('accounting-configs.duplicate-modal')
    @include('accounting-configs.add-classification-modal')
    @include('accounting-configs.add-formula-modal')

    <x-spin-load />

    <x-modal title="{{ __('Excluir Configuração') }}"
             msg="{{ __('Deseja realmente apagar essa configuração?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_config_modal"
             method="DELETE"
             redirect-url="{{ route('accounting-configs.index') }}"/>

    <script>
        document.getElementById("year").addEventListener("change", function() {
            document.getElementById("search_year_form").submit();
        });
        document.getElementById("month").addEventListener("change", function() {
            document.getElementById("search_year_form").submit();
        });

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

                            if(!item2.classList.contains('active')) {
                                document.querySelectorAll(`tr[data-type='classification'][data-year='${item2.dataset.year}'][data-month='${item2.dataset.month}'] button svg.minus:not(.hidden)`).forEach(item5 => {
                                    item5.classList.toggle("hidden");
                                });

                                document.querySelectorAll(`tr[data-type='classification'][data-year='${item2.dataset.year}'][data-month='${item2.dataset.month}'] button svg.plus.hidden`).forEach(item6 => {
                                    item6.classList.toggle("hidden");
                                });

                                document.querySelectorAll(`tr[data-type='item-classification'][data-year='${item2.dataset.year}'][data-month='${item2.dataset.month}'].active`).forEach(item3 => {
                                    item3.classList.toggle("active");
                                });

                                document.querySelectorAll(`tr[data-type='item-formula'][data-year='${item2.dataset.year}'][data-month='${item2.dataset.month}'].active`).forEach(item3 => {
                                    item3.classList.toggle("active");
                                });
                            }
                        });
                    }

                    if(item.dataset.type == 'classification') {
                        document.querySelectorAll(`tr[data-type='item-classification'][data-year='${item.dataset.year}'][data-month='${item.dataset.month}'][data-classification='${item.dataset.classification}']`).forEach(item => {
                            if(item.dataset.type == 'item-classification') item.classList.toggle("active");
                        });
                    }

                    if(item.dataset.type == 'formula') {
                        document.querySelectorAll(`tr[data-type='item-formula'][data-year='${item.dataset.year}'][data-month='${item.dataset.month}']`).forEach(item => {
                            if(item.dataset.type == 'item-formula') item.classList.toggle("active");
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

        document.getElementById("btn_duplicate").addEventListener("click", function() {
            var modal = document.getElementById("accounting_config_duplicate_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("btn_add_classification").addEventListener("click", function() {
            var modal = document.getElementById("add_classification_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("btn_add_formula").addEventListener("click", function() {
            var modal = document.getElementById("add_formula_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("accounting_config_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("accounting_config_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("accounting_config_duplicate_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("accounting_config_duplicate_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("add_classification_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("add_classification_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("add_formula_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("add_formula_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("all_accounting_classification").addEventListener("change", function(e) {
            if(e.currentTarget.checked) {
                document.querySelectorAll("#accounting_classification_type option").forEach(item => {
                   item.selected = true;
                });
            } else {
                document.querySelectorAll("#accounting_classification_type option").forEach(item => {
                   item.selected = false;
                });
            }
            window.customSelectArray['accounting_classification_type'].update();
        });

        document.getElementById("all_formulas").addEventListener("change", function(e){
            setCheckboxValue(e);
        });

        document.getElementById("retiradas_gerenciais").addEventListener("change", function(e){
            setCheckboxValue(e);
        });

        document.getElementById("resultado_exercicio").addEventListener("change", function(e){
            setCheckboxValue(e);
        });

        document.getElementById("dre").addEventListener("change", function(e){
            setCheckboxValue(e);
        });

        document.getElementById("dre_ajustavel").addEventListener("change", function(e){
            setCheckboxValue(e);
        });

        document.getElementById("formula").addEventListener("change", function(e){
            setCheckboxValue(e);
        });


        function setCheckboxValue(e) {
            e.currentTarget.value = e.currentTarget.checked;
        }

        document.getElementById("accounting_config_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let url = "{!! route('accounting-configs.store') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let month = document.querySelector("#accounting_config_modal #month").value;
            let year = document.querySelector("#accounting_config_modal #year").value;

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

        document.getElementById("accounting_config_duplicate_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let url = "{!! route('accounting-configs.duplicate') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let month = document.querySelector("#accounting_config_duplicate_modal #month").value;
            let month_ref = document.querySelector("#accounting_config_duplicate_modal #month_ref").value;
            let year = document.querySelector("#accounting_config_duplicate_modal #year").value;
            let year_ref = document.querySelector("#accounting_config_duplicate_modal #year_ref").value;
            let retiradas_gerenciais = document.querySelector("#accounting_config_duplicate_modal #retiradas_gerenciais").value;
            let resultado_exercicio = document.querySelector("#accounting_config_duplicate_modal #resultado_exercicio").value;
            let dre = document.querySelector("#accounting_config_duplicate_modal #dre").value;
            let dre_ajustavel = document.querySelector("#accounting_config_duplicate_modal #dre_ajustavel").value;
            let formula = document.querySelector("#accounting_config_duplicate_modal #formula").value;

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
            data.append('month_ref', month_ref);
            data.append('year_ref', year_ref);
            data.append('retiradas_gerenciais', retiradas_gerenciais);
            data.append('resultado_exercicio', resultado_exercicio);
            data.append('dre', dre);
            data.append('dre_ajustavel', dre_ajustavel);
            data.append('formula', formula);
            ajax.send(data);

        });

        document.getElementById("add_classification_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let month = document.querySelector("#add_classification_modal #month").value;
            let year = document.querySelector("#add_classification_modal #year").value;
            let accounting_classification_id = document.querySelector("#add_classification_modal #accounting_classification_id").value;
            let url = "{!! route('accounting-configs.add-Classification', ['month' => '#1', 'year' => '#2']) !!}".replace('#1', month).replace('#2', year);
            let accounting_classification_type = Array.from(document.getElementById("accounting_classification_type").options).filter(o => (o.selected && o.text != '')).map(o => o.value);

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
            data.append('accounting_classification_id', accounting_classification_id);
            data.append('accounting_classification_type', accounting_classification_type);

            ajax.send(data);

        });

        document.getElementById("add_formula_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let month = document.querySelector("#add_formula_modal #month").value;
            let year = document.querySelector("#add_formula_modal #year").value;
            let formula_id = document.querySelector("#add_formula_modal #formula_id").value;
            let all_formulas = document.querySelector("#add_formula_modal #all_formulas").value;
            let url = "{!! route('accounting-configs.add-formula', ['month' => '#1', 'year' => '#2']) !!}".replace('#1', month).replace('#2', year);

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
            data.append('formula_id', formula_id);
            data.append('all_formulas', all_formulas);

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
