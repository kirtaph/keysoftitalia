(() => {
    if (!['telefonia.php', 'forniture.php', 'flyers.php', 'videos.php', 'team.php'].includes(location.pathname.split('/').pop())) return;
    document.addEventListener('DOMContentLoaded', () => {
        const lists = {promoTableBody: 'offerte', requestsTableBody: 'richieste', partnerTableBody: 'partner', flyersTableBody: 'volantini', videosTableBody: 'video', membersTableBody: 'membri'};
        for (const [id, label] of Object.entries(lists)) {
            const body = document.getElementById(id);
            if (!body) continue;
            const container = body.closest('.table-responsive') || body.closest('table');
            const toolbar = document.createElement('div');
            toolbar.className = 'd-flex flex-wrap gap-3 align-items-end px-3 py-3';
            const searchLabel = document.createElement('label'); searchLabel.className = 'flex-grow-1';
            searchLabel.textContent = `Cerca ${label}`;
            const search = document.createElement('input'); search.type = 'search'; search.className = 'form-control mt-1'; search.placeholder = 'Cerca in questo elenco';
            searchLabel.append(search);
            const sizeLabel = document.createElement('label'); sizeLabel.textContent = 'Per pagina';
            const size = document.createElement('select'); size.className = 'form-select mt-1';
            for (const n of [25, 50, 100]) { const option = document.createElement('option'); option.value = n; option.textContent = n; size.append(option); }
            sizeLabel.append(size); toolbar.append(searchLabel, sizeLabel); container.before(toolbar);
            const footer = document.createElement('div'); footer.className = 'd-flex flex-wrap gap-3 align-items-center justify-content-between px-3 py-3';
            const count = document.createElement('span'); count.className = 'small'; count.setAttribute('role', 'status');
            const nav = document.createElement('nav'); nav.setAttribute('aria-label', `Pagine elenco ${label}`); nav.className = 'd-flex align-items-center gap-2';
            const previous = document.createElement('button'); previous.type = 'button'; previous.className = 'btn btn-sm btn-outline-secondary'; previous.textContent = 'Precedente';
            const position = document.createElement('span'); position.className = 'small';
            const next = previous.cloneNode(true); next.textContent = 'Successiva'; nav.append(previous, position, next);
            footer.append(count, nav); container.after(footer);
            let page = 1, scheduled = false;
            const observer = new MutationObserver(() => {
                if (!scheduled) { scheduled = true; requestAnimationFrame(() => { scheduled = false; render(); }); }
            });
            function text(row) {
                return Array.from(row.cells).slice(0, -1).map(cell => {
                    const clone = cell.cloneNode(true);
                    const selected = Array.from(cell.querySelectorAll('select')).map(select => select.selectedOptions[0]?.textContent || '').join(' ');
                    clone.querySelectorAll('button, select, input').forEach(el => el.remove());
                    return clone.textContent + ' ' + selected;
                }).join(' ').toLocaleLowerCase('it');
            }
            function render() {
                observer.disconnect();
                body.querySelectorAll('[data-admin-empty]').forEach(row => row.remove());
                const rows = Array.from(body.rows).filter(row => row.cells.length > 1);
                const term = search.value.trim().toLocaleLowerCase('it');
                const matches = rows.filter(row => text(row).includes(term));
                const perPage = Number(size.value), pages = Math.max(1, Math.ceil(matches.length / perPage));
                page = Math.min(page, pages);
                const visible = new Set(matches.slice((page - 1) * perPage, page * perPage));
                rows.forEach(row => row.hidden = !visible.has(row));
                if (rows.length && !matches.length) {
                    const row = body.insertRow(); row.dataset.adminEmpty = 'true';
                    const cell = row.insertCell(); cell.colSpan = rows[0].cells.length; cell.className = 'text-center py-4';
                    cell.textContent = 'Nessun risultato. Prova un altro termine o cancella la ricerca.';
                }
                count.textContent = `${matches.length ? (page - 1) * perPage + 1 : 0}–${Math.min(page * perPage, matches.length)} di ${matches.length} risultati`;
                position.textContent = `Pagina ${page} di ${pages}`;
                previous.disabled = page === 1; next.disabled = page === pages; nav.classList.toggle('d-none', pages === 1);
                observer.observe(body, {childList: true, subtree: true});
            }
            search.addEventListener('input', () => { page = 1; render(); }); size.addEventListener('change', () => { page = 1; render(); });
            previous.addEventListener('click', () => { --page; render(); }); next.addEventListener('click', () => { ++page; render(); });
            body.addEventListener('change', render);
            render();
        }
    });
})();
