<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.users.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.users.store') }}" method="POST">
            @csrf

            <div class="grid gap-2">
                <div class="mb-5 space-y-2">
                    <x-form.label for="name" :value="__('Nama')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                        placeholder="{{ __('Nama pengguna') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="email" :value="__('Email')" />
                    <x-form.input id="email" class="block w-full" type="text" name="email" :value="old('email')"
                        placeholder="{{ __('Email') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="role_id" :value="__('Pilih Role')" />
                    <x-form.select id="role_id" class="block w-full" name="role_id">
                        <option value="" disabled selected>{{ __('Pilih Role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('id')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                  <x-form.label for="password" :value="__('Password')" />
                  <x-form.input id="password" class="block w-full" type="text" name="password" :value="old('password')"
                      placeholder="{{ __('Password') }}" required />
              </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>
