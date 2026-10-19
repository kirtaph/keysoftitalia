async page => {
 const results=[];
 for(const size of [{width:1920,height:900},{width:1440,height:600},{width:390,height:844},{width:320,height:667}]){
  const fresh=await page.context().newPage();await fresh.setViewportSize(size);
  await fresh.goto('http://keysoftitalia.test/');
  await fresh.waitForFunction(()=>document.querySelector('#home-cinematic')?.dataset.renderer==='sequence'&&!document.documentElement.classList.contains('welcome-loading'));
  await fresh.waitForTimeout(850);
  const start=await fresh.evaluate(()=>({scroll:scrollY,frame:Number(document.querySelector('#home-cinematic').dataset.frame),bar:document.querySelector('.header-top').getBoundingClientRect().bottom}));
  if(start.scroll<=0||start.frame!==0||start.bar>0)throw Error('Bad opening '+JSON.stringify(start));
  const samples=[];
  for(const progress of [.46,.48,.50,.60,.72,.9,1,.60,.10]){
   await fresh.evaluate(p=>{const s=document.querySelector('#home-cinematic'),st=s.querySelector('.cinematic-stage'),h=document.querySelector('.header').getBoundingClientRect().height;scrollTo(0,s.getBoundingClientRect().top+scrollY-h+(s.offsetHeight-st.offsetHeight)*p);},progress);
   await fresh.waitForTimeout(600);
   const sample=await fresh.evaluate(()=>{
    const s=document.querySelector('#home-cinematic'),c=s.querySelector('.cinematic-computer'),a=c.querySelector('.home-actions'),f=s.querySelector('.cinematic-footer');
    return {frame:Number(s.dataset.frame),chapter:s.dataset.chapter,opacity:Number(getComputedStyle(c.querySelector('h2')).opacity),inert:c.inert,actionBottom:a.getBoundingClientRect().bottom,footerTop:f.getBoundingClientRect().top,overflow:document.documentElement.scrollWidth>innerWidth,canvas:getComputedStyle(s.querySelector('canvas')).visibility};
   });
   if(sample.overflow||sample.canvas!=='visible')throw Error('Bad scene '+JSON.stringify({size,progress,sample}));
   if(progress===.9&&(sample.frame!==431||sample.inert||sample.opacity<.99||sample.actionBottom>sample.footerTop))throw Error('Bad computer copy '+JSON.stringify({size,progress,sample}));
   if(progress===.50&&!sample.inert)throw Error('Computer controls available too early');
   if(progress===.9)await fresh.screenshot({path:`artifacts/chapter-02-integrated-${size.width}.png`});
   samples.push({progress,...sample});
  }
  await fresh.locator('.cinematic-skip').click();await fresh.waitForTimeout(100);
  if(await fresh.evaluate(()=>document.activeElement?.id)!=='home-services-title')throw Error('Skip lost focus');
  results.push({size,start,samples});await fresh.close();
 }
 const fallback=await page.context().newPage();await fallback.emulateMedia({reducedMotion:'reduce'});await fallback.goto('http://keysoftitalia.test/');await fallback.waitForTimeout(700);
 if(!await fallback.locator('.cinematic-computer h2').isVisible()||await fallback.evaluate(()=>scrollY)!==0)throw Error('Reduced motion lost copy');await fallback.close();
 return {results,reducedMotion:'readable'};
}
