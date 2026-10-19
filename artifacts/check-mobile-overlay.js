async page=>{
 const r={views:[],errors:[]};page.on('pageerror',e=>r.errors.push(e.message));
 await page.addInitScript(()=>{const draw=CanvasRenderingContext2D.prototype.drawImage;CanvasRenderingContext2D.prototype.drawImage=function(...args){if(this.canvas.classList.contains('cinematic-canvas'))this.canvas.dataset.imageBounds=JSON.stringify({x:args[1],y:args[2],width:args[3],height:args[4]});return draw.apply(this,args);};});
 await page.emulateMedia({reducedMotion:'no-preference'});await page.goto('http://keysoftitalia.test/');await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));await page.reload();await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='sequence');await page.evaluate(()=>document.fonts.ready);
 const m=await page.evaluate(async()=>await(await fetch(document.getElementById('home-cinematic').dataset.manifest)).json());
 async function sample(id,t){const b=m.beats.find(b=>b.id===id);await page.evaluate(p=>{const s=document.getElementById('home-cinematic');scrollTo({top:s.offsetTop-document.querySelector('.header').getBoundingClientRect().height+p*(s.offsetHeight-s.querySelector('.cinematic-stage').offsetHeight),behavior:'instant'});},b.scroll[0]+(b.scroll[1]-b.scroll[0])*t);await page.waitForTimeout(250);}
 for(const [width,height] of [[320,667],[390,844],[430,932],[1440,1000]]){
  await page.setViewportSize({width,height});
  for(const id of ['opening','care','create','perspective','selection']){
   await sample(id,.55);
   r.views.push(await page.locator('.cinematic-canvas').evaluate((canvas,id)=>{const b=JSON.parse(canvas.dataset.imageBounds),stage=canvas.getBoundingClientRect(),copy=document.querySelector(`.cinematic-chapter[data-chapter=${id}]`);return {id,width:innerWidth,cover:b.x<=1&&b.y<=1&&b.x+b.width>=canvas.width-1&&b.y+b.height>=canvas.height-1,overlay:copy.getBoundingClientRect().top>=stage.top&&copy.getBoundingClientRect().bottom<=stage.bottom,shade:getComputedStyle(document.querySelector('.cinematic-shade')).display!=='none'&&Number(getComputedStyle(document.querySelector('.cinematic-shade')).opacity)>.9,active:!copy.inert,overflow:document.documentElement.scrollWidth>innerWidth};},id));
   if(width===390&&['opening','perspective'].includes(id))await page.screenshot({path:`artifacts/mobile-overlay-${id}.png`});
  }
 }
 await page.setViewportSize({width:390,height:844});await sample('perspective-reveal',.4);await page.mouse.wheel(0,220);await page.waitForTimeout(200);const f=Number(await page.locator('#home-cinematic').getAttribute('data-frame'));await page.mouse.wheel(0,-220);await page.waitForTimeout(200);r.reverse=Number(await page.locator('#home-cinematic').getAttribute('data-frame'))<f;
 await page.locator('.menu-toggle').click();r.menu=await page.locator('.menu-toggle').getAttribute('aria-expanded')==='true';await page.keyboard.press('Escape');r.menuClosed=await page.locator('.menu-toggle').getAttribute('aria-expanded')==='false';
 await page.emulateMedia({reducedMotion:'reduce'});await page.reload();r.reduced=await page.locator('#home-cinematic').evaluate(el=>el.dataset.renderer==='poster'&&!el.classList.contains('cinematic-enhanced'));
 r.passed=r.views.every(v=>v.cover&&v.overlay&&v.active&&!v.overflow&&(v.width>=768||v.shade))&&r.reverse&&r.menu&&r.menuClosed&&r.reduced&&!r.errors.length;
 await page.emulateMedia({reducedMotion:'no-preference'});if(!r.passed)throw Error(JSON.stringify(r));return r;
}
