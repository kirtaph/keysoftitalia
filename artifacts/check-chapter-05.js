async page=>{
 const r={frames:[],widths:[],errors:[]};page.on('pageerror',e=>r.errors.push(e.message));
 await page.emulateMedia({reducedMotion:'no-preference'});await page.setViewportSize({width:1440,height:1000});await page.goto('http://keysoftitalia.test/');await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));await page.reload();await page.evaluate(()=>document.fonts.ready);await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='sequence');await page.mouse.wheel(0,500);await page.waitForTimeout(350);
 const m=await page.evaluate(async()=>await(await fetch(document.getElementById('home-cinematic').dataset.manifest)).json());
 async function sample(id,t){const b=m.beats.find(b=>b.id===id);await page.evaluate(p=>{const s=document.getElementById('home-cinematic');scrollTo({top:s.offsetTop-document.querySelector('.header').getBoundingClientRect().height+p*(s.offsetHeight-s.querySelector('.cinematic-stage').offsetHeight),behavior:'instant'});},b.scroll[0]+(b.scroll[1]-b.scroll[0])*t);await page.waitForTimeout(200);}
 for(const [id,t,n] of [['selection',.5,815],['approach',.1,835],['approach',.5,912],['approach',.98,1003],['people',.45,1007],['approach',.5,912],['selection',.5,815]]){
  await sample(id,t);await page.waitForFunction(n=>Math.abs(Number(document.getElementById('home-cinematic').dataset.frame)-n)<=2,n);
  r.frames.push(await page.locator('#home-cinematic').evaluate(el=>({chapter:el.dataset.chapter,frame:Number(el.dataset.frame),opacity:Number(getComputedStyle(el).getPropertyValue('--story-picture-opacity'))})));
 }
 for(const [width,height] of [[320,844],[390,844],[320,667],[390,667],[768,1000],[1024,1000],[1440,1000],[1024,600]]){
  await page.setViewportSize({width,height});await sample('people',.5);await page.locator('.cinematic-people-visual img').evaluate(el=>el.decode());
  r.widths.push(await page.locator('.cinematic-people').evaluate(el=>{const rect=el.getBoundingClientRect(),stage=document.querySelector('.cinematic-stage').getBoundingClientRect(),photo=el.querySelector('figure').getBoundingClientRect();return {width:innerWidth,height:innerHeight,overflow:document.documentElement.scrollWidth>innerWidth,fits:rect.top>=stage.top-1&&rect.bottom<=stage.bottom-35,photoFits:photo.left>=0&&photo.right<=innerWidth,visible:!el.inert,filmOpacity:getComputedStyle(document.querySelector('.cinematic-canvas')).opacity};}));
  if([320,390,1440].includes(width))await page.screenshot({path:`artifacts/chapter-05-people-${width}.png`});
 }
 await page.setViewportSize({width:1440,height:1000});await sample('people',.06);const a=await page.locator('.cinematic-people-visual').evaluate(el=>getComputedStyle(el).transform);await sample('people',.3);const b=await page.locator('.cinematic-people-visual').evaluate(el=>getComputedStyle(el).transform);await sample('people',.06);const c=await page.locator('.cinematic-people-visual').evaluate(el=>getComputedStyle(el).transform);r.reversible=a===c&&a!==b;
 await sample('approach',.4);const f=Number(await page.locator('#home-cinematic').getAttribute('data-frame'));await page.mouse.wheel(0,200);await page.waitForTimeout(200);r.native=Number(await page.locator('#home-cinematic').getAttribute('data-frame'))>f;
 await page.locator('.cinematic-skip').click();r.skip=await page.evaluate(()=>document.activeElement.id==='home-services-title');
 const video=page.locator('[data-home-video]');if(await video.count()){await video.click();await page.waitForTimeout(200);r.dialog=await page.locator('#home-video-dialog').evaluate(el=>el.open);await page.keyboard.press('Escape');r.dialogClosed=await page.locator('#home-video-dialog').evaluate(el=>!el.open)&&await page.locator('#home-video-player iframe').count()===0;}
 await page.locator('.home-faq details').first().locator('summary').click();r.faq=await page.locator('.home-faq details').first().evaluate(el=>el.open);
 await page.emulateMedia({reducedMotion:'reduce'});await page.reload();r.reduced=await page.locator('.cinematic-people').evaluate(el=>!el.inert&&getComputedStyle(el).visibility==='visible'&&[...el.querySelectorAll('figure,p,h2,a')].every(c=>getComputedStyle(c).transform==='none'));
 const nc=await page.context().browser().newContext({javaScriptEnabled:false,viewport:{width:390,height:844}});const np=await nc.newPage();await np.goto('http://keysoftitalia.test/');r.nojs=await np.locator('.cinematic-people img').isVisible()&&await np.locator('.cinematic-stock-item').count()===4;await nc.close();
 await page.emulateMedia({reducedMotion:'no-preference'});
 r.passed=r.widths.every(v=>!v.overflow&&v.fits&&v.photoFits&&v.visible&&v.filmOpacity==='0')&&r.reversible&&r.native&&r.skip&&r.faq&&r.reduced&&r.nojs&&!r.errors.length&&(r.dialog===undefined||r.dialog&&r.dialogClosed);
 if(!r.passed)throw Error(JSON.stringify(r));return r;
}
