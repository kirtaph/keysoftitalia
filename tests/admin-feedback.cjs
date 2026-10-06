const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const context = {window: {}, document: {addEventListener() {}}, fetch: null};
vm.createContext(context);
vm.runInContext(fs.readFileSync(require('node:path').join(__dirname, '../assets/js/pages/admin-feedback.js'), 'utf8'), context);
const request = context.window.AdminFeedback.request;
let passed = 0;
async function check(name, run) { await run(); ++passed; console.log('PASS ' + name); }
(async () => {
    await check('success returns server data', async () => {
        context.fetch = async () => ({ok: true, status: 200, json: async () => ({status: 'success', id: 9})});
        assert.equal((await request('/test')).id, 9);
    });
    for (const [status, message] of [[401, /sessione è scaduta/], [403, /sessione è cambiata/], [422, /Dati incompleti/]]) {
        await check('HTTP ' + status + ' produces actionable feedback', async () => {
            context.fetch = async () => ({ok: false, status, json: async () => ({status: 'error', message: 'Dati incompleti'})});
            await assert.rejects(request('/test'), message);
        });
    }
    await check('network failure restores button and its icon', async () => {
        const icon = {tag: 'i'};
        const button = {disabled: false, childNodes: [icon], textContent: '', replaceChildren(...nodes) { this.childNodes = nodes; }};
        context.fetch = async () => { assert.equal(button.disabled, true); throw new Error('offline'); };
        await assert.rejects(request('/test', {}, button), /Connessione interrotta/);
        assert.equal(button.disabled, false); assert.equal(button.childNodes[0], icon);
    });
    await check('invalid JSON produces server feedback', async () => {
        context.fetch = async () => ({ok: false, status: 500, json: async () => { throw new SyntaxError(); }});
        await assert.rejects(request('/test'), /server non ha risposto/);
    });
    await check('server application error is not treated as success', async () => {
        context.fetch = async () => ({ok: true, status: 200, json: async () => ({status: 'error', message: 'Salvataggio fallito'})});
        await assert.rejects(request('/test'), /Salvataggio fallito/);
    });
    await check('disabled button prevents a duplicate request', async () => {
        context.fetch = async () => { throw new Error('must not run'); };
        await assert.rejects(request('/test', {}, {disabled: true}), /già in corso/);
    });
    console.log(passed + ' passed');
})().catch(error => { console.error(error); process.exitCode = 1; });
