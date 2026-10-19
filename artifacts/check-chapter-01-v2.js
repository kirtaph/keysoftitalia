async (page) => {
 const results=[];
 for(const [width,height] of [[1440,1000],[390,844],[320,667]]) {
  await page.setViewportSize({width,height});
  await page.goto('http://keysoftitalia.test/');
  await page.waitForFunction(()=>document.querySelector('#home-cinematic')?.dataset.renderer==='sequence');
  const refuse=page.getByRole('button',{name:'Rifiuta',exact:true});
  if(await refuse.isVisible())await refuse.click();
  await page.waitForTimeout(1000);
  const dimensions=await page.evaluate(()=>{const s=document.querySelector('#home-cinematic'),stage=s.querySelector('.cinematic-stage');return {top:s.getBoundingClientRect().top+scrollY,header:document.querySelector('.header').getBoundingClientRect().height,distance:s.offsetHeight-stage.offsetHeight};});
  for(const progress of [0,.25,.6,1,.2,0]) {
   await page.evaluate(({d,p})=>scrollTo(0,Math.max(0,d.top-d.header+d.distance*p)),{d:dimensions,p:progress});
   await page.waitForTimeout(650);
   const state=await page.evaluate(()=>{const s=document.querySelector('#home-cinematic'),h=s.querySelector('h1'),c=s.querySelector('canvas'),a=s.querySelector('.home-actions');return {frame:Number(s.dataset.frame),beat:s.dataset.chapter,canvas:[c.width,c.height],titleOpacity:Number(getComputedStyle(h).opacity),actionInert:a.inert,overflow:document.documentElement.scrollWidth>innerWidth};});
   if(state.overflow)throw Error(`Overflow ${width}`);
   if(progress===0&&state.frame!==0)throw Error('Opening not restored');
   if(progress===.25&&state.titleOpacity>.4)throw Error('Copy must leave with the camera');
   if(progress===1&&state.frame!==239)throw Error('Actual final frame not reached');
   if(progress===.6&&(state.frame<70||!state.actionInert))throw Error('Scroll or hidden controls failed');
   results.push({width,height,progress,...state});
   if(width!==320&&[0,.25,.6].includes(progress)) await page.screenshot({path:`artifacts/chapter-01-v2-${width}-${progress}.png`});
  }
  await page.locator('.cinematic-skip').click();
  if(await page.locator('#home-services-title').evaluate(e=>document.activeElement!==e)) throw Error('Skip focus');
 }
 await page.emulateMedia({reducedMotion:'reduce'});
 await page.reload();
 await page.waitForFunction(()=>document.querySelector('#home-cinematic').dataset.renderer==='poster');
 if(!await page.locator('#home-title').isVisible()||await page.locator('.cinematic-chapter').evaluate(e=>e.inert))throw Error('Reduced motion fallback');
 results.push({reducedMotion:'readable poster'});
 await page.emulateMedia({reducedMotion:'no-preference'});
 await page.setViewportSize({width:1440,height:1000});
 await page.goto('http://keysoftitalia.test/');
 return results;
}
