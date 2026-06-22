<x-layouts.app title="Matrícula Masiva">
    <x-slot:actions>
        <a href="{{ route('admin.enrollments.index') }}"
           class="flex items-center gap-2 rounded-lg border border-ugarte-border bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <x-erp.icon name="arrow-left" class="h-4 w-4" />
            Volver
        </a>
    </x-slot:actions>

    <div x-data="bulkEnrollmentWizard()" x-cloak>

        {{-- Step indicator --}}
        <div class="mb-8 flex items-center justify-center gap-0">
            <template x-for="(s, i) in [{n:1, label:'Sección'}, {n:2, label:'Alumnos'}, {n:3, label:'Confirmar'}]" :key="i">
                <div class="flex items-center">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold transition-colors"
                             :class="step > s.n ? 'bg-green-500 text-white' : (step === s.n ? 'bg-ugarte-primary text-white' : 'bg-gray-200 text-gray-500')">
                            <template x-if="step > s.n">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </template>
                            <template x-if="step <= s.n">
                                <span x-text="s.n"></span>
                            </template>
                        </div>
                        <span class="hidden text-sm font-medium sm:inline" :class="step >= s.n ? 'text-gray-900' : 'text-gray-400'" x-text="s.label"></span>
                    </div>
                    <div x-show="i < 2" class="mx-4 h-px w-12 sm:w-20" :class="step > s.n ? 'bg-green-400' : 'bg-gray-200'"></div>
                </div>
            </template>
        </div>

        {{-- ═══════════════ PASO 1: Seleccionar Sección ═══════════════ --}}
        <div x-show="step === 1" x-transition>
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-lg font-semibold text-gray-900">Seleccionar Sección</h2>
                <p class="mb-6 text-sm text-gray-500">Elige la sección a la que deseas matricular alumnos.</p>

                {{-- Buscador --}}
                <div class="mb-4">
                    <input type="text" x-model="sectionSearch" @input.debounce.300ms="fetchSections()"
                           placeholder="Buscar por curso o programa…"
                           class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                </div>

                {{-- Loading --}}
                <div x-show="loading" class="py-12 text-center text-sm text-gray-400">
                    <svg class="mx-auto h-6 w-6 animate-spin text-ugarte-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <p class="mt-2">Cargando secciones…</p>
                </div>

                {{-- Tabla de secciones --}}
                <div x-show="!loading && sections.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-ugarte-border bg-gray-50/50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-4 py-3 w-10"></th>
                                <th class="px-4 py-3">Curso</th>
                                <th class="px-4 py-3">Programa</th>
                                <th class="px-4 py-3">Sec.</th>
                                <th class="px-4 py-3">Docente</th>
                                <th class="px-4 py-3">Período</th>
                                <th class="px-4 py-3 text-center">Vacantes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ugarte-border/50">
                            <template x-for="s in sections" :key="s.id">
                                <tr class="cursor-pointer transition-colors"
                                    :class="selectedSection?.id === s.id ? 'bg-ugarte-primary/5' : 'hover:bg-gray-50'"
                                    @click="selectSection(s)">
                                    <td class="px-4 py-3">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors"
                                             :class="selectedSection?.id === s.id ? 'border-ugarte-primary bg-ugarte-primary' : 'border-gray-300'">
                                            <div x-show="selectedSection?.id === s.id" class="h-2 w-2 rounded-full bg-white"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900" x-text="s.course_name"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="s.program_name"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="s.section_code"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="s.teacher_name"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="s.period_name"></td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700"
                                              x-text="s.available_slots + '/' + s.capacity"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Sin resultados --}}
                <div x-show="!loading && sections.length === 0" class="py-12 text-center text-sm text-gray-400">
                    No se encontraron secciones con vacantes disponibles.
                </div>

                {{-- Acciones --}}
                <div class="mt-6 flex justify-end">
                    <button @click="goToStep2()" :disabled="!selectedSection"
                            class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════ PASO 2: Seleccionar Alumnos ═══════════════ --}}
        <div x-show="step === 2" x-transition>
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">

                {{-- Resumen sección --}}
                <div class="mb-6 flex flex-wrap items-center gap-3 rounded-lg bg-ugarte-primary/5 px-4 py-3 text-sm">
                    <span class="font-semibold text-ugarte-primary" x-text="selectedSection?.course_name"></span>
                    <span class="text-gray-400">—</span>
                    <span class="text-gray-600">Sec. <span x-text="selectedSection?.section_code"></span></span>
                    <span class="text-gray-400">—</span>
                    <span class="text-gray-600" x-text="selectedSection?.program_name"></span>
                    <span class="ml-auto inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700"
                          x-text="selectedSection?.available_slots + ' vacantes'"></span>
                </div>

                <h2 class="mb-1 text-lg font-semibold text-gray-900">Seleccionar Alumnos</h2>
                <p class="mb-4 text-sm text-gray-500">Marca los alumnos que deseas matricular en esta sección.</p>

                {{-- Buscador + contador --}}
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <input type="text" x-model="studentSearch" @input.debounce.300ms="fetchStudents()"
                           placeholder="Buscar por nombre, DNI o correo…"
                           class="flex-1 rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary">
                    <span class="text-sm font-medium"
                          :class="selectedStudentIds.length > 0 ? 'text-ugarte-primary' : 'text-gray-400'">
                        <span x-text="selectedStudentIds.length"></span> de <span x-text="studentsTotal"></span> seleccionados
                    </span>
                </div>

                {{-- Loading --}}
                <div x-show="loading" class="py-12 text-center text-sm text-gray-400">
                    <svg class="mx-auto h-6 w-6 animate-spin text-ugarte-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <p class="mt-2">Cargando alumnos…</p>
                </div>

                {{-- Tabla de alumnos --}}
                <div x-show="!loading && students.length > 0" class="overflow-x-auto max-h-[28rem] overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 border-b border-ugarte-border bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-4 py-3 w-10">
                                    <input type="checkbox" @change="toggleAll()" :checked="selectedStudentIds.length === students.length && students.length > 0"
                                           class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary/30">
                                </th>
                                <th class="px-4 py-3">Alumno</th>
                                <th class="px-4 py-3">DNI</th>
                                <th class="px-4 py-3">Correo</th>
                                <th class="px-4 py-3 text-center">Matrículas activas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ugarte-border/50">
                            <template x-for="st in students" :key="st.id">
                                <tr class="cursor-pointer transition-colors"
                                    :class="selectedStudentIds.includes(st.id) ? 'bg-ugarte-primary/5' : 'hover:bg-gray-50'"
                                    @click="toggleStudent(st.id)">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" :checked="selectedStudentIds.includes(st.id)"
                                               @click.stop="toggleStudent(st.id)"
                                               class="h-4 w-4 rounded border-gray-300 text-ugarte-primary focus:ring-ugarte-primary/30">
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900" x-text="st.name"></td>
                                    <td class="px-4 py-3 text-gray-600 font-mono text-xs" x-text="st.dni || '—'"></td>
                                    <td class="px-4 py-3 text-gray-600 text-xs" x-text="st.email"></td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold"
                                              :class="st.active_enrollments > 0 ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500'"
                                              x-text="st.active_enrollments"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Sin alumnos --}}
                <div x-show="!loading && students.length === 0" class="py-12 text-center text-sm text-gray-400">
                    No hay alumnos disponibles para esta sección.
                </div>

                {{-- Acciones --}}
                <div class="mt-6 flex justify-between">
                    <button @click="step = 1"
                            class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Anterior
                    </button>
                    <button @click="step = 3" :disabled="selectedStudentIds.length === 0"
                            class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════ PASO 3: Revisar y Confirmar ═══════════════ --}}
        <div x-show="step === 3" x-transition>
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-lg font-semibold text-gray-900">Revisar y Confirmar</h2>
                <p class="mb-6 text-sm text-gray-500">Verifica la información antes de ejecutar la matrícula masiva.</p>

                {{-- Resumen sección --}}
                <div class="mb-4 rounded-lg border border-ugarte-border bg-gray-50 p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Sección destino</h3>
                    <div class="grid grid-cols-2 gap-y-1 text-sm sm:grid-cols-3">
                        <div><span class="text-gray-500">Curso:</span> <span class="font-medium" x-text="selectedSection?.course_name"></span></div>
                        <div><span class="text-gray-500">Sección:</span> <span class="font-medium" x-text="selectedSection?.section_code"></span></div>
                        <div><span class="text-gray-500">Programa:</span> <span class="font-medium" x-text="selectedSection?.program_name"></span></div>
                        <div><span class="text-gray-500">Docente:</span> <span class="font-medium" x-text="selectedSection?.teacher_name"></span></div>
                        <div><span class="text-gray-500">Período:</span> <span class="font-medium" x-text="selectedSection?.period_name"></span></div>
                        <div><span class="text-gray-500">Vacantes:</span> <span class="font-medium" x-text="selectedSection?.available_slots"></span></div>
                    </div>
                </div>

                {{-- Warning capacidad --}}
                <div x-show="capacityWarning" class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <strong>Atención:</strong> Has seleccionado <span x-text="selectedStudentIds.length"></span> alumnos pero solo hay
                    <span x-text="selectedSection?.available_slots"></span> vacantes. Se matricularán los primeros que quepan.
                </div>

                {{-- Lista de alumnos --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">
                        Alumnos a matricular (<span x-text="selectedStudentIds.length"></span>)
                    </h3>
                    <div class="max-h-60 overflow-y-auto rounded-lg border border-ugarte-border divide-y divide-ugarte-border/50">
                        <template x-for="st in selectedStudents" :key="st.id">
                            <div class="flex items-center justify-between px-4 py-2 text-sm">
                                <span class="font-medium text-gray-900" x-text="st.name"></span>
                                <span class="text-xs text-gray-500 font-mono" x-text="st.dni || st.email"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Observaciones --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones (opcional)</label>
                    <textarea x-model="remarks" rows="2"
                        class="w-full rounded-lg border border-ugarte-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ugarte-primary/30 focus:border-ugarte-primary"
                        placeholder="Información adicional sobre este lote de matrículas…"></textarea>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-between">
                    <button @click="step = 2"
                            class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Anterior
                    </button>
                    <button @click="executeEnrollment()" :disabled="executing"
                            class="flex items-center gap-2 rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors disabled:opacity-60">
                        <svg x-show="executing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        <span x-text="executing ? 'Matriculando…' : 'Confirmar Matrícula Masiva'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════ PASO 4: Resultados ═══════════════ --}}
        <div x-show="step === 4" x-transition>
            <div class="rounded-xl border border-ugarte-border bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-gray-900">Resultado</h2>

                {{-- Matriculados --}}
                <div x-show="results?.enrolled > 0" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <span class="font-semibold" x-text="results?.enrolled"></span> alumno(s) matriculados exitosamente.
                </div>

                {{-- Omitidos --}}
                <div x-show="results?.skipped > 0" class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <p class="font-semibold mb-1"><span x-text="results?.skipped"></span> alumno(s) omitidos:</p>
                    <ul class="list-disc pl-5 space-y-0.5">
                        <template x-for="(err, i) in results?.errors || []" :key="i">
                            <li x-text="err"></li>
                        </template>
                    </ul>
                </div>

                {{-- Sin matricular --}}
                <div x-show="results?.enrolled === 0 && results?.skipped > 0" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    No se pudo matricular ningún alumno. Revisa los errores arriba.
                </div>

                {{-- Acciones --}}
                <div class="mt-6 flex gap-3">
                    <a :href="results?.redirect_url || '{{ route('admin.enrollments.index') }}'"
                       class="rounded-lg bg-ugarte-primary px-5 py-2 text-sm font-semibold text-white hover:bg-ugarte-primary/90 transition-colors">
                        Ver Matrículas
                    </a>
                    <button @click="reset()"
                            class="rounded-lg border border-ugarte-border bg-white px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Matricular otro grupo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function bulkEnrollmentWizard() {
        return {
            step: 1,
            loading: false,
            executing: false,

            // Step 1
            sections: [],
            sectionSearch: '',
            selectedSection: null,

            // Step 2
            students: [],
            studentSearch: '',
            selectedStudentIds: [],
            studentsTotal: 0,

            // Step 3
            remarks: '',
            results: null,

            get selectedStudents() {
                return this.students.filter(s => this.selectedStudentIds.includes(s.id));
            },

            get capacityWarning() {
                if (!this.selectedSection) return false;
                return this.selectedStudentIds.length > this.selectedSection.available_slots;
            },

            selectSection(section) {
                this.selectedSection = section;
            },

            toggleStudent(id) {
                const idx = this.selectedStudentIds.indexOf(id);
                idx === -1 ? this.selectedStudentIds.push(id) : this.selectedStudentIds.splice(idx, 1);
            },

            toggleAll() {
                if (this.selectedStudentIds.length === this.students.length) {
                    this.selectedStudentIds = [];
                } else {
                    this.selectedStudentIds = this.students.map(s => s.id);
                }
            },

            async fetchSections() {
                this.loading = true;
                try {
                    const params = new URLSearchParams({ search: this.sectionSearch });
                    const res = await fetch(`{{ route('admin.enrollments.bulk.sections') }}?${params}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    this.sections = await res.json();
                } catch (e) {
                    this.sections = [];
                }
                this.loading = false;
            },

            async fetchStudents() {
                this.loading = true;
                try {
                    const params = new URLSearchParams({
                        course_section_id: this.selectedSection.id,
                        search: this.studentSearch,
                    });
                    const res = await fetch(`{{ route('admin.enrollments.bulk.students') }}?${params}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.students = data.students;
                    this.studentsTotal = data.total;
                } catch (e) {
                    this.students = [];
                    this.studentsTotal = 0;
                }
                this.loading = false;
            },

            async goToStep2() {
                this.studentSearch = '';
                this.selectedStudentIds = [];
                this.step = 2;
                await this.fetchStudents();
            },

            async executeEnrollment() {
                this.executing = true;
                try {
                    const res = await fetch(`{{ route('admin.enrollments.bulk.execute') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            course_section_id: this.selectedSection.id,
                            alumno_ids: this.selectedStudentIds,
                            remarks: this.remarks,
                        }),
                    });
                    this.results = await res.json();
                    this.step = 4;
                } catch (e) {
                    this.results = { enrolled: 0, skipped: 0, errors: ['Error de conexión. Intenta de nuevo.'] };
                    this.step = 4;
                }
                this.executing = false;
            },

            reset() {
                this.step = 1;
                this.selectedSection = null;
                this.students = [];
                this.studentSearch = '';
                this.selectedStudentIds = [];
                this.remarks = '';
                this.results = null;
                this.fetchSections();
            },

            init() {
                this.fetchSections();
            }
        }
    }
    </script>
</x-layouts.app>
