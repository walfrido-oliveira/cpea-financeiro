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
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="[2022 => 2022, 2021 => 2021, 2020 => 2020]" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : now()->year"/>
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

    <x-spin-load />

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

</x-app-layout>
