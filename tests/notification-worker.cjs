const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const handlers = {}, shown = [], opened = [];
const scope = 'https://keysoftitalia.it/admin/';
vm.runInNewContext(fs.readFileSync('admin/notification-worker.js','utf8'), {
    URL,
    self: {addEventListener:(name,handler)=>handlers[name]=handler,skipWaiting:async()=>{},registration:{scope,showNotification:async(title,options)=>shown.push({title,options})}},
    clients: {claim:async()=>{},matchAll:async()=>[],openWindow:async(url)=>opened.push(url)}
});
async function push(payload) {
    let pending;
    handlers.push({data:{json:()=>payload},waitUntil:p=>pending=p});
    await pending;return shown.at(-1);
}
(async()=>{
    const event = await push({title:'Valutazione usato',body:'Nuova richiesta',url:'used_quotes.php?q=22&open=22',tag:'ksi-request-22'});
    assert.equal(event.title,'Valutazione usato');
    assert.equal(event.options.data.url,scope+'used_quotes.php?q=22&open=22');
    let pending;
    handlers.notificationclick({notification:{data:event.options.data,close(){}},waitUntil:p=>pending=p});
    await pending;assert.equal(opened[0],event.options.data.url);
    for (const url of ['https://evil.test/admin/','../','/admin-other/']) {
        assert.equal((await push({url})).options.data.url,scope+'notifications.php');
    }
    assert.equal((await push(null)).title,'Nuove richieste sul sito');
    assert.equal((await push({title:'Notifica di prova'})).title,'Notifica di prova');
    console.log('PASS request text, direct click, unsafe URL fallbacks, missing payload and test notification');
})().catch(error=>{console.error(error);process.exitCode=1;});
