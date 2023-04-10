<x-app-layout>
    <div class="py-6 index-check_point">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Total de horas') }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <form action="{{ route('check-points.total-static-check-point.import') }}" method="POST">
                        @csrf
                        @method("POST")
                        <div class="m-2 ">
                            <button id="import" type="button" class="btn-outline-info">{{ __('Importar') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full justify-end">
                    <form id="search_year_form" action="{{ route('check-points.total-static-check-point.index') }}" method="GET">
                        <div class="w-full md:w-auto px-2 mb-6 md:mb-0">
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="$years" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : now()->year"/>
                        </div>
                    </form>
                </div>
                <div class="w-full">
                    <h2 class="my-6 ml-3">Horas Projetos</h2>
                    <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="flex accounting-table">
                        <div class="view">
                            <div class="wrapper" id="scroll_bottom">
                                <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                    @include('total-static-check-point.filter-result', ['totalStaticCheckPoints' =>  App\Models\TotalStaticCheckPoint::where('year', $year)->where('type', 'Horas Projetos')->groupBy('classification_id')->orderBy('order')->get(),
                                    'ascending' => $ascending, 'orderBy' => $orderBy, 'type' => 'Horas Projetos'])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <h2 class="my-6 ml-3">Horas Administrativas</h2>
                    <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="flex accounting-table">
                        <div class="view">
                            <div class="wrapper" id="scroll_bottom">
                                <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                    @include('total-static-check-point.filter-result', ['totalStaticCheckPoints' => App\Models\TotalStaticCheckPoint::where('year', $year)->where('type', 'Horas Administrativas')->groupBy('classification_id')->orderBy('order')->get(),
                                    'ascending' => $ascending, 'orderBy' => $orderBy, 'type' => 'Horas Administrativas'])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <h2 class="my-6 ml-3">% Custo Direto</h2>
                    <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="flex accounting-table">
                        <div class="view">
                            <div class="wrapper" id="scroll_bottom">
                                <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                    @include('total-static-check-point.formula', ['totalStaticCheckPoints' => App\Models\TotalStaticCheckPoint::where('year', $year)->groupBy('classification_id')->orderBy('order')->get(),
                                    'ascending' => $ascending, 'orderBy' => $orderBy, 'type' => 'Custo Direto'])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <h2 class="my-6 ml-3">% Custo Indireto</h2>
                    <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="flex accounting-table">
                        <div class="view">
                            <div class="wrapper" id="scroll_bottom">
                                <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                    @include('total-static-check-point.formula', ['totalStaticCheckPoints' => App\Models\TotalStaticCheckPoint::where('year', $year)->groupBy('classification_id')->orderBy('order')->get(),
                                    'ascending' => $ascending, 'orderBy' => $orderBy, 'type' => 'Custo Indireto'])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <h2 class="my-6 ml-3">% Total</h2>
                    <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                        <div style="height: 1px;"></div>
                    </div>
                    <div class="flex accounting-table">
                        <div class="view">
                            <div class="wrapper" id="scroll_bottom">
                                <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                    @include('total-static-check-point.total', ['totalStaticCheckPoints' => App\Models\TotalStaticCheckPoint::where('year', $year)->groupBy('classification_id')->orderBy('order')->get(),
                                    'ascending' => $ascending, 'orderBy' => $orderBy, 'type' => ''])
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('total-static-check-point.import-modal')
    @include('total-static-check-point.edit-modal')

    <x-spin-load />

    <script>
        function eventsEditCallback() {
            document.querySelectorAll('.edit-check-point').forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();

                    var modal = document.getElementById("check_point_modal");
                    modal.classList.remove("hidden");
                    modal.classList.add("block");

                    document.querySelector("#check_point_modal #result").value = "";
                    document.querySelector("#check_point_modal #justification").value = "";
                    document.querySelector("#check_point_modal #static_id").value = this.dataset.id;
                    document.querySelector("#check_point_modal #month").value = this.dataset.month;
                    document.querySelector("#check_point_modal #year").value = this.dataset.year;
                    document.querySelector("#check_point_modal #type").value = this.dataset.type;

                });
            });
        }

        document.getElementById("check_point_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("check_point_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("check_point_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';

            let result = document.querySelector("#check_point_modal #result").value;
            let justification = document.querySelector("#check_point_modal #justification").value;
            let id = document.querySelector("#check_point_modal #static_id").value;
            let month = document.querySelector("#check_point_modal #month").value;
            let year = document.querySelector("#check_point_modal #year").value;
            let type = document.querySelector("#check_point_modal #type").value;

            let url = "{!! route('check-points.total-static-check-point.update', ['id' => '#']) !!}".replace('#', id);

            ajax.open("POST", url);

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
            data.append('result', result);
            data.append('justification', justification);
            data.append('id', id);
            data.append('year', year);
            data.append('month', month);
            data.append('type', type);

            ajax.send(data);

        });

        eventsEditCallback();

    </script>

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
            document.getElementById("year").addEventListener("change", function() {
                document.getElementById("search_year_form").submit();
            });
        });
    </script>

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
            let url = "{!! route('check-points.total-static-check-point.import') !!}";
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

    </script>

</x-app-layout>
