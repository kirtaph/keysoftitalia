async page=>{
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 await page.setViewportSize({width:1440,height:1000});await page.goto('http://keysoftitalia.test/');
 await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));await page.reload();
 await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='sequence');
 const r={frames:[],widths:[]};
 async function position(p){await page.evaluate(p=>{const s=document.getElementById('home-cinematic'),stage=s.querySelector('.cinematic-stage');scrollTo({top:s.offsetTop-document.querySelector('.header').getBoundingClientRect().height+p*(s.offsetHeight-stage.offsetHeight),behavior:'instant'});},p);}
 for(const [p,expected] of [[0,0],[.36*5.5/7.9,81],[.62*5.5/7.9,143],[.96*5.5/7.9,239],[5.6/7.9,240],[.82,322],[.98,431],[.82,322],[.62*5.5/7.9,143]]){
  await position(p*7.9/10.3);await page.waitForFunction(expected=>Math.abs(Number(document.getElementById('home-cinematic').dataset.frame)-expected)<=2,expected);
  r.frames.push(await page.locator('#home-cinematic').evaluate(el=>({frame:Number(el.dataset.frame),chapter:el.dataset.chapter,pictureOpacity:Number(getComputedStyle(el).getPropertyValue('--story-picture-opacity'))})));
 }
 for(const width of [320,390,768,1024,1440]){
  await page.setViewportSize({width,height:width<768?844:1000});await position(.98*7.9/10.3);
  await page.waitForFunction(()=>Number(document.getElementById('home-cinematic').dataset.frame)===431);
  r.widths.push(await page.evaluate(()=>{const c=document.querySelector('.cinematic-chapter[data-chapter=choose]'),h=c.querySelector('h2').getBoundingClientRect();return{width:innerWidth,overflow:document.documentElement.scrollWidth>innerWidth,titleFits:h.left>=0&&h.right<=innerWidth,active:!c.inert,canvasOpacity:getComputedStyle(document.querySelector('.cinematic-canvas')).opacity};}));
  if(width===1440||width===390)await page.screenshot({path:`artifacts/chapter-02-${width}.png`});
 }
 await page.emulateMedia({reducedMotion:'reduce'});await page.reload();await page.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='poster');
 r.reduced=await page.locator('.cinematic-chapter[data-chapter=choose]').evaluate(el=>({visible:!el.inert&&getComputedStyle(el).visibility==='visible',posterVisible:getComputedStyle(el.querySelector('img')).display!=='none'}));
 await page.emulateMedia({reducedMotion:'no-preference'});
 const fallback=await page.context().browser().newContext();await fallback.route('**/story-manifest.json*',route=>route.abort());const fp=await fallback.newPage();await fp.goto('http://keysoftitalia.test/');await fp.waitForFunction(()=>document.getElementById('home-cinematic').dataset.renderer==='poster');r.failure=await fp.locator('.cinematic-chapter[data-chapter=choose]').evaluate(el=>!el.inert);await fallback.close();
 r.errors=errors;r.passed=r.widths.every(w=>!w.overflow&&w.titleFits&&w.active&&w.canvasOpacity==='1')&&r.reduced.visible&&r.reduced.posterVisible&&r.failure&&!errors.length;
 if(!r.passed)throw new Error(JSON.stringify(r));return r;
}

