<x-app-layout>
    <x-slot name="header">Tambah Karyawan</x-slot>

    <div class="space-y-6">
        <x-card class="stagger-1 animate-fade-in-up">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="form-group">
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input id="name" class="mt-1" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="form-group">
                        <x-input-label for="employee_id" value="NIK / Employee ID" />
                        <x-text-input id="employee_id" class="mt-1" type="text" name="employee_id" :value="old('employee_id')" required placeholder="KRY-001" />
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <x-input-label for="phone" value="No. Telepon" />
                        <x-text-input id="phone" class="mt-1" type="text" name="phone" :value="old('phone')" placeholder="08123456789" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="form-group">
                        <x-input-label for="position" value="Jabatan" />
                        <x-text-input id="position" class="mt-1" type="text" name="position" :value="old('position')" placeholder="Staff Marketing" />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <x-input-label for="role_id" value="Role" />
                        <select id="role_id" name="role_id" required class="input mt-1 w-full">
                            <option value="">Pilih role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="form-group">
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" class="mt-1" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('users.index') }}" class="btn-secondary flex-1 text-center text-sm">Batal</a>
                    <button type="submit" class="btn-primary flex-1 text-sm">Simpan</button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
