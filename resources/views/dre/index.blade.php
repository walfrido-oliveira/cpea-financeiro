<x-spin-load />
<script>
    document.getElementById("spin_load").classList.remove("hidden");
</script>

<style>
   .multiple-options {
        max-height: 18px !important;
    }

    .nice-select.has-multiple .multiple-options span.current {
        margin-top: -4px !important;
    }

    .current button {
        position: relative;
        top: 3px;
    }
</style>

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
                    <form class="w-1/3 flex justify-end items-end" id="search_year_form" action="{{ route('dre.index') }}" method="GET">
                        <div class="w-1/2 px-2 mb-6 md:mb-0">
                            <x-jet-label for="year" value="{{ __('Ano') }}"/>
                            <x-custom-select class="filter-field" select-class="no-nice-select" :options="$years" name="year" id="year"
                                :value="app('request')->has('year') ? app('request')->input('year') : now()->year"/>
                        </div>
                        <div class="w-1/3 px-2 mb-6 md:mb-0">
                            <x-jet-label for="month" value="{{ __('Mês') }}"/>
                            <x-custom-multi-select multiple :options="$monthsArr" name="month[]" id="month" :value="[]" select-class="form-input" class="" no-filter="no-filter"/>
                        </div>
                        <div class="w-1/3 px-2 mb-6 md:mb-0">
                            <button id="search" type="button" class="btn-outline-info">{{ __('Buscar') }}</button>
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
             redirect-url="{{ route('dre.index')  }}?year={{ $year }}{!! $routeMonthParams !!}"/>

    @include('dre.edit-modal')
    @include('dre.scripts')

    <link rel="stylesheet" href="/css/jquery.treetable.css">
    <link rel="stylesheet" href="/css/jquery.treetable.theme.default.css">
    <link rel="stylesheet" href="/css/screen.css">

    <script src="/js/jquery-3.7.0.min.js"></script>
    <script src="/js/jquery.treetable.js"></script>

    <script>
        jQuery("table").treetable();
    </script>
</x-app-layout>
