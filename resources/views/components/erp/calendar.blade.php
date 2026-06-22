@props(['fetchUrl', 'colorBy' => 'program'])

{{-- dayjs via CDN — loaded once per page --}}
@once
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/isoWeek.js"></script>
<script>
dayjs.locale('es');
dayjs.extend(window.dayjs_plugin_isoWeek);
</script>
@endonce

<div x-data="erpCalendar('{{ $fetchUrl }}')" x-init="load()" class="flex flex-col lg:flex-row gap-4 items-start">

    {{-- ── CALENDAR GRID ── --}}
    <div class="flex-1 min-w-0 rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-ugarte-border bg-white px-5 py-3">
            <button @click="prev()"
                    class="flex items-center gap-1 rounded-lg border border-ugarte-border px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                ‹ Anterior
            </button>
            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700 capitalize"
                x-text="monthLabel()"></h3>
            <button @click="next()"
                    class="flex items-center gap-1 rounded-lg border border-ugarte-border px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                Siguiente ›
            </button>
        </div>

        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-ugarte-border">
            <template x-for="d in ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom']">
                <div class="bg-gray-50 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-400"
                     x-text="d"></div>
            </template>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="flex items-center justify-center gap-2 py-16 text-sm text-gray-400">
            <svg class="animate-spin h-4 w-4 text-ugarte-primary" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Cargando…
        </div>

        {{-- Grid cells --}}
        <div x-show="!loading" class="grid grid-cols-7 auto-rows-[minmax(110px,auto)] divide-x divide-y divide-ugarte-border">
            <template x-for="cell in calendarCells()" :key="cell.date">
                <div class="relative flex flex-col p-1.5"
                     :class="{
                         'bg-gray-50/70': !cell.currentMonth,
                         'bg-amber-50':  cell.isToday && cell.currentMonth,
                         'bg-white':     cell.currentMonth && !cell.isToday
                     }">

                    {{-- Day number --}}
                    <div class="mb-1 flex items-center justify-between">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full text-[11px] font-bold"
                              :class="{
                                  'bg-ugarte-primary text-white':   cell.isToday,
                                  'text-gray-700':                  !cell.isToday && cell.currentMonth,
                                  'text-gray-300':                  !cell.currentMonth
                              }"
                              x-text="cell.day"></span>
                        <template x-if="cell.isToday">
                            <span class="text-[9px] font-bold uppercase tracking-wide text-ugarte-primary">HOY</span>
                        </template>
                    </div>

                    {{-- Events --}}
                    <div class="flex flex-col gap-0.5">
                        <template x-for="event in cell.sessions" :key="event.id">
                            <button type="button"
                                    @click="selectEvent(event)"
                                    class="w-full rounded-md px-1.5 py-1 text-left text-[11px] text-white transition-all hover:opacity-90 hover:shadow-sm focus:outline-none"
                                    :style="{ backgroundColor: event.color }"
                                    :class="{
                                        'ring-2 ring-ugarte-primary ring-offset-1': selectedEvent?.id === event.id,
                                        'opacity-50': event.status === 'cancelled'
                                    }">
                                <div class="flex items-center gap-1 font-bold leading-none mb-0.5">
                                    <template x-if="event.is_live">
                                        <span class="h-1.5 w-1.5 flex-shrink-0 rounded-full bg-white animate-pulse"></span>
                                    </template>
                                    <span x-text="event.starts_at.slice(11,16) + ' – ' + event.ends_at.slice(11,16)"></span>
                                </div>
                                <div class="truncate leading-tight opacity-90" x-text="event.course"></div>
                            </button>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer legend --}}
        <div class="flex flex-wrap items-center gap-4 border-t border-ugarte-border bg-gray-50 px-4 py-2 text-[11px] text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-ugarte-primary animate-pulse"></span>
                En vivo
            </span>
            <span class="flex items-center gap-1.5">
                <span class="h-4 w-4 rounded bg-amber-100 border border-amber-200"></span>
                Hoy
            </span>
            <span class="flex items-center gap-1.5">
                <span class="h-4 w-4 rounded bg-ugarte-primary opacity-50"></span>
                Cancelada
            </span>
            <span class="ml-auto text-gray-400">
                <span x-text="sessions.length"></span> sesiones este mes
            </span>
        </div>
    </div>

    {{-- ── DETAIL PANEL ── --}}
    <div x-show="selectedEvent"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 lg:translate-y-0 lg:translate-x-4"
         x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="w-full lg:w-72 flex-shrink-0 rounded-xl border border-ugarte-border bg-white shadow-sm overflow-hidden"
         x-cloak>

        {{-- Panel header --}}
        <div class="flex items-center justify-between border-b border-ugarte-border px-4 py-3">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Detalle de sesión</p>
            <button @click="selectedEvent = null"
                    class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-4 space-y-4">

            {{-- Live banner --}}
            <template x-if="selectedEvent?.is_live">
                <div class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2">
                    <span class="h-2 w-2 flex-shrink-0 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-red-700">Sesión en curso ahora</span>
                </div>
            </template>

            {{-- Color accent bar --}}
            <div class="h-1 rounded-full" :style="{ backgroundColor: selectedEvent?.color }"></div>

            {{-- Título --}}
            <div>
                <p class="font-semibold text-gray-900" x-text="selectedEvent?.title"></p>
                <p class="mt-0.5 text-xs text-gray-400" x-text="selectedEvent?.program"></p>
            </div>

            {{-- Datos --}}
            <dl class="divide-y divide-gray-100 rounded-lg border border-ugarte-border bg-gray-50 text-sm overflow-hidden">
                <div class="flex items-start gap-3 px-3 py-2.5">
                    <dt class="w-14 flex-shrink-0 text-[11px] font-semibold uppercase tracking-wide text-gray-400 pt-0.5">Curso</dt>
                    <dd class="text-gray-700 text-xs leading-snug" x-text="selectedEvent?.course"></dd>
                </div>
                <div class="flex items-start gap-3 px-3 py-2.5">
                    <dt class="w-14 flex-shrink-0 text-[11px] font-semibold uppercase tracking-wide text-gray-400 pt-0.5">Fecha</dt>
                    <dd class="text-gray-700 text-xs leading-snug capitalize"
                        x-text="selectedEvent ? dayjs(selectedEvent.starts_at).format('dddd, D [de] MMMM [de] YYYY') : ''"></dd>
                </div>
                <div class="flex items-start gap-3 px-3 py-2.5">
                    <dt class="w-14 flex-shrink-0 text-[11px] font-semibold uppercase tracking-wide text-gray-400 pt-0.5">Hora</dt>
                    <dd class="font-semibold text-gray-800 text-sm"
                        x-text="selectedEvent ? (selectedEvent.starts_at.slice(11,16) + ' – ' + selectedEvent.ends_at.slice(11,16)) : ''"></dd>
                </div>
                <template x-if="selectedEvent?.room">
                    <div class="flex items-start gap-3 px-3 py-2.5">
                        <dt class="w-14 flex-shrink-0 text-[11px] font-semibold uppercase tracking-wide text-gray-400 pt-0.5">Aula</dt>
                        <dd class="text-gray-700 text-xs" x-text="selectedEvent?.room"></dd>
                    </div>
                </template>
                <div class="flex items-center gap-3 px-3 py-2.5">
                    <dt class="w-14 flex-shrink-0 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Estado</dt>
                    <dd>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
                              :class="{
                                  'bg-blue-100 text-blue-700':   selectedEvent?.status === 'scheduled',
                                  'bg-yellow-100 text-yellow-700': selectedEvent?.status === 'in_progress',
                                  'bg-green-100 text-green-700': selectedEvent?.status === 'completed',
                                  'bg-red-100 text-red-700':     selectedEvent?.status === 'cancelled'
                              }"
                              x-text="{
                                  scheduled:   'Programada',
                                  in_progress: 'En curso',
                                  completed:   'Completada',
                                  cancelled:   'Cancelada'
                              }[selectedEvent?.status] || selectedEvent?.status">
                        </span>
                    </dd>
                </div>
            </dl>

            {{-- Asistencia registrada --}}
            <template x-if="selectedEvent?.attendance_taken">
                <div class="flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-2">
                    <svg class="h-3.5 w-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xs font-medium text-green-700">Asistencia registrada</span>
                </div>
            </template>

            {{-- Botón unirse --}}
            <template x-if="selectedEvent?.meeting_url">
                <a :href="selectedEvent.meeting_url" target="_blank" rel="noopener noreferrer"
                   class="flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white transition-colors"
                   :class="selectedEvent?.can_join ? 'bg-red-600 hover:bg-red-700' : 'bg-ugarte-primary hover:bg-ugarte-dark'">
                    <template x-if="selectedEvent?.can_join">
                        <span class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                            Unirse ahora
                        </span>
                    </template>
                    <template x-if="!selectedEvent?.can_join">
                        <span>Ver enlace de reunión</span>
                    </template>
                </a>
            </template>

        </div>
    </div>
</div>

<script>
function erpCalendar(fetchUrl) {
    return {
        year: dayjs().year(),
        month: dayjs().month(),
        sessions: [],
        loading: false,
        selectedEvent: null,

        monthLabel() {
            return dayjs().year(this.year).month(this.month).format('MMMM YYYY');
        },

        prev() {
            if (this.month === 0) { this.month = 11; this.year--; }
            else { this.month--; }
            this.selectedEvent = null;
            this.load();
        },

        next() {
            if (this.month === 11) { this.month = 0; this.year++; }
            else { this.month++; }
            this.selectedEvent = null;
            this.load();
        },

        selectEvent(event) {
            this.selectedEvent = this.selectedEvent?.id === event.id ? null : event;
        },

        async load() {
            this.loading = true;
            const m = String(this.month + 1).padStart(2, '0');
            try {
                const res = await fetch(`${fetchUrl}?month=${this.year}-${m}`);
                if (!res.ok) throw new Error('Network error');
                this.sessions = await res.json();
            } catch (e) {
                this.sessions = [];
            }
            this.loading = false;
        },

        calendarCells() {
            const first     = dayjs().year(this.year).month(this.month).startOf('month');
            const last      = first.endOf('month');
            const startCell = first.isoWeekday(); // 1=Mon
            const todayStr  = dayjs().format('YYYY-MM-DD');
            const cells     = [];

            // Padding before month
            for (let i = 1; i < startCell; i++) {
                const d = first.subtract(startCell - i, 'day');
                cells.push({ date: d.format('YYYY-MM-DD'), day: d.date(), currentMonth: false, isToday: false, sessions: [] });
            }

            // Days in month
            for (let d = first; d.isBefore(last.add(1, 'day')); d = d.add(1, 'day')) {
                const dateStr = d.format('YYYY-MM-DD');
                cells.push({
                    date:         dateStr,
                    day:          d.date(),
                    currentMonth: true,
                    isToday:      dateStr === todayStr,
                    sessions:     this.sessions.filter(s => s.starts_at.startsWith(dateStr)),
                });
            }

            // Padding after month to complete last row
            const rem = cells.length % 7;
            if (rem > 0) {
                for (let i = 1; i <= 7 - rem; i++) {
                    const d = last.add(i, 'day');
                    cells.push({ date: d.format('YYYY-MM-DD'), day: d.date(), currentMonth: false, isToday: false, sessions: [] });
                }
            }

            return cells;
        },
    };
}
</script>
