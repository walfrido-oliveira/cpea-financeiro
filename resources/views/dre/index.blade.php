<x-spin-load />
<script>
    document.getElementById("spin_load").classList.remove("hidden");
</script>

<x-app-layout>
    <div class="py-6 index-dre">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">

            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('DRE Contábil') }}</h1>
                </div>
            </div>
            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="flex -mx-3 mb-6 p-3 md:flex-row flex-col w-full justify-end">
                    <form class="w-1/3 flex" id="search_year_form" action="{{ route('dre.index') }}" method="GET">
                        <div class="w-1/2 px-2 mb-6 md:mb-0">
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="$years" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : now()->year"/>
                        </div>
                        <div class="w-1/2 px-2 mb-6 md:mb-0">
                            <x-custom-multi-select multiple :options="months()" name="month[]" id="month" :value="$months" select-class="form-input" class="" no-filter="no-filter"/>
                        </div>
                    </form>
                </div>
                <div id="scroll_top" style="width: calc(100vw - 21rem); overflow: auto;">
                    <div style="height: 1px;"></div>
                </div>
                <div class="flex accounting-table">
                    <div class="view">
                        <div class="wrapper" id="scroll_bottom">
                            <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                @include('dre.filter-result', ['accountingClassifications' => $accountingClassifications, 'ascending' => $ascending, 'orderBy' => $orderBy])
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal title="{{ __('Excluir Dre') }}"
             msg="{{ __('Deseja realmente apagar esse Dre?') }}"
             confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_dre_modal"
             method="DELETE"
             redirect-url="{{ route('dre.index') }}"/>

    @include('dre.edit-modal')

    <script>
        window.addEventListener("load", function() {
            document.querySelectorAll(".total").forEach(item => {
                var sum = 0;
                var id = item.dataset.id;
                var unity = item.dataset.unity;

                document.querySelectorAll(`.accounting-classification-${id}`).forEach(value => {
                    sum += parseFloat(value.value);
                });

                if(unity != '%') {
                    let currencyFormatted = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL',
                    });

                    item.innerHTML = currencyFormatted.format(parseFloat(sum).toFixed(0));
                } else {
                    item.innerHTML = parseFloat(sum).toFixed(0) + '%';
                }

            });
        });
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
                document.getElementById("spin_load").classList.remove("hidden");
            });

            document.getElementById("month").addEventListener("change", function() {
                document.getElementById("search_year_form").submit();
                document.getElementById("spin_load").classList.remove("hidden");
            });

            document.getElementById("spin_load").classList.add("hidden");
        });
    </script>

    <script>
        function eventsEditCallback() {
            document.querySelectorAll('.edit-dre').forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();

                    var modal = document.getElementById("dre_modal");
                    modal.classList.remove("hidden");
                    modal.classList.add("block");

                    document.querySelector("#dre_modal #value").value = "";
                    document.querySelector("#dre_modal #justification").value = "";
                    document.querySelector("#dre_modal #accounting_classification_id").value = this.dataset.id;
                    document.querySelector("#dre_modal #month").value = this.dataset.month;
                    document.querySelector("#dre_modal #year").value = this.dataset.year;
                    document.querySelector("#dre_modal #value").value = this.dataset.value;
                    document.querySelector("#dre_modal #justification").value = this.dataset.justification;

                    var deleteDre = document.querySelector("#dre_modal #dre_delete");

                    if(!this.dataset.destroy) {
                        deleteDre.classList.add("hidden");
                    } else {
                        deleteDre.dataset.url = deleteDre.dataset.url.replace("#", this.dataset.dre);
                    }
                });
            });
        }

        function eventsDeleteCallback() {
            document.querySelectorAll('#dre_delete').forEach(item => {
                item.addEventListener("click", function() {
                    var modalDre = document.getElementById("dre_modal");
                    modalDre.classList.add("hidden");

                    var url = this.dataset.url;
                    var modal = document.getElementById("delete_dre_modal");
                    modal.dataset.url = url;
                    modal.classList.remove("hidden");
                    modal.classList.add("block");
                });
            });
        }

        document.getElementById("dre_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("dre_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("dre_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'POST';
            let value = document.querySelector("#dre_modal #value").value;
            let justification = document.querySelector("#dre_modal #justification").value
            let accounting_classification_id = document.querySelector("#dre_modal #accounting_classification_id").value
            let month = document.querySelector("#dre_modal #month").value
            let year = document.querySelector("#dre_modal #year").value
            let url = "{!! route('dre.create') !!}";

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
            data.append('value', value);
            data.append('justification', justification);
            data.append('month', month);
            data.append('year', year);
            data.append('accounting_classification_id', accounting_classification_id);

            ajax.send(data);

        });

        eventsEditCallback();
        eventsDeleteCallback();

    </script>

</x-app-layout>
