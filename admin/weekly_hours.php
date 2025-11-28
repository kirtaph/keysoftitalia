<?php
include_once 'includes/header.php';
?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-clock me-2 text-primary"></i>Gestione Orari</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-3 px-3">
        <ul class="nav nav-tabs card-header-tabs" id="hoursTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab"><i class="fas fa-calendar-week me-2"></i>Orario Settimanale</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exceptions-tab" data-bs-toggle="tab" data-bs-target="#exceptions" type="button" role="tab"><i class="fas fa-calendar-alt me-2"></i>Eccezioni & Chiusure</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="holidays-tab" data-bs-toggle="tab" data-bs-target="#holidays" type="button" role="tab"><i class="fas fa-gift me-2"></i>Festività Ricorrenti</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="hoursTabsContent">
            
            <!-- TAB 1: WEEKLY HOURS -->
            <div class="tab-pane fade show active" id="weekly" role="tabpanel">
                <div class="alert alert-info small mb-4">
                    <i class="fas fa-info-circle me-1"></i> Imposta gli orari standard di apertura. Usa gli switch per indicare i giorni di chiusura.
                </div>
                <form id="weeklyHoursForm">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 150px;">Giorno</th>
                                    <th>Mattina</th>
                                    <th>Pomeriggio</th>
                                </tr>
                            </thead>
                            <tbody id="weeklyGrid">
                                <!-- Generated via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" id="saveWeeklyBtn"><i class="fas fa-save me-2"></i>Salva Orari Settimanali</button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: EXCEPTIONS (CALENDAR) -->
            <div class="tab-pane fade" id="exceptions" role="tabpanel">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Legenda</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 15px; height: 15px; background-color: #198754; border-radius: 3px;" class="me-2"></div>
                                    <small>Apertura Straordinaria</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div style="width: 15px; height: 15px; background-color: #dc3545; border-radius: 3px;" class="me-2"></div>
                                    <small>Chiusura / Ferie</small>
                                </div>
                            </div>
                        </div>
                        <p class="small text-muted">Clicca su un giorno nel calendario per aggiungere o modificare una regola.</p>
                    </div>
                    <div class="col-md-9">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: HOLIDAYS -->
            <div class="tab-pane fade" id="holidays" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Festività Fisse & Mobili</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#holidayModal" id="addHolidayBtn"><i class="fas fa-plus me-1"></i>Aggiungi Festività</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Data / Regola</th>
                                <th class="text-center">Stato</th>
                                <th class="text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="holidaysTableBody">
                            <!-- Generated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Exception -->
<div class="modal fade" id="exceptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestione Eccezione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exceptionForm">
                    <input type="hidden" id="ex_id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Data</label>
                        <input type="date" class="form-control" id="ex_date" name="date" required readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo Eccezione</label>
                        <select class="form-select" id="ex_is_closed" name="is_closed">
                            <option value="1">CHIUSO (Ferie/Chiusura)</option>
                            <option value="0">APERTO (Apertura Straordinaria)</option>
                        </select>
                    </div>
                    <div id="ex_times_container" class="d-none">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Apertura</label>
                                <input type="time" class="form-control" id="ex_open_time" name="open_time">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Chiusura</label>
                                <input type="time" class="form-control" id="ex_close_time" name="close_time">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Nota (Opzionale)</label>
                        <input type="text" class="form-control" id="ex_notice" name="notice" placeholder="es. Ponte, Inventario...">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger d-none" id="deleteExceptionBtn">Elimina</button>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="saveExceptionBtn">Salva</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Holiday -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestione Festività</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="holidayForm">
                    <input type="hidden" id="hol_id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Nome Festività</label>
                        <input type="text" class="form-control" id="hol_name" name="name" required placeholder="es. Natale">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo Regola</label>
                        <select class="form-select" id="hol_rule_type" name="rule_type">
                            <option value="fixed">Data Fissa (es. 25 Dicembre)</option>
                            <option value="easter">Legata alla Pasqua (es. Pasquetta)</option>
                        </select>
                    </div>
                    
                    <!-- Fixed Date Inputs -->
                    <div id="hol_fixed_container" class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Giorno</label>
                            <input type="number" class="form-control" id="hol_day" name="day" min="1" max="31">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mese</label>
                            <select class="form-select" id="hol_month" name="month">
                                <option value="1">Gennaio</option>
                                <option value="2">Febbraio</option>
                                <option value="3">Marzo</option>
                                <option value="4">Aprile</option>
                                <option value="5">Maggio</option>
                                <option value="6">Giugno</option>
                                <option value="7">Luglio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Settembre</option>
                                <option value="10">Ottobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Dicembre</option>
                            </select>
                        </div>
                    </div>

                    <!-- Easter Offset Input -->
                    <div id="hol_easter_container" class="mb-3 d-none">
                        <label class="form-label">Giorni di distanza dalla Pasqua</label>
                        <input type="number" class="form-control" id="hol_offset_days" name="offset_days" value="1">
                        <div class="form-text">0 = Pasqua, 1 = Pasquetta, -2 = Venerdì Santo</div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="hol_is_closed" name="is_closed" value="1" checked>
                        <label class="form-check-label" for="hol_is_closed">Negozio Chiuso</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger d-none" id="deleteHolidayBtn">Elimina</button>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="saveHolidayBtn">Salva</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/it.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- WEEKLY HOURS LOGIC ---
    const daysMap = {1: 'Lunedì', 2: 'Martedì', 3: 'Mercoledì', 4: 'Giovedì', 5: 'Venerdì', 6: 'Sabato', 7: 'Domenica'};
    const weeklyGrid = document.getElementById('weeklyGrid');
    let weeklyData = [];

    function loadWeeklyHours() {
        fetch('ajax_actions/hours_actions.php?action=get_weekly')
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    weeklyData = data.data;
                    renderWeeklyGrid();
                }
            });
    }

    function renderWeeklyGrid() {
        weeklyGrid.innerHTML = '';
        // Group by DOW
        for(let d=1; d<=7; d++) {
            const daySegs = weeklyData.filter(h => h.dow == d);
            // Ensure we have 2 segments (Morning/Afternoon)
            const seg1 = daySegs.find(h => h.seg == 1) || {id: null, dow: d, seg: 1, open_time: '', close_time: '', active: 1};
            const seg2 = daySegs.find(h => h.seg == 2) || {id: null, dow: d, seg: 2, open_time: '', close_time: '', active: 1};

            const row = `
                <tr>
                    <td class="fw-bold bg-light">${daysMap[d]}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input seg-active" type="checkbox" data-dow="${d}" data-seg="1" ${seg1.active == 1 ? 'checked' : ''}>
                            </div>
                            <input type="time" class="form-control form-control-sm seg-time" data-dow="${d}" data-seg="1" data-field="open_time" value="${seg1.open_time}" ${seg1.active == 0 ? 'disabled' : ''}>
                            <span>-</span>
                            <input type="time" class="form-control form-control-sm seg-time" data-dow="${d}" data-seg="1" data-field="close_time" value="${seg1.close_time}" ${seg1.active == 0 ? 'disabled' : ''}>
                            <input type="hidden" class="seg-id" data-dow="${d}" data-seg="1" value="${seg1.id || ''}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input seg-active" type="checkbox" data-dow="${d}" data-seg="2" ${seg2.active == 1 ? 'checked' : ''}>
                            </div>
                            <input type="time" class="form-control form-control-sm seg-time" data-dow="${d}" data-seg="2" data-field="open_time" value="${seg2.open_time}" ${seg2.active == 0 ? 'disabled' : ''}>
                            <span>-</span>
                            <input type="time" class="form-control form-control-sm seg-time" data-dow="${d}" data-seg="2" data-field="close_time" value="${seg2.close_time}" ${seg2.active == 0 ? 'disabled' : ''}>
                            <input type="hidden" class="seg-id" data-dow="${d}" data-seg="2" value="${seg2.id || ''}">
                        </div>
                    </td>
                </tr>
            `;
            weeklyGrid.insertAdjacentHTML('beforeend', row);
        }

        // Add listeners for switches
        document.querySelectorAll('.seg-active').forEach(sw => {
            sw.addEventListener('change', function() {
                const dow = this.dataset.dow;
                const seg = this.dataset.seg;
                const inputs = document.querySelectorAll(`.seg-time[data-dow="${dow}"][data-seg="${seg}"]`);
                inputs.forEach(inp => inp.disabled = !this.checked);
            });
        });
    }

    document.getElementById('saveWeeklyBtn').addEventListener('click', function() {
        const hours = [];
        for(let d=1; d<=7; d++) {
            for(let s=1; s<=2; s++) {
                const active = document.querySelector(`.seg-active[data-dow="${d}"][data-seg="${s}"]`).checked ? 1 : 0;
                const open = document.querySelector(`.seg-time[data-dow="${d}"][data-seg="${s}"][data-field="open_time"]`).value;
                const close = document.querySelector(`.seg-time[data-dow="${d}"][data-seg="${s}"][data-field="close_time"]`).value;
                const id = document.querySelector(`.seg-id[data-dow="${d}"][data-seg="${s}"]`).value;
                
                hours.push({
                    id: id,
                    dow: d,
                    seg: s,
                    active: active,
                    open_time: open,
                    close_time: close
                });
            }
        }

        const formData = new FormData();
        formData.append('action', 'save_weekly');
        hours.forEach((h, index) => {
            formData.append(`hours[${index}][id]`, h.id);
            formData.append(`hours[${index}][dow]`, h.dow);
            formData.append(`hours[${index}][seg]`, h.seg);
            formData.append(`hours[${index}][active]`, h.active);
            formData.append(`hours[${index}][open_time]`, h.open_time);
            formData.append(`hours[${index}][close_time]`, h.close_time);
        });

        fetch('ajax_actions/hours_actions.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') alert('Orari salvati!');
                else alert('Errore salvataggio');
            });
    });

    loadWeeklyHours();

    // --- CALENDAR LOGIC ---
    const calendarEl = document.getElementById('calendar');
    const exceptionModal = new bootstrap.Modal(document.getElementById('exceptionModal'));
    const exIsClosed = document.getElementById('ex_is_closed');
    const exTimesContainer = document.getElementById('ex_times_container');
    const deleteExceptionBtn = document.getElementById('deleteExceptionBtn');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'it',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        events: 'ajax_actions/hours_actions.php?action=get_exceptions',
        dateClick: function(info) {
            // Add new exception
            document.getElementById('exceptionForm').reset();
            document.getElementById('ex_id').value = '';
            document.getElementById('ex_date').value = info.dateStr;
            exIsClosed.value = '1';
            exTimesContainer.classList.add('d-none');
            deleteExceptionBtn.classList.add('d-none');
            exceptionModal.show();
        },
        eventClick: function(info) {
            // Edit existing
            const props = info.event.extendedProps;
            document.getElementById('ex_id').value = info.event.id;
            document.getElementById('ex_date').value = info.event.startStr;
            exIsClosed.value = props.is_closed;
            document.getElementById('ex_open_time').value = props.open_time;
            document.getElementById('ex_close_time').value = props.close_time;
            document.getElementById('ex_notice').value = props.notice;
            
            if(props.is_closed == 0) exTimesContainer.classList.remove('d-none');
            else exTimesContainer.classList.add('d-none');

            deleteExceptionBtn.classList.remove('d-none');
            deleteExceptionBtn.dataset.id = info.event.id;
            exceptionModal.show();
        }
    });

    // Render calendar when tab is shown (fix rendering issues)
    document.getElementById('exceptions-tab').addEventListener('shown.bs.tab', function () {
        calendar.render();
    });

    exIsClosed.addEventListener('change', function() {
        if(this.value == '0') exTimesContainer.classList.remove('d-none');
        else exTimesContainer.classList.add('d-none');
    });

    document.getElementById('saveExceptionBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('exceptionForm'));
        formData.append('action', 'save_exception');
        fetch('ajax_actions/hours_actions.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    exceptionModal.hide();
                    calendar.refetchEvents();
                }
            });
    });

    deleteExceptionBtn.addEventListener('click', function() {
        if(confirm('Eliminare questa eccezione?')) {
            const formData = new FormData();
            formData.append('action', 'delete_exception');
            formData.append('id', this.dataset.id);
            fetch('ajax_actions/hours_actions.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        exceptionModal.hide();
                        calendar.refetchEvents();
                    }
                });
        }
    });

    // --- HOLIDAYS LOGIC ---
    const holidayModal = new bootstrap.Modal(document.getElementById('holidayModal'));
    const holRuleType = document.getElementById('hol_rule_type');
    const holFixedContainer = document.getElementById('hol_fixed_container');
    const holEasterContainer = document.getElementById('hol_easter_container');
    const holidaysTableBody = document.getElementById('holidaysTableBody');
    const deleteHolidayBtn = document.getElementById('deleteHolidayBtn');

    function loadHolidays() {
        fetch('ajax_actions/hours_actions.php?action=get_holidays')
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    renderHolidays(data.data);
                }
            });
    }

    function renderHolidays(holidays) {
        holidaysTableBody.innerHTML = '';
        holidays.forEach(h => {
            let ruleDesc = '';
            if(h.rule_type === 'fixed') {
                const months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
                ruleDesc = `${h.day} ${months[h.month-1]}`;
            } else {
                ruleDesc = `Pasqua ${h.offset_days >= 0 ? '+' : ''}${h.offset_days} gg`;
            }

            const row = `
                <tr>
                    <td>${h.name}</td>
                    <td>${h.rule_type === 'fixed' ? 'Data Fissa' : 'Mobile (Pasqua)'}</td>
                    <td>${ruleDesc}</td>
                    <td class="text-center">${h.is_closed == 1 ? '<span class="badge bg-danger">Chiuso</span>' : '<span class="badge bg-success">Aperto</span>'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary edit-holiday-btn" data-json='${JSON.stringify(h)}'><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
            `;
            holidaysTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    holRuleType.addEventListener('change', function() {
        if(this.value === 'fixed') {
            holFixedContainer.classList.remove('d-none');
            holEasterContainer.classList.add('d-none');
        } else {
            holFixedContainer.classList.add('d-none');
            holEasterContainer.classList.remove('d-none');
        }
    });

    document.getElementById('addHolidayBtn').addEventListener('click', function() {
        document.getElementById('holidayForm').reset();
        document.getElementById('hol_id').value = '';
        deleteHolidayBtn.classList.add('d-none');
        holRuleType.dispatchEvent(new Event('change'));
    });

    holidaysTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-holiday-btn');
        if(btn) {
            const h = JSON.parse(btn.dataset.json);
            document.getElementById('hol_id').value = h.id;
            document.getElementById('hol_name').value = h.name;
            document.getElementById('hol_rule_type').value = h.rule_type;
            document.getElementById('hol_day').value = h.day;
            document.getElementById('hol_month').value = h.month;
            document.getElementById('hol_offset_days').value = h.offset_days;
            document.getElementById('hol_is_closed').checked = h.is_closed == 1;
            
            holRuleType.dispatchEvent(new Event('change'));
            deleteHolidayBtn.classList.remove('d-none');
            deleteHolidayBtn.dataset.id = h.id;
            holidayModal.show();
        }
    });

    document.getElementById('saveHolidayBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('holidayForm'));
        formData.append('action', 'save_holiday');
        fetch('ajax_actions/hours_actions.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    holidayModal.hide();
                    loadHolidays();
                }
            });
    });

    deleteHolidayBtn.addEventListener('click', function() {
        if(confirm('Eliminare questa festività?')) {
            const formData = new FormData();
            formData.append('action', 'delete_holiday');
            formData.append('id', this.dataset.id);
            fetch('ajax_actions/hours_actions.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        holidayModal.hide();
                        loadHolidays();
                    }
                });
        }
    });

    loadHolidays();
});
</script>
