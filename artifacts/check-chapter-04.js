async page=>{
 const r={frames:[],widths:[],errors:[]};page.on('pageerror',e=>r.errors.push(e.message));
 await page.emulateMedia({reducedMotion:'no-preference'});await page.setViewportSize({width:1440,height:1000});await page.goto('http://keysoftitalia.test/');
 await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));await page.reload();await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='sequence');await page.evaluate(()=>document.fonts.ready);await page.mouse.wheel(0,600);await page.waitForTimeout(350);
 const m=await page.evaluate(async()=>await(await fetch(document.getElementById('home-cinematic').dataset.manifest)).json());
 async function sample(id,t){const b=m.beats.find(b=>b.id===id),p=b.scroll[0]+(b.scroll[1]-b.scroll[0])*t;await page.evaluate(p=>{const s=document.getElementById('home-cinematic');scrollTo({top:s.offsetTop-document.querySelector('.header').getBoundingClientRect().height+p*(s.offsetHeight-s.querySelector('.cinematic-stage').offsetHeight),behavior:'instant'});},p);await page.waitForTimeout(160);}
 for(const [id,t,expected] of [['create',.6,623],['perspective-reveal',0,624],['perspective-reveal',.5,708],['perspective',.65,815],['selection',.5,815],['perspective-reveal',.5,708],['create',.6,623]]){
  await sample(id,t);await page.waitForFunction(n=>Math.abs(Number(document.getElementById('home-cinematic').dataset.frame)-n)<3,expected);
  r.frames.push(await page.locator('#home-cinematic').evaluate(el=>({chapter:el.dataset.chapter,frame:Number(el.dataset.frame),opacity:Number(getComputedStyle(el).getPropertyValue('--story-picture-opacity'))})));
 }
 for(const width of [320,390,768,1024,1440]){
  await page.setViewportSize({width,height:width<768?844:1000});
  for(const id of ['perspective','selection']){
   await sample(id,.5);await page.waitForFunction(()=>Number(document.getElementById('home-cinematic').dataset.frame)===815);
   r.widths.push(await page.locator(`.cinematic-chapter[data-chapter=${id}]`).evaluate((el,id)=>{const rect=el.getBoundingClientRect(),stage=document.querySelector('.cinematic-stage').getBoundingClientRect(),cta=el.lastElementChild.getBoundingClientRect();return {id,width:innerWidth,overflow:document.documentElement.scrollWidth>innerWidth,fits:rect.left>=-1&&rect.right<=innerWidth+1&&rect.top>=stage.top&&rect.bottom<stage.bottom-35,mobileOverlay:innerWidth>=768||getComputedStyle(document.querySelector('.cinematic-shade')).display==='block',active:!el.inert,side:document.querySelector('.cinematic-stage').dataset.copySide};},id));
   if([320,390,1440].includes(width))await page.screenshot({path:`artifacts/chapter-04-${id}-${width}.png`});
  }
 }
 await page.setViewportSize({width:1440,height:1000});await sample('perspective-reveal',.6);const f=Number(await page.locator('#home-cinematic').getAttribute('data-frame'));await page.mouse.wheel(0,250);await page.waitForTimeout(200);r.native=Number(await page.locator('#home-cinematic').getAttribute('data-frame'))>f;
 r.products=await page.locator('.cinematic-stock-item').count();r.noDuplicateCatalogue=await page.locator('.continuum-catalogue').count()===0;
 await page.emulateMedia({reducedMotion:'reduce'});await page.reload();r.reduced=await page.locator('.cinematic-chapter[data-chapter=selection]').evaluate(el=>!el.inert&&getComputedStyle(el).visibility==='visible'&&getComputedStyle(el).transform==='none');
 await page.emulateMedia({reducedMotion:'no-preference'});
 const c=await page.context().browser().newContext();await c.route('**/story-manifest.json*',route=>route.abort());const p=await c.newPage();await p.goto('http://keysoftitalia.test/');await p.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='poster');r.fallback=await p.locator('.cinematic-stock-item').first().evaluate(el=>!el.closest('[inert]'));await c.close();
 r.passed=r.widths.every(v=>!v.overflow&&v.fits&&v.mobileOverlay&&v.active&&v.side==='right')&&r.native&&r.reduced&&r.fallback&&r.noDuplicateCatalogue&&!r.errors.length;
 if(!r.passed)throw new Error(JSON.stringify(r));return r;
}
