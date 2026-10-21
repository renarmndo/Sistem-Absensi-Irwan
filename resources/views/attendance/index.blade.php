<x-app-layout>
    <x-slot name="header">Presensi Hari Ini</x-slot>

    <div class="space-y-6" x-data="attendanceApp()">
        @if (session('success'))
            <div class="alert-success stagger-1 animate-fade-in-up">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error stagger-1 animate-fade-in-up">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!$office)
            <x-card class="stagger-2 animate-fade-in-up">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Konfigurasi Belum Tersedia</p>
                    <p class="empty-state-description">Silakan hubungi HRD untuk mengatur lokasi kantor.</p>
                </div>
            </x-card>
        @elseif ($todayAttendance && $todayAttendance->clock_in && $todayAttendance->clock_out)
            <x-card class="stagger-2 animate-fade-in-up">
                <div class="py-4 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full" style="background: #EAF3EC; color: #3F7D58;">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-heading text-lg font-semibold" style="color: #16222E;">Presensi Selesai</h3>
                    <p class="mt-1 text-sm" style="color: #8C97A1;">Anda sudah melakukan absen masuk dan pulang hari ini.</p>
                    <div class="mt-6 inline-flex items-center gap-6 rounded-2xl px-6 py-3" style="background: #FAF7F1;">
                        <div class="text-center">
                            <span class="block text-xs" style="color: #8C97A1;">Masuk</span>
                            <span class="font-mono font-medium" style="color: #16222E;">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</span>
                            <span class="ml-2">
                                <span class="badge badge-{{ strtolower($todayAttendance->status) }}">{{ $todayAttendance->status }}</span>
                            </span>
                        </div>
                        <div class="h-8 w-px" style="background: #E7E0D2;"></div>
                        <div class="text-center">
                            <span class="block text-xs" style="color: #8C97A1;">Pulang</span>
                            <span class="font-mono font-medium" style="color: #16222E;">{{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </x-card>
        @else
            <x-card class="stagger-2 animate-fade-in-up">
                <x-slot name="header">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading text-base font-semibold" style="color: #16222E;">Presensi Hari Ini</h2>
                            <p class="text-sm" style="color: #8C97A1;" x-text="currentDate"></p>
                        </div>
                    </div>
                </x-slot>

                <div class="space-y-5">
                    <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
                         style="background: linear-gradient(135deg, #1E5F8C, #143B57);">
                        <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full" style="background: rgba(255,255,255,0.05);"></div>
                        <div class="absolute -top-4 -left-4 h-16 w-16 rounded-full" style="background: rgba(255,255,255,0.05);"></div>
                        <p class="text-sm" style="color: rgba(255,255,255,0.8);">Jam kerja</p>
                        <p class="font-heading text-2xl font-bold tracking-tight">
                            {{ \Carbon\Carbon::parse($office->work_start_time)->format('H:i') }} —
                            {{ \Carbon\Carbon::parse($office->work_end_time)->format('H:i') }}
                        </p>
                        <div class="mt-2 flex gap-4 text-sm" style="color: rgba(255,255,255,0.7);">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Radius: {{ $office->radius_meter }} meter
                            </span>
                        </div>
                    </div>

                    <template x-if="!gpsStarted && !gpsReady && !gpsError && !submitting">
                        <div class="flex items-center justify-center rounded-2xl border-2 border-dashed p-10"
                             style="border-color: #E7E0D2; background: rgba(250, 247, 241, 0.5);">
                            <div class="text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full" style="background: #E9F1F5; color: #1E5F8C;">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <p class="mt-4 text-base font-medium" style="color: #16222E;">Siap untuk presensi?</p>
                                <p class="mt-1 text-sm" style="color: #8C97A1;">Klik tombol di bawah untuk memulai proses presensi hari ini.</p>
                                <button @click="startAttendance()" class="btn-primary mt-5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Mulai Presensi
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="gpsStarted && !gpsReady && !gpsError && !submitting">
                        <div class="flex items-center justify-center rounded-2xl border-2 border-dashed p-10"
                             style="border-color: #E7E0D2; background: rgba(250, 247, 241, 0.5);">
                            <div class="text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full" style="background: #E9F1F5;">
                                    <svg class="h-6 w-6 animate-pulse" style="color: #1E5F8C;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-medium" style="color: #48555F;">Mendapatkan lokasi Anda...</p>
                                <p class="text-xs" style="color: #8C97A1;">Izinkan akses lokasi jika diminta browser.</p>
                            </div>
                        </div>
                    </template>

                    <template x-if="gpsError">
                        <div class="rounded-2xl border-2 p-6 text-center"
                             style="border-color: #E8C4C4; background: #FAEDED;">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full"
                                 style="background: #FAEDED; color: #B23A3A;">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <p class="mt-3 text-sm font-medium" style="color: #8B2A2A;" x-text="gpsErrorMessage"></p>
                            <button @click="gpsStarted = false; gpsError = false;" class="btn-secondary mt-3">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Kembali
                            </button>
                            <button @click="initGPS()" class="btn-primary mt-3 ml-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Coba Lagi
                            </button>
                        </div>
                    </template>

                    <template x-if="gpsReady && !cameraReady && !cameraError && !submitting">
                        <div class="flex items-center justify-center rounded-2xl border-2 border-dashed p-10"
                             style="border-color: #E7E0D2; background: rgba(250, 247, 241, 0.5);">
                            <div class="text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full" style="background: #E9F1F5;">
                                    <svg class="h-6 w-6" style="color: #1E5F8C;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-medium" style="color: #48555F;">Lokasi berhasil dideteksi</p>
                                <p class="text-xs" style="color: #8C97A1;">Klik tombol di bawah untuk membuka kamera.</p>
                                <button @click="initCamera()" class="btn-primary mt-4">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Buka Kamera
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="cameraError">
                        <div class="rounded-2xl border-2 p-6 text-center"
                             style="border-color: #E8C4C4; background: #FAEDED;">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full"
                                 style="background: #FAEDED; color: #B23A3A;">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                                </svg>
                            </div>
                            <p class="mt-3 text-sm font-medium" style="color: #8B2A2A;" x-text="cameraErrorMessage"></p>
                            <button @click="initCamera()" class="btn-primary mt-3">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Coba Lagi
                            </button>
                        </div>
                    </template>

                    <div x-show="cameraReady && !submitting" x-cloak>
                        <div>
                            <div class="relative mx-auto aspect-square w-full max-w-sm overflow-hidden rounded-2xl shadow-lg ring-2"
                                 style="background: #16222E; --tw-ring-color: #E7E0D2;">
                                <video x-ref="video" autoplay playsinline class="h-full w-full object-cover"></video>
                                <template x-if="selfieCaptured">
                                    <img :src="selfieData" class="absolute inset-0 h-full w-full object-cover">
                                </template>
                            </div>

                            <template x-if="!selfieCaptured">
                                <button @click="captureSelfie()" class="btn-primary mx-auto mt-4 w-full max-w-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ambil Foto
                                </button>
                            </template>

                            <template x-if="selfieCaptured">
                                <div class="mt-4 flex gap-3">
                                    <button @click="retakeSelfie()" class="btn-secondary flex-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Ambil Ulang
                                    </button>

                                    <template x-if="!todayClockIn">
                                        <button @click="submitAttendance('in')" :disabled="submitting" class="btn-primary flex-1">
                                            <span x-show="!submitting">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Absen Masuk
                                            </span>
                                            <span x-show="submitting">
                                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Memproses...
                                            </span>
                                        </button>
                                    </template>

                                    <template x-if="todayClockIn && !todayClockOut">
                                        <button @click="submitAttendance('out')" :disabled="submitting" class="btn-success flex-1">
                                            <span x-show="!submitting">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                                </svg>
                                                Absen Pulang
                                            </span>
                                            <span x-show="submitting">Memproses...</span>
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <div class="mt-4 rounded-2xl p-4 text-sm" style="background: #FAF7F1;">
                                <div class="flex items-center gap-2" style="color: #48555F;">
                                    <span class="flex h-2 w-2 rounded-full"
                                          :class="withinRadius ? 'bg-status-hadir' : 'bg-status-alfa'"></span>
                                    <span class="font-mono" x-text="`Lokasi: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`"></span>
                                </div>
                                <div class="mt-1.5 flex items-center gap-2 font-medium"
                                     :class="withinRadius ? 'text-status-hadir' : 'text-status-alfa'">
                                    <span class="flex h-2 w-2 rounded-full"
                                          :class="withinRadius ? 'bg-status-hadir' : 'bg-status-alfa'"></span>
                                    <span x-text="withinRadius ? '✓ Anda berada dalam radius kantor' : `✗ Anda di luar radius kantor (${distance} meter)`"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif

        @if ($histories->isNotEmpty())
            <x-card class="stagger-3 animate-fade-in-up">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="font-heading text-base font-semibold" style="color: #16222E;">Riwayat Presensi (30 Hari)</h2>
                        </div>
                        <a href="{{ route('attendance.history') }}" class="link-primary text-[11px]">
                            Lihat Semua
                            <svg class="inline h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </x-slot>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $attendance)
                                <tr>
                                    <td style="color: #16222E;" class="font-medium">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                    <td class="font-mono">{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                                    <td class="font-mono">{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                                    <td>
                                        @php
                                            $badge = 'badge-' . strtolower($attendance->status);
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ $attendance->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif
    </div>

    @push('scripts')
    <script>
        window.attendanceApp = function() {
            return {
                currentDate: '',
                latitude: 0,
                longitude: 0,
                accuracy: 0,
                distance: 0,
                withinRadius: false,
                gpsStarted: false,
                gpsReady: false,
                gpsError: false,
                gpsErrorMessage: '',
                cameraReady: false,
                cameraError: false,
                cameraErrorMessage: '',
                selfieCaptured: false,
                selfieData: '',
                submitting: false,
                todayClockIn: @json(optional($todayAttendance)->clock_in),
                todayClockOut: @json(optional($todayAttendance)->clock_out),
                officeLat: @json($office?->latitude ?? 0),
                officeLon: @json($office?->longitude ?? 0),
                officeRadius: @json($office?->radius_meter ?? 0),

                init() {
                    this.currentDate = new Date().toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                startAttendance() {
                    this.gpsStarted = true;
                    this.initGPS();
                },

                initGPS() {
                    this.gpsReady = false;
                    this.gpsError = false;
                    this.gpsErrorMessage = '';

                    if (!navigator.geolocation) {
                        this.gpsError = true;
                        this.gpsErrorMessage = 'Browser Anda tidak mendukung GPS.';
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.accuracy = position.coords.accuracy;
                            this.gpsReady = true;

                            this.calculateDistance();
                        },
                        (error) => {
                            this.gpsError = true;
                            this.gpsStarted = false;
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    this.gpsErrorMessage = 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    this.gpsErrorMessage = 'Lokasi tidak tersedia. Pastikan GPS Anda aktif.';
                                    break;
                                case error.TIMEOUT:
                                    this.gpsErrorMessage = 'Waktu permintaan lokasi habis. Coba lagi.';
                                    break;
                                default:
                                    this.gpsErrorMessage = 'Gagal mendapatkan lokasi. Coba lagi.';
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0,
                        }
                    );
                },

                calculateDistance() {
                    const R = 6371000;
                    const lat1 = this.latitude * Math.PI / 180;
                    const lat2 = parseFloat(this.officeLat) * Math.PI / 180;
                    const dLat = (parseFloat(this.officeLat) - this.latitude) * Math.PI / 180;
                    const dLon = (parseFloat(this.officeLon) - this.longitude) * Math.PI / 180;

                    const a = Math.sin(dLat / 2) ** 2 +
                              Math.cos(lat1) * Math.cos(lat2) *
                              Math.sin(dLon / 2) ** 2;
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                    this.distance = Math.round(R * c);
                    this.withinRadius = this.distance <= this.officeRadius;
                },

                initCamera() {
                    this.cameraReady = false;
                    this.cameraError = false;
                    this.cameraErrorMessage = '';

                    if (!window.isSecureContext) {
                        this.cameraError = true;
                        this.cameraErrorMessage = 'Kamera memerlukan koneksi HTTPS. Akses aplikasi via https:// atau gunakan localhost.';
                        return;
                    }

                    if (!navigator.mediaDevices?.getUserMedia) {
                        this.cameraError = true;
                        this.cameraErrorMessage = 'Browser Anda tidak mendukung akses kamera.';
                        return;
                    }

                    if (!this.$refs.video) {
                        this.$nextTick(() => this.initCamera());
                        return;
                    }

                    navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 640 } },
                        audio: false,
                    }).then((stream) => {
                        this.$refs.video.srcObject = stream;
                        this.cameraReady = true;
                    }).catch((error) => {
                        this.cameraError = true;
                        if (error.name === 'NotAllowedError') {
                           this.cameraErrorMessage = 'Akses kamera ditolak. Izinkan akses kamera di pengaturan browser.';
                        } else if (error.name === 'NotFoundError') {
                           this.cameraErrorMessage = 'Kamera tidak ditemukan pada perangkat ini.';
                        } else {
                           this.cameraErrorMessage = 'Gagal mengakses kamera. Coba lagi.';
                        }
                    });
                },

                captureSelfie() {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    canvas.width = 640;
                    canvas.height = 640;
                    const ctx = canvas.getContext('2d');

                    const size = Math.min(video.videoWidth, video.videoHeight);
                    const sx = (video.videoWidth - size) / 2;
                    const sy = (video.videoHeight - size) / 2;

                    ctx.drawImage(video, sx, sy, size, size, 0, 0, 640, 640);
                    this.selfieData = canvas.toDataURL('image/jpeg', 0.8);
                    this.selfieCaptured = true;

                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                    }
                },

                retakeSelfie() {
                    this.selfieCaptured = false;
                    this.selfieData = '';
                    this.initCamera();
                },

                async submitAttendance(type) {
                    if (!this.selfieCaptured) return;
                    if (!this.withinRadius) {
                        alert('Anda berada di luar radius kantor. Tidak dapat melakukan absensi.');
                        return;
                    }

                    this.submitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('type', type);
                        formData.append('latitude', this.latitude);
                        formData.append('longitude', this.longitude);
                        formData.append('selfie', this.selfieData);
                        formData.append('accuracy', this.accuracy);

                        const response = await fetch('{!! route('attendance.store') !!}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                           window.location.reload();
                        } else {
                           const errorMsg = data.message || data.error || 'Terjadi kesalahan. Coba lagi.';
                           alert(errorMsg);
                           this.submitting = false;
                        }
                    } catch (error) {
                        alert('Gagal terhubung ke server. Coba lagi.');
                        this.submitting = false;
                    }
                }
            };
        };
    </script>
    @endpush
</x-app-layout>
