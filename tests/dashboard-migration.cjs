const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const source = fs.readFileSync(require('node:path').join(__dirname, '../admin/dashboard.php'), 'utf8');
const script = source.slice(source.indexOf('<script>') + 8, source.lastIndexOf('</script>')).replace(/<\?php[\s\S]*?\?>/g, '[]');
let handler, mode = 'error', sent, reloads = 0, confirmations = true;
const button = {textContent:'Aggiorna Ora',disabled:false,addEventListener(event,callback) { handler = callback; }};
const context = {
    document: {
        addEventListener(event,callback) { callback(); },
        getElementById(id) { if (id === 'updateDbBtn') return button; if (id === 'dbVersion') return {textContent:''}; if (id === 'migrationAlert') return {classList:{remove(){}}}; return null; }
    },
    window: {ADMIN_CSRF_TOKEN:'test-token'},
    FormData: class extends Map {},
    confirm: () => confirmations,
    alert() {},
    location: {reload() { ++reloads; }},
    fetch: async (url,options) => {
        if (!options) return {json:async () => ({status:'success',last_version:'v1',pending_count:1})};
        sent = {url, options};
        assert.equal(button.disabled,true);
        if (mode === 'network') throw new Error('offline');
        return {ok:mode==='success',json:async () => ({status:mode,message:'test'})};
    }
};
vm.runInNewContext(script, context);
(async () => {
    await handler.call(button);
    assert.equal(sent.url,'ajax_actions/migrate_action.php');
    assert.equal(sent.options.method,'POST');
    assert.equal(sent.options.body.get('action'),'execute');
    assert.equal(sent.options.body.get('csrf_token'),'test-token');
    assert.equal(button.disabled,false); assert.equal(button.textContent,'Aggiorna Ora'); assert.equal(reloads,0);
    console.log('PASS migration sends POST with CSRF and restores button on server error');
    mode = 'network'; await handler.call(button);
    assert.equal(button.disabled,false); assert.equal(reloads,0);
    console.log('PASS network error restores the migration button');
    mode = 'success'; await handler.call(button); assert.equal(reloads,1);
    console.log('PASS successful migration reloads dashboard');
    sent = null; confirmations = false; await handler.call(button); assert.equal(sent,null);
    console.log('PASS cancelling confirmation sends no request');
})().catch(error => { console.error(error);process.exitCode=1; });
