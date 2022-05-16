<x-app-layout>
    <div class="py-6 show-accounting-controls">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <div class="flex md:flex-row flex-col">
                <div class="w-full flex items-center">
                    <h1>{{ __('Analítico Contábil ') . {{ months()[$accountingControl->month] }}/{{ $accountingControl->year }} }}</h1>
                </div>
                <div class="w-full flex justify-end">
                    <div class="m-2 ">
                        <a class="btn-outline-info" href="{{ route('accounting-controls.index') }}">{{ __('Listar') }}</a>
                    </div>
                    <div class="m-2 hidden">
                        <a class="btn-outline-warning" href="{{ route('accounting-controls.edit', ['accounting_control' => $accountingControl->id]) }}">{{ __('Editar') }}</a>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn-outline-danger delete-accounting-control" id="accounting_control_delete" data-toggle="modal" data-target="#delete_modal" data-id="{{ $accountingControl->id }}">{{ __('Apagar') }}</button>
                    </div>
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="py-2 my-2 bg-white rounded-lg flex md:flex-row flex-col flex-wrap">
                    <div class="flex md:flex-row flex-col w-full">
                        <div class="mx-4 px-3 py-2 w-full flex justify-end" x-data="{ open: false }">
                            <div class="pr-4 flex">
                                <button @click="open = !open" id="nav-toggle" class="w-full block btn-transition-secondary">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                  </svg>
                                </button>
                            </div>
                            <!--Search-->
                            <div :class="{'block': open, 'hidden': !open}" class="w-full block" id="search-content">
                                <div class="container mx-auto">
                                    <input id="q" name="q" type="search" placeholder="Buscar..." autofocus="autofocus" class="filter-field w-full form-control no-border">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex w-full">
                        <table id="accounting_analyctics_table" class="table table-responsive md:table w-full">
                            @include('accounting-analytics.filter-result', ['accountingAnalytics' => $accountingAnalytics, 'ascending' => $ascending, 'orderBy' => $orderBy])
                        </table>
                    </div>
                    <div class="flex w-full mt-4 p-2" id="pagination">
                        {{ $accountingAnalytics->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('accounting-analytics.edit-modal')
    <x-spin-load />

    <x-modal title="{{ __('Excluir item') }}"
    msg="{{ __('Deseja realmente apagar esse item?') }}"
    confirm="{{ __('Sim') }}" cancel="{{ __('Não') }}" id="delete_accounting_control_modal"
    method="DELETE"
    url="{{ route('accounting-controls.destroy', ['accounting_control' => $accountingControl->id]) }}"
    redirect-url="{{ route('accounting-controls.index') }}"/>

    <script>
      function eventsDeleteCallback() {
        document.querySelectorAll('.delete-accounting-control').forEach(item => {
          item.addEventListener("click", function() {
            var modal = document.getElementById("delete_accounting_control_modal");
            modal.classList.remove("hidden");
            modal.classList.add("block");
          })
        });
      }

      function eventsDeleteCallback2() {
      }

      eventsDeleteCallback();
      eventsDeleteCallback2();
    </script>


    <script>
      window.addEventListener("load", function() {
        var filterCallback = function (event) {
          var ajax = new XMLHttpRequest();
          var url = "{!! route('accounting-analytics.filter') !!}";
          var token = document.querySelector('meta[name="csrf-token"]').content;
          var method = 'POST';
          var paginationPerPage = document.getElementById("paginate_per_page").value;
          var q = document.getElementById("q").value;

          ajax.open(method, url);

          ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var resp = JSON.parse(ajax.response);
              document.getElementById("accounting_analyctics_table").innerHTML = resp.filter_result;
              document.getElementById("pagination").innerHTML = resp.pagination;
              eventsFilterCallback();
              eventsDeleteCallback();
            } else if(this.readyState == 4 && this.status != 200) {
              toastr.error("{!! __('Um erro ocorreu ao gerar a consulta') !!}");
              eventsFilterCallback();
              eventsDeleteCallback();
              eventsDeleteCallback2();
            }
          }

          var data = new FormData();
          data.append('_token', token);
          data.append('_method', method);
          data.append('paginate_per_page', paginationPerPage);
          data.append('ascending', ascending);
          data.append('order_by', orderBY);
          if(q) data.append('q', q);
          data.append('accounting_control_id', {{ $accountingControl->id }});

          ajax.send(data);
        }

        var ascending = "{!! $ascending !!}";
        var orderBY = "{!! $orderBy !!}";

        var orderByCallback = function (event) {
          orderBY = this.dataset.name;
          ascending = this.dataset.ascending;
          var that = this;
          var ajax = new XMLHttpRequest();
          var url = "{!! route('accounting-analytics.filter') !!}";
          var token = document.querySelector('meta[name="csrf-token"]').content;
          var method = 'POST';
          var paginationPerPage = document.getElementById("paginate_per_page").value;
          var q = document.getElementById("q").value;

          ajax.open(method, url);

          ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var resp = JSON.parse(ajax.response);
              document.getElementById("accounting_analyctics_table").innerHTML = resp.filter_result;
              document.getElementById("pagination").innerHTML = resp.pagination;
              that.dataset.ascending = that.dataset.ascending == 'asc' ? that.dataset.ascending = 'desc' : that.dataset.ascending = 'asc';
              eventsFilterCallback();
              eventsDeleteCallback();
            } else if(this.readyState == 4 && this.status != 200) {
              toastr.error("{!! __('Um erro ocorreu ao gerar a consulta') !!}");
              eventsFilterCallback();
              eventsDeleteCallback();
              eventsDeleteCallback2();

            }
          }

          var data = new FormData();
          data.append('_token', token);
          data.append('_method', method);
          data.append('paginate_per_page', paginationPerPage);
          data.append('ascending', ascending);
          data.append('order_by', orderBY);
          if(q) data.append('q', q);
          data.append('accounting_control_id', {{ $accountingControl->id }});

          ajax.send(data);
        }

        function eventsFilterCallback() {
          document.querySelectorAll('.filter-field').forEach(item => {
            item.addEventListener('change', filterCallback, false);
            item.addEventListener('keyup', filterCallback, false);
          });
          document.querySelectorAll("#accounting_analyctics_table thead [data-name]").forEach(item => {
            item.addEventListener("click", orderByCallback, false);
          });
        }

        function eventsDeleteCallback() {
          document.querySelectorAll('.delete-accounting-analyctics').forEach(item => {
            item.addEventListener("click", function() {
              if(this.dataset.type != 'multiple') {
                var url = this.dataset.url;
                var modal = document.getElementById("delete_accounting_analyctics_modal");
                modal.dataset.url = url;
                modal.classList.remove("hidden");
                modal.classList.add("block");
              }
              else {
                var urls = '';
                document.querySelectorAll('input:checked.accounting-analyctics-url').forEach((item, index, arr) => {
                  urls += item.value ;
                  if(index < (arr.length - 1)) {
                    urls += ',';
                  }
                });

                if(urls.length > 0) {
                  var modal = document.getElementById("delete_accounting_analyctics_modal");
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


        function eventsEditCallback() {
            document.querySelectorAll('.edit-accounting-analytics').forEach(item => {
                item.addEventListener("click", function() {
                    var modal = document.getElementById("accounting_analytics_modal");
                    modal.classList.remove("hidden");
                    modal.classList.add("block");

                    const id = this.dataset.id;
                    const value = document.getElementById(`accounting_analytics_value_${id}`).value
                    const justification = document.getElementById(`accounting_analytics_justification_${id}`).value;
                    const idValue = document.getElementById(`accounting_analytics_id_${id}`).value;

                    document.querySelector("#accounting_analytics_modal #value").value = value;
                    document.querySelector("#accounting_analytics_modal #justification").value = justification;
                    document.querySelector("#accounting_analytics_modal #id").value = idValue;
                });
            });
        }

        document.getElementById("accounting_analytics_cancel_modal").addEventListener("click", function(e) {
            var modal = document.getElementById("accounting_analytics_modal");
            modal.classList.add("hidden");
        });

        document.getElementById("accounting_analytics_confirm_modal").addEventListener("click", function(e) {
            document.getElementById("spin_load").classList.remove("hidden");

            let ajax = new XMLHttpRequest();
            let token = document.querySelector('meta[name="csrf-token"]').content;
            let method = 'PUT';
            let value = document.querySelector("#accounting_analytics_modal #value").value;
            let justification = document.querySelector("#accounting_analytics_modal #justification").value
            let id = document.querySelector("#accounting_analytics_modal #id").value
            let url = "{!! route('accounting-analytics.update', ['accounting_analytics' => '#']) !!}".replace("#", id);

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
            data.append('id', id);

            ajax.send(data);

        });

        eventsEditCallback();
      });
    </script>
</x-app-layout>
