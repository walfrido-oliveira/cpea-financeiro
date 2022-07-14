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
                </div>
            </div>

            <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                <div class="filter-container">
                    <div class="flex -mx-3 p-3 md:flex-row flex-col w-full">
                        <form action="" id="search_year_form" class="flex md:flex-column flex-row w-full justify-end">
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
                </div>
                <div class="flex mt-4">
                    <table id="check-points_table" class="table table-responsive md:table w-full">
                        @include('check-points.filter-result-admin', ['check-points' => $checkPoints, 'ascending' => $ascending, 'orderBy' => $orderBy])
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-spin-load />

    <script>
        document.getElementById("year").addEventListener("change", function() {
            document.getElementById("search_year_form").submit();
        });
        document.getElementById("month").addEventListener("change", function() {
            document.getElementById("search_year_form").submit();
        });


    </script>





</x-app-layout>
