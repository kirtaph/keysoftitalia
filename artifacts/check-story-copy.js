async page=>{
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 await page.setViewportSize({width:1440,height:1000});await page.goto('http://keysoftitalia.test/');
 await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));await page.reload();
 await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='sequence');
 await page.evaluate(()=>document.fonts.ready);await page.waitForTimeout(100);
 await page.mouse.wheel(0,600);await page.waitForTimeout(400);
 const manifest=await page.evaluate(async()=>await(await fetch(document.getElementById('home-cinematic').dataset.manifest)).json());
 const r={chapters:[]};
 async function sample(id,offset){
  const beat=manifest.beats.find(b=>b.id===id),p=beat.scroll[0]+offset/manifest.activeViewportHeights;
  await page.evaluate(p=>{const s=document.getElementById('home-cinematic');scrollTo({top:s.offsetTop-document.querySelector('.header').getBoundingClientRect().height+p*(s.offsetHeight-s.querySelector('.cinematic-stage').offsetHeight),behavior:'instant'});},p);
  await page.waitForTimeout(80);
  return await page.locator(`.cinematic-chapter[data-chapter=${id}]`).evaluate(el=>{const h=el.querySelector('h1,h2'),action=el.querySelector('.home-actions')||el.querySelector('a');return{heading:Number(getComputedStyle(h).opacity),y:new DOMMatrix(getComputedStyle(h).transform).m42,action:Number(getComputedStyle(action).opacity),controlsInert:action.inert,parentInert:el.inert};});
 }
 for(const id of ['care','restart','choose','create']){
  const partial=await sample(id,.06),arriving=await sample(id,.12),landed=await sample(id,.30),reversed=await sample(id,.06);
  r.chapters.push({id,partial,arriving,landed,reversed});
  if(!(partial.heading>0&&partial.heading<1&&arriving.heading>partial.heading&&partial.y>arriving.y&&landed.heading>.99&&!landed.controlsInert&&Math.abs(partial.heading-reversed.heading)<.015))throw new Error(JSON.stringify(r));
 }
 await sample('create',.3);await page.screenshot({path:'artifacts/story-copy-desktop.png'});
 await page.setViewportSize({width:390,height:844});await sample('create',.3);await page.screenshot({path:'artifacts/story-copy-mobile.png'});
 await page.emulateMedia({reducedMotion:'reduce'});await page.reload();await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='poster');
 r.reduced=await page.locator('.cinematic-chapter :is(h1,h2)').evaluateAll(els=>els.every(el=>getComputedStyle(el).opacity==='1'&&!el.inert&&getComputedStyle(el).transform==='none'));
 r.errors=errors;r.passed=r.reduced&&!errors.length;if(!r.passed)throw new Error(JSON.stringify(r));return r;
}
