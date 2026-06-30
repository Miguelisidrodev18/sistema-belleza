<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AcademicPeriodController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseSectionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\BulkEnrollmentController;
use App\Http\Controllers\Admin\ReEnrollmentController;
use App\Http\Controllers\Admin\ClassSessionController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SessionGeneratorController;
use App\Http\Controllers\Admin\LandingSettingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Docente\DashboardController as DocenteDashboardController;
use App\Http\Controllers\Docente\ProfileController as DocenteProfileController;
use App\Http\Controllers\Docente\SectionController as DocenteSectionController;
use App\Http\Controllers\Docente\ClassSessionController as DocenteClassSessionController;
use App\Http\Controllers\Alumno\DashboardController as AlumnoDashboardController;
use App\Http\Controllers\Alumno\EnrollmentController as AlumnoEnrollmentController;
use App\Http\Controllers\Alumno\ProfileController as AlumnoProfileController;
use App\Http\Controllers\Alumno\CalendarController as AlumnoCalendarController;
use App\Http\Controllers\Alumno\ClassSessionController as AlumnoClassSessionController;
use App\Http\Controllers\Alumno\SectionController as AlumnoSectionController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Docente\MaterialController as DocenteMaterialController;
use App\Http\Controllers\Alumno\MaterialController as AlumnoMaterialController;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'active', 'active-period', 'password-changed'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes
    Route::middleware('role:administrador')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::get('api/dni-lookup', [AdminUserController::class, 'dniLookup'])->name('api.dni-lookup');

            Route::get('change-password', [AdminProfileController::class, 'showChangePassword'])->name('change-password');
            Route::post('change-password', [AdminProfileController::class, 'changePassword'])->name('change-password.update');

            Route::resource('users', AdminUserController::class);
            Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');

            // Fase 2 — Períodos Académicos
            Route::resource('academic-periods', AcademicPeriodController::class)->except('show');
            Route::post('academic-periods/{academic_period}/set-current', [AcademicPeriodController::class, 'setCurrent'])
                ->name('academic-periods.set-current');

            // Fase 2 — Programas + Cursos (nested)
            Route::resource('programs', ProgramController::class);
            Route::resource('programs.courses', CourseController::class)
                ->except(['index', 'show'])
                ->scoped();

            // Fase 2 — Secciones Académicas
            Route::resource('course-sections', CourseSectionController::class)->except('show');
            Route::get('course-sections/{courseSection}', [CourseSectionController::class, 'show'])
                ->name('course-sections.show');

            // Fase 3 — Matrículas
            Route::get('enrollments/bulk', [BulkEnrollmentController::class, 'index'])->name('enrollments.bulk');
            Route::get('enrollments/bulk/sections', [BulkEnrollmentController::class, 'sections'])->name('enrollments.bulk.sections');
            Route::get('enrollments/bulk/students', [BulkEnrollmentController::class, 'students'])->name('enrollments.bulk.students');
            Route::post('enrollments/bulk/execute', [BulkEnrollmentController::class, 'execute'])->name('enrollments.bulk.execute');
            Route::resource('enrollments', EnrollmentController::class)->except('show');
            Route::get('re-enrollment', [ReEnrollmentController::class, 'index'])->name('re-enrollment.index');
            Route::post('re-enrollment/preview', [ReEnrollmentController::class, 'preview'])->name('re-enrollment.preview');
            Route::post('re-enrollment/execute', [ReEnrollmentController::class, 'execute'])->name('re-enrollment.execute');

            // Fase 4 — Programación Académica
            Route::get('course-sections/{courseSection}/schedules', [ScheduleController::class, 'index'])
                ->name('course-sections.schedules.index');
            Route::resource('course-sections.schedules', ScheduleController::class)
                ->except(['index', 'show'])->scoped();

            Route::resource('class-sessions', ClassSessionController::class)->except('show');

            // Fase 5 — Materiales (Admin)
            Route::post('course-sections/{courseSection}/materials', [AdminMaterialController::class, 'store'])
                ->name('course-sections.materials.store');
            Route::delete('course-sections/{courseSection}/materials/{material}', [AdminMaterialController::class, 'destroy'])
                ->name('course-sections.materials.destroy');
            Route::post('class-sessions/{classSession}/materials', [AdminMaterialController::class, 'storeSession'])
                ->name('class-sessions.materials.store');
            Route::delete('class-sessions/{classSession}/materials/{material}', [AdminMaterialController::class, 'destroySession'])
                ->name('class-sessions.materials.destroy');

            Route::get('session-generator', [SessionGeneratorController::class, 'index'])
                ->name('session-generator.index');
            Route::post('session-generator/preview', [SessionGeneratorController::class, 'preview'])
                ->name('session-generator.preview');
            Route::post('session-generator/simulate', [SessionGeneratorController::class, 'simulate'])
                ->name('session-generator.simulate');
            Route::post('session-generator/generate', [SessionGeneratorController::class, 'generate'])
                ->name('session-generator.generate');

            // Apariencia de la Landing
            Route::get('site-settings/landing', [LandingSettingController::class, 'edit'])
                ->name('site-settings.landing.edit');
            Route::put('site-settings/landing/hero', [LandingSettingController::class, 'updateHero'])
                ->name('site-settings.landing.hero.update');
            Route::put('site-settings/landing/about', [LandingSettingController::class, 'updateAbout'])
                ->name('site-settings.landing.about.update');
            Route::put('site-settings/landing/gallery', [LandingSettingController::class, 'updateGallery'])
                ->name('site-settings.landing.gallery.update');
            Route::delete('site-settings/landing/media/{key}', [LandingSettingController::class, 'removeMedia'])
                ->name('site-settings.landing.remove-media');

            // Galería
            Route::post('gallery/{category}', [GalleryController::class, 'store'])->name('gallery.store');
            Route::delete('gallery/{galleryImage}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        });

    // Docente routes
    Route::middleware('role:docente')
        ->prefix('docente')
        ->name('docente.')
        ->group(function () {
            Route::get('/', [DocenteDashboardController::class, 'index'])->name('dashboard');

            Route::get('change-password', [DocenteProfileController::class, 'showChangePassword'])->name('change-password');
            Route::post('change-password', [DocenteProfileController::class, 'changePassword'])->name('change-password.update');

            Route::get('profile', [DocenteProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [DocenteProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [DocenteProfileController::class, 'updatePassword'])->name('profile.password');

            // Fase 3 — Mis Secciones
            Route::get('sections', [DocenteSectionController::class, 'index'])->name('sections.index');
            Route::get('sections/{courseSection}/students', [DocenteSectionController::class, 'students'])->name('sections.students');

            // Fase 5 — Materiales (Docente)
            Route::post('class-sessions/{classSession}/materials', [DocenteMaterialController::class, 'store'])
                ->name('class-sessions.materials.store');
            Route::delete('class-sessions/{classSession}/materials/{material}', [DocenteMaterialController::class, 'destroy'])
                ->name('class-sessions.materials.destroy');

            // Fase 4 — Mis Clases
            Route::get('class-sessions', [DocenteClassSessionController::class, 'index'])->name('class-sessions.index');
            Route::get('class-sessions/{classSession}', [DocenteClassSessionController::class, 'show'])->name('class-sessions.show');
            Route::put('class-sessions/{classSession}', [DocenteClassSessionController::class, 'update'])->name('class-sessions.update');
            Route::post('class-sessions/{classSession}/attendance', [DocenteClassSessionController::class, 'attendance'])->name('class-sessions.attendance');
        });

    // Alumno routes
    Route::middleware('role:alumno')
        ->prefix('alumno')
        ->name('alumno.')
        ->group(function () {
            Route::get('/', [AlumnoDashboardController::class, 'index'])->name('dashboard');

            Route::get('change-password', [AlumnoProfileController::class, 'showChangePassword'])->name('change-password');
            Route::post('change-password', [AlumnoProfileController::class, 'changePassword'])->name('change-password.update');

            Route::get('profile', [AlumnoProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [AlumnoProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [AlumnoProfileController::class, 'updatePassword'])->name('profile.password');

            // Fase 3 — Mis Matrículas
            Route::get('enrollments', [AlumnoEnrollmentController::class, 'index'])->name('enrollments.index');

            // Fase 4 — Calendario
            Route::get('calendar', [AlumnoCalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/sessions', [AlumnoCalendarController::class, 'sessions'])->name('calendar.sessions');

            // Fase 5 — Materiales (Alumno)
            Route::get('materials/{materialAttachment}/download', [AlumnoMaterialController::class, 'download'])
                ->name('materials.download');
            Route::get('sections/{courseSection}/materials', [AlumnoMaterialController::class, 'sectionMaterials'])
                ->name('sections.materials');

            // Fase 5.1 — Experiencia del Alumno
            Route::get('class-sessions/{classSession}', [AlumnoClassSessionController::class, 'show'])
                ->name('class-sessions.show');
            Route::get('sections/{courseSection}', [AlumnoSectionController::class, 'show'])
                ->name('sections.show');
        });
});
