import { chromium } from 'playwright';

const BASE = 'http://localhost:8000';
let pass = 0, fail = 0;

function ok(label) { console.log(`  ✅ ${label}`); pass++; }
function ko(label, detail = '') { console.log(`  ❌ ${label}${detail ? ': ' + detail : ''}`); fail++; }

async function login(page, email, password) {
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
}

async function check(label, condition, detail = '') {
    if (condition) ok(label);
    else ko(label, detail);
}

(async () => {
    const browser = await chromium.launch({ headless: true });
    const ctx = await browser.newContext();
    const page = await ctx.newPage();

    // ─── ADMIN FLOWS ─────────────────────────────────────────
    console.log('\n📋 Admin — Sesiones y Programación');
    await login(page, 'admin@ugarte.edu.pe', 'password');
    const adminUrl = page.url();
    await check('Admin login → /admin', adminUrl.includes('/admin'));

    // Admin class-sessions list
    await page.goto(`${BASE}/admin/class-sessions`);
    await page.waitForLoadState('networkidle');
    const csStatus = page.url();
    await check('Admin class-sessions page loads (no 500)', !csStatus.includes('500') && csStatus.includes('/admin/class-sessions'));

    // Check that sessions exist in DB (page has some content)
    const sessionRows = await page.locator('table tbody tr').count().catch(() => 0);
    await check('Admin class-sessions table has rows (seeder ran)', sessionRows > 0, `rows=${sessionRows}`);

    // Session generator index
    await page.goto(`${BASE}/admin/session-generator`);
    await page.waitForLoadState('networkidle');
    const genUrl = page.url();
    await check('Session generator page loads', genUrl.includes('/admin/session-generator'), genUrl);

    // Check form has section select
    const sectionSelect = await page.locator('select[name="course_section_id"]').count();
    await check('Generator form has section select', sectionSelect > 0);

    // Schedules for first section
    await page.goto(`${BASE}/admin/class-sessions`);
    await page.waitForLoadState('networkidle');

    // ─── COURSE SECTIONS → SCHEDULES ─────────────────────────
    console.log('\n📋 Admin — Horarios (Schedules)');
    await page.goto(`${BASE}/admin/course-sections`);
    await page.waitForLoadState('networkidle');
    const firstSectionHref = await page.locator('table tbody tr:first-child a').first().getAttribute('href').catch(() => null);

    if (firstSectionHref) {
        // Extract ID from href like /admin/course-sections/1/edit
        const sectionId = firstSectionHref.match(/course-sections\/(\d+)/)?.[1];
        if (sectionId) {
            await page.goto(`${BASE}/admin/course-sections/${sectionId}/schedules`);
            await page.waitForLoadState('networkidle');
            const schedUrl = page.url();
            await check('Course section schedules page loads', schedUrl.includes('/schedules'), schedUrl);

            const schedRows = await page.locator('table tbody tr').count().catch(() => 0);
            await check('Schedules table has rows (seeder)', schedRows > 0, `rows=${schedRows}`);
        } else {
            ko('Could not extract section ID', firstSectionHref);
        }
    } else {
        ko('No course section links found in table');
    }

    // ─── DOCENTE FLOWS ────────────────────────────────────────
    console.log('\n📋 Docente — Mis Clases');
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', 'carlos.rios@ugarte.edu.pe');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
    await check('Docente login → /docente', page.url().includes('/docente'));

    // Docente class-sessions index
    await page.goto(`${BASE}/docente/class-sessions`);
    await page.waitForLoadState('networkidle');
    const docenteCSUrl = page.url();
    await check('Docente class-sessions page loads', docenteCSUrl.includes('/docente/class-sessions'), docenteCSUrl);

    // Check sidebar has "Mis Clases"
    const misClasesLink = await page.locator('a:has-text("Mis Clases")').count();
    await check('Sidebar has "Mis Clases" link', misClasesLink > 0);

    // Dashboard Hoy section (docente dashboard has today's sessions logic)
    await page.goto(`${BASE}/docente`);
    await page.waitForLoadState('networkidle');
    const docenteDashText = await page.content();
    const hasTodaySection = docenteDashText.includes('Hoy') || docenteDashText.includes('clases programadas');
    await check('Docente dashboard loads with today section', hasTodaySection);

    // Find a session to view
    await page.goto(`${BASE}/docente/class-sessions`);
    await page.waitForLoadState('networkidle');
    const sessionLink = await page.locator('a[href*="/docente/class-sessions/"]').first().getAttribute('href').catch(() => null);

    if (sessionLink) {
        await page.goto(`${BASE}${sessionLink}`);
        await page.waitForLoadState('networkidle');
        const sessionUrl = page.url();
        await check('Docente can view a class session', sessionUrl.includes('/docente/class-sessions/'), sessionUrl);

        const attendanceForm = await page.locator('form[action*="attendance"]').count();
        await check('Session show has attendance form', attendanceForm > 0);

        // Check for arrival_time inputs
        const arrivalInputs = await page.locator('input[name*="arrival_time"]').count();
        await check('Attendance form has arrival_time inputs', arrivalInputs > 0, `count=${arrivalInputs}`);
    } else {
        ko('No docente class session links found');
        ko('Docente session show page (skipped)');
        ko('Attendance form present (skipped)');
        ko('arrival_time inputs (skipped)');
    }

    // ─── ALUMNO FLOWS ─────────────────────────────────────────
    console.log('\n📋 Alumno — Calendario');
    await page.goto(`${BASE}/login`);

    // Find an alumno user
    const alumnoUsers = ['alumno01@test.com', 'test1@test.com'];
    // Try to find an active alumno by going to login and trying credentials
    await page.fill('input[name="email"]', 'alumno01@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    let alumnoLoggedIn = page.url().includes('/alumno');

    if (!alumnoLoggedIn) {
        // Try to get a real alumno from admin area
        await login(page, 'admin@ugarte.edu.pe', 'password');
        await page.goto(`${BASE}/admin/users?role=alumno`);
        await page.waitForLoadState('networkidle');
        const firstAlumnoEmail = await page.locator('table tbody tr:first-child td:nth-child(3)').textContent().catch(() => null);

        if (firstAlumnoEmail) {
            await page.goto(`${BASE}/login`);
            await page.fill('input[name="email"]', firstAlumnoEmail.trim());
            await page.fill('input[name="password"]', 'password');
            await page.click('button[type="submit"]');
            await page.waitForLoadState('networkidle');
            alumnoLoggedIn = page.url().includes('/alumno');
        }
    }

    await check('Alumno login succeeds', alumnoLoggedIn, page.url());

    if (alumnoLoggedIn) {
        // Alumno dashboard
        await check('Alumno dashboard URL correct', page.url().includes('/alumno'));

        // Check for proxima-clase component
        const dashContent = await page.content();
        const hasProximaClase = dashContent.includes('Próxima') || dashContent.includes('clase') || dashContent.includes('Calendario');
        await check('Alumno dashboard has calendar/proxima-clase content', hasProximaClase);

        // Calendar page
        await page.goto(`${BASE}/alumno/calendar`);
        await page.waitForLoadState('networkidle');
        const calUrl = page.url();
        await check('Alumno calendar page loads', calUrl.includes('/alumno/calendar'), calUrl);

        // Check calendar component is present
        const calContent = await page.content();
        const hasCalendar = calContent.includes('erpCalendar') || calContent.includes('calendar') || calContent.includes('mes') || calContent.includes('Calendar');
        await check('Calendar component present in page', hasCalendar);

        // Sidebar has Calendario link
        const calLink = await page.locator('a:has-text("Calendario")').count();
        await check('Alumno sidebar has "Calendario" link', calLink > 0);

        // Calendar JSON endpoint
        const apiResp = await page.request.get(`${BASE}/alumno/calendar/sessions?month=2026-06`);
        await check('Calendar /sessions endpoint returns 200', apiResp.status() === 200, `status=${apiResp.status()}`);

        const body = await apiResp.json().catch(() => null);
        await check('Calendar endpoint returns JSON array', Array.isArray(body), `type=${typeof body}`);

        if (Array.isArray(body) && body.length > 0) {
            const event = body[0];
            const hasFields = 'starts_at' in event && 'is_today' in event && 'can_join' in event;
            await check('Calendar events have required fields (is_today, can_join)', hasFields, JSON.stringify(Object.keys(event)));
        }
    } else {
        ko('Alumno dashboard URL (skipped)');
        ko('Alumno dashboard content (skipped)');
        ko('Alumno calendar page (skipped)');
        ko('Calendar component (skipped)');
        ko('Alumno sidebar Calendario (skipped)');
        ko('Calendar sessions endpoint (skipped)');
        ko('Calendar JSON format (skipped)');
        ko('Calendar event fields (skipped)');
    }

    // ─── DATA INTEGRITY ───────────────────────────────────────
    console.log('\n📋 Data Integrity');
    await login(page, 'admin@ugarte.edu.pe', 'password');

    // Check class_sessions count via admin page (should have many from seeder)
    await page.goto(`${BASE}/admin/class-sessions`);
    await page.waitForLoadState('networkidle');
    const totalSessions = await page.locator('table tbody tr').count().catch(() => 0);
    await check('Seeder created many class sessions (>10)', totalSessions > 10, `visible rows=${totalSessions}`);

    // Check that meetings exist (seeder creates them for past sessions)
    const meetingText = await page.content();
    const hasMeetingCol = meetingText.includes('Zoom') || meetingText.includes('zoom') || meetingText.includes('Meet');
    await check('Class session list shows meeting platform info', hasMeetingCol);

    await browser.close();

    // ─── SUMMARY ─────────────────────────────────────────────
    console.log(`\n${'─'.repeat(50)}`);
    console.log(`✅ ${pass} passed · ❌ ${fail} failed · Total ${pass + fail}`);
    if (fail > 0) process.exit(1);
})();
