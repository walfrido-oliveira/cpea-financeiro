<x-app-layout>
    <div class="py-6 index-check-points">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Gestão de Pontos') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <div class="m-2 ">
                        <a class="btn-outline-info" href="#" id="add_check_point">{{ __('Adicionar Registro') }}</a>
                    </div>
                    <div class="m-2">
                        <a class="btn-outline-info" href="{{ route('check-points.create') }}" >{{ __('Abrir Ponto') }}</a>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="filter-container">
                    <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full">
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <x-jet-label for="id" value="{{ __('Atividade/Projeto') }}" />
                            <x-jet-input id="id" class="form-control block w-full filter-field" type="text" name="id" :value="app('request')->input('id')" autofocus autocomplete="id" />
                        </div>
                        <div class="w-full md:w-1/2 px-2 mb-6 md:mb-0">
                            <x-jet-label for="period" value="{{ __('Período') }}" />
                            <x-jet-input id="period" class="form-control block w-full filter-field" type="date" name="period" :value="app('request')->input('period')" autofocus autocomplete="period" />
                        </div>
                    </div>
                </div>
                <div class="flex mt-4">
                    <table id="check-points_table" class="table table-responsive md:table w-full">
                        @include('check-points.filter-result', ['check-points' => $checkPoints, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('check-points.create-modal')
    <x-spin-load />

    <script>
        document.querySelectorAll(".show-check-points").forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault();
                const day = this.dataset.day;
                document.querySelectorAll(`tr[data-day='${day}']`).forEach(item2 => {
                    item2.style.display = item2.style.display == "table-row" ? "none" : "table-row";
                });
            });
        });
    </script>

    <script>
         document.getElementById("add_check_point").addEventListener("click", function() {
            var modal = document.getElementById("check_points_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
        });

        document.getElementById("check_points_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("check_points_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("check_points_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let url = "{!! route('check-points.store') !!}";
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let project_id = document.querySelector("#check_points_modal #project_id").value;
            let activity_id = document.querySelector("#check_points_modal #activity_id").value;
            let start = document.querySelector("#check_points_modal #start").value;
            let end = document.querySelector("#check_points_modal #end").value;
            let description = document.querySelector("#check_points_modal #description").value;

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
            data.append('project_id', project_id);
            data.append('activity_id', activity_id);
            data.append('start', start);
            data.append('end', end);
            data.append('description', description);

            ajax.send(data);

        });

    </script>

    <script>
        window.addEventListener("load", function() {
            var filterCallback = function (event) {
                var ajax = new XMLHttpRequest();
                var url = "{!! route('check-points.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var period = document.getElementById("period").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("check-points_table").innerHTML = resp.filter_result;
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
                if(period) data.append('period', period);

                ajax.send(data);
            }

            var ascending = "{!! $ascending !!}";
            var orderBY = "{!! $orderBy !!}";

            var orderByCallback = function (event) {
                orderBY = this.dataset.name;
                ascending = this.dataset.ascending;
                var that = this;
                var ajax = new XMLHttpRequest();
                var url = "{!! route('check-points.filter') !!}";
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var method = 'POST';
                var paginationPerPage = document.getElementById("paginate_per_page").value;
                var id = document.getElementById("id").value;
                var period = document.getElementById("period").value;

                ajax.open(method, url);

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var resp = JSON.parse(ajax.response);
                        document.getElementById("check-points_table").innerHTML = resp.filter_result;
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
                if(period) data.append('period', period);

                ajax.send(data);
            }

            function eventsFilterCallback() {
                document.querySelectorAll('.filter-container .filter-field').forEach(item => {
                    item.addEventListener('change', filterCallback, false);
                    item.addEventListener('keyup', filterCallback, false);
                });
                document.querySelectorAll("#check-points_table thead [data-name]").forEach(item => {
                    item.addEventListener("click", orderByCallback, false);
                });
            }

            eventsFilterCallback();
        });
    </script>

</x-app-layout>
