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
                    <form id="search_year_form" action="{{ route('dre.index') }}" method="GET">
                        <div class="w-full md:w-auto px-2 mb-6 md:mb-0">
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="[2022 => 2022, 2021 => 2021]" name="year" id="year" :value="app('request')->input('year')"/>
                        </div>
                    </form>
                </div>
                <div class="flex mt-4 accounting-table">
                    <div class="view">
                        <div class="wrapper">
                            <table id="accounting_classifications_table" class="table table-responsive md:table w-full">
                                @include('dre.filter-result', ['accountingClassifications' => $accountingClassifications, 'ascending' => $ascending, 'orderBy' => $orderBy])
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-spin-load />

    <script>
        window.addEventListener("load", function() {
            document.getElementById("year").addEventListener("change", function() {
                document.getElementById("search_year_form").submit();
            });
        });
    </script>

</x-app-layout>
