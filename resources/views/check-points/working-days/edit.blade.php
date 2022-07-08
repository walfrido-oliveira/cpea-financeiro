<x-app-layout>
    <div class="py-6 edit-working-days">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('check-points.working-days.update', ['working_day' => $workingDay->id]) }}">
                @csrf
                @method("PUT")
                <div class="flex md:flex-row flex-col">
                    <div class="w-full flex items-center">
                        <h1>{{ __('Jornada de Trabalho') }}</h1>
                    </div>
                    <div class="w-full flex justify-end">
                        <div class="m-2 ">
                            <button type="submit" class="btn-outline-success">{{ __('Confirmar') }}</button>
                        </div>
                        <div class="m-2">
                            <a href="{{ route('check-points.working-days.index')}}" class="btn-outline-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:flex-row flex-col">
                    <x-jet-validation-errors class="mb-4" />
                </div>

                <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="description" value="{{ __('Descrição') }}" required/>
                            <x-jet-input id="description" class="form-control block mt-1 w-full" :value="$workingDay->description" type="text" name="description" maxlength="255" required autofocus autocomplete="description" placeholder="{{ __('Descrição') }}"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="day_of_the_week" value="{{ __('Dias da Semana') }}" required/>
                            <x-custom-multi-select multiple class="mt-1" :options="$daysOfTheWeek" name="day_of_the_week[]" id="day_of_the_week" required :value="$workingDay->day_of_the_week"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="start" value="{{ __('Início') }}" required/>
                            <x-jet-input id="start" class="form-control block mt-1 w-full" :value="$workingDay->start->format('H:i')" type="time" name="start" required autofocus autocomplete="start" placeholder="{{ __('Início') }}"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="end" value="{{ __('Fim') }}" required/>
                            <x-jet-input id="end" class="form-control block mt-1 w-full"  :value="$workingDay->end->format('H:i')" type="time" name="end" required autofocus autocomplete="end" placeholder="{{ __('Fim') }}"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Observações') }}" />
                            <textarea class="form-input w-full" name="obs" id="obs" cols="30" rows="10">{{ $workingDay->obs }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
