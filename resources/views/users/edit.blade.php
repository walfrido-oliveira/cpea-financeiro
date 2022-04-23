<x-app-layout>
    <div class="py-6 edit-users">
        <div class="md:max-w-6xl lg:max-w-full mx-auto px-4">
            <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                @csrf
                @method("PUT")
                <div class="flex md:flex-row flex-col">
                    <div class="w-full flex items-center">
                        <h1>{{ __('Editar Usuário') }}</h1>
                    </div>
                    <div class="w-full flex justify-end">
                        <div class="m-2 ">
                            <button type="submit" class="btn-outline-success">{{ __('Confirmar') }}</button>
                        </div>
                        <div class="m-2">
                            <a href="{{ route('users.index')}}" class="btn-outline-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </div>

                <div class="flex md:flex-row flex-col">
                    <x-jet-validation-errors class="mb-4" />
                </div>

                <div class="py-2 my-2 bg-white rounded-lg min-h-screen">
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="name" value="{{ __('Primeiro Nome') }}" required/>
                            <x-jet-input id="name" class="form-control block mt-1 w-full" type="text" name="name" maxlength="255" :value="$user->name" required autofocus autocomplete="name"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="last_name" value="{{ __('Segundo Nome') }}" />
                            <x-jet-input id="last_name" class="form-control block mt-1 w-full" type="text" name="last_name" maxlength="255" :value="$user->last_name" autofocus autocomplete="last-name"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="email" value="{{ __('E-mail') }}"/>
                            <x-jet-input id="email" class="form-control block mt-1 w-full" type="text" name="email" maxlength="255" :value="$user->email" readonly autofocus autocomplete="email"/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="password" value="{{ __('Senha') }}" />
                            <x-jet-input id="password" class="form-control block mt-1 w-full" type="password" name="password" maxlength="255" :value="$user->password" readonly autofocus autocomplete="password"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="phone" value="{{ __('Telefone') }}" />
                            <x-jet-input id="phone" class="form-control block mt-1 w-full" type="tel" name="phone" maxlength="255" :value="$user->phone" autofocus autocomplete="phone"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap mx-4 px-3 py-2 mt-4">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="role" value="{{ __('Nível de Acesso') }}" required/>
                            <x-custom-select class="mt-1" :options="$roles" name="role" id="role" :value="$userRole" required/>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <x-jet-label for="status" value="{{ __('Situação do Usuário') }}" required/>
                            <x-custom-select class="mt-1" :options="$status" name="status" id="status" :value="$user->status" required/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
