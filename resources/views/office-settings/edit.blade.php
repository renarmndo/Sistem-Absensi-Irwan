<x-app-layout>
    <x-slot name="header">Pengaturan Kantor</x-slot>

    <div class="space-y-6" x-data="officeSettings()">
        @if (session('success'))
            <div class="alert-success stagger-1 animate-fade-in-up">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('office-settings.update') }}" class="space-y-6" x-ref="form">
            @csrf @method('PUT')

            {{-- Informasi Kantor --}}
            <x-card class="stagger-1 animate-fade-in-up">
                <x-slot name="header">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading text-base font-semibold" style="color: #16222E;">Informasi Kantor</h2>
                            <p class="text-sm" style="color: #8C97A1;">Nama dan detail lokasi kantor</p>
                        </div>
                    </div>
                </x-slot>

                <div class="space-y-5">
                    <div class="form-group">
                        <x-input-label for="office_name" value="Nama Kantor" />
                        <x-text-input id="office_name" class="mt-1" type="text" name="office_name"
                            :value="old('office_name', $office->office_name)" required placeholder="Contoh: Kantor Pusat" />
                        <x-input-error :messages="$errors->get('office_name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="form-group">
                            <x-input-label for="latitude" value="Latitude" />
                            <x-text-input id="latitude" class="mt-1 font-mono" type="text" name="latitude"
                                :value="old('latitude', $office->latitude)" required placeholder="-6.2088"
                                x-model="latitude" readonly />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="longitude" value="Longitude" />
                            <x-text-input id="longitude" class="mt-1 font-mono" type="text" name="longitude"
                                :value="old('longitude', $office->longitude)" required placeholder="106.8456"
                                x-model="longitude" readonly />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="radius_meter" value="Radius (meter)" />
                            <x-text-input id="radius_meter" class="mt-1" type="number" name="radius_meter" min="10" max="500"
                                :value="old('radius_meter', $office->radius_meter)" required x-model="radius" />
                            <x-input-error :messages="$errors->get('radius_meter')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Peta Lokasi --}}
            <x-card class="stagger-2 animate-fade-in-up">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-heading text-base font-semibold" style="color: #16222E;">Peta Lokasi</h2>
                                <p class="text-sm" style="color: #8C97A1;">Tentukan posisi kantor pada peta</p>
                            </div>
                        </div>
                        <button type="button" @click="detectMyLocation()" :disabled="detectingLocation"
                                class="btn-secondary btn-sm" :class="detectingLocation && 'opacity-60 cursor-not-allowed'">
                            <template x-if="!detectingLocation">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Gunakan Lokasi Saya
                                </span>
                            </template>
                            <template x-if="detectingLocation">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Mendeteksi...
                                </span>
                            </template>
                        </button>
                    </div>
                </x-slot>

                <div class="space-y-4">
                    <div id="map" class="h-[400px] w-full rounded-xl ring-1" style="--tw-ring-color: #E7E0D2;"></div>

                    <div class="flex items-start gap-3 rounded-xl p-4" style="background: #FAF7F1;">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-sm" style="color: #48555F;">
                            <p class="font-medium">Petunjuk</p>
                            <ul class="mt-1 space-y-1 text-xs" style="color: #8C97A1;">
                                <li>Klik pada peta atau drag marker untuk memilih lokasi kantor.</li>
                                <li>Tombol <span class="font-medium" style="color: #48555F;">"Gunakan Lokasi Saya"</span> mendeteksi posisi perangkat Anda secara otomatis.</li>
                                <li>Lingkaran putih transparan menunjukkan area radius presensi.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Jam Kerja --}}
            <x-card class="stagger-3 animate-fade-in-up">
                <x-slot name="header">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading text-base font-semibold" style="color: #16222E;">Jam Kerja</h2>
                            <p class="text-sm" style="color: #8C97A1;">Atur waktu masuk dan pulang karyawan</p>
                        </div>
                    </div>
                </x-slot>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="form-group">
                            <x-input-label for="work_start_time" value="Jam Masuk" />
                            <x-text-input id="work_start_time" class="mt-1" type="time" name="work_start_time"
                                :value="old('work_start_time', $office->work_start_time)" required />
                            <x-input-error :messages="$errors->get('work_start_time')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="work_end_time" value="Jam Pulang" />
                            <x-text-input id="work_end_time" class="mt-1" type="time" name="work_end_time"
                                :value="old('work_end_time', $office->work_end_time)" required />
                            <x-input-error :messages="$errors->get('work_end_time')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="effective_work_days" value="Hari Kerja Efektif/Bulan" />
                            <x-text-input id="effective_work_days" class="mt-1" type="number" name="effective_work_days" min="1" max="31"
                                :value="old('effective_work_days', $office->effective_work_days)" required />
                            <x-input-error :messages="$errors->get('effective_work_days')" class="mt-2" />
                            <p class="mt-1 text-xs" style="color: #8C97A1;">Digunakan untuk perhitungan gaji potongan</p>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 stagger-4 animate-fade-in-up">
                <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        function officeSettings() {
            return {
                latitude: '{{ old("latitude", $office->latitude) }}',
                longitude: '{{ old("longitude", $office->longitude) }}',
                radius: {{ old("radius_meter", $office->radius_meter) }},
                detectingLocation: false,
                map: null,
                marker: null,
                circle: null,

                init() {
                    const lat = parseFloat(this.latitude) || -6.2088;
                    const lng = parseFloat(this.longitude) || 106.8456;

                    this.map = L.map('map', { zoomControl: true }).setView([lat, lng], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.addMarker(lat, lng, this.radius);

                    this.map.on('click', (e) => {
                        const { lat, lng } = e.latlng;
                        this.latitude = lat.toFixed(6);
                        this.longitude = lng.toFixed(6);
                        this.updateMarker(lat, lng, this.radius);
                    });

                    this.$watch('radius', (val) => {
                        const r = parseInt(val) || 50;
                        if (this.marker) {
                            const pos = this.marker.getLatLng();
                            this.updateMarker(pos.lat, pos.lng, r);
                        }
                    });
                },

                addMarker(lat, lng, radius) {
                    const icon = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="width:24px;height:24px;border-radius:50%;background:#E53E3E;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
                        iconSize: [24, 24],
                        iconAnchor: [12, 12],
                    });

                    this.marker = L.marker([lat, lng], { icon, draggable: true }).addTo(this.map);

                    this.circle = L.circle([lat, lng], {
                        radius: radius,
                        color: '#1E5F8C',
                        fillColor: '#1E5F8C',
                        fillOpacity: 0.08,
                        weight: 2,
                        dashArray: '6 4',
                    }).addTo(this.map);

                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.latitude = pos.lat.toFixed(6);
                        this.longitude = pos.lng.toFixed(6);
                        this.circle.setLatLng(pos);
                    });
                },

                updateMarker(lat, lng, radius) {
                    this.marker.setLatLng([lat, lng]);
                    this.circle.setLatLng([lat, lng]);
                    this.circle.setRadius(radius);
                    this.map.setView([lat, lng]);
                },

                detectMyLocation() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung GPS.');
                        return;
                    }

                    this.detectingLocation = true;

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            this.latitude = lat.toFixed(6);
                            this.longitude = lng.toFixed(6);
                            this.updateMarker(lat, lng, this.radius);
                            this.detectingLocation = false;
                        },
                        (error) => {
                            this.detectingLocation = false;
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    alert('Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser.');
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    alert('Lokasi tidak tersedia. Pastikan GPS Anda aktif.');
                                    break;
                                case error.TIMEOUT:
                                    alert('Waktu permintaan lokasi habis. Coba lagi.');
                                    break;
                                default:
                                    alert('Gagal mendapatkan lokasi. Coba lagi.');
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0,
                        }
                    );
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
