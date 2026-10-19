async page => {
 const results=[];
 for(const size of [{width:1920,height:900},{width:1440,height:600},{width:390,height:844},{width:320,height:667}]){
  const fresh=await page.context().newPage();
  await fresh.setViewportSize(size);
  await fresh.goto('http://keysoftitalia.test/');
  await fresh.waitForFunction(()=>document.querySelector('#home-cinematic')?.dataset.renderer==='sequence');
  await fresh.waitForTimeout(900);
  const entry=await fresh.evaluate(()=>{
   const s=document.querySelector('#home-cinematic'),stage=s.querySelector('.cinematic-stage');
   const top=document.querySelector('.header-top').getBoundingClientRect();
   const h=document.querySelector('.header').getBoundingClientRect();
   const p=(h.height-s.getBoundingClientRect().top)/(s.offsetHeight-stage.offsetHeight);
   const action=s.querySelector('.home-actions').getBoundingClientRect();
   const footer=s.querySelector('.cinematic-footer').getBoundingClientRect();
   return {scroll:scrollY,progress:p,frame:Number(s.dataset.frame),topbarBottom:top.bottom,headerTop:h.top,titleOpacity:Number(getComputedStyle(s.querySelector('h1')).opacity),overflow:document.documentElement.scrollWidth>innerWidth,actionBottom:action.bottom,footerTop:footer.top};
  });
  if(entry.scroll<=0||entry.topbarBottom>0||Math.abs(entry.progress-.16)>.003||entry.frame!==0||entry.titleOpacity<.95||entry.overflow)throw Error('Bad entry '+JSON.stringify({size,entry}));
  if(entry.actionBottom>entry.footerTop)throw Error('Action overlaps footer');
  await fresh.screenshot({path:`artifacts/welcome-entry-${size.width}.png`});
  await fresh.mouse.wheel(0,160);await fresh.waitForTimeout(300);
  if(await fresh.locator('#home-cinematic').evaluate(e=>Number(e.dataset.frame))<1)throw Error('Movement does not start on first scroll');
  results.push({size,entry});await fresh.close();
 }
 const anchor=await page.context().newPage();await anchor.goto('http://keysoftitalia.test/#home-services');await anchor.waitForTimeout(1000);
 if(await anchor.locator('#home-services').evaluate(e=>Math.abs(e.getBoundingClientRect().top)>250))throw Error('Entry overrode anchor');
 await anchor.close();
 const restored=await page.context().newPage();await restored.goto('http://keysoftitalia.test/');await restored.waitForTimeout(900);await restored.evaluate(()=>scrollTo(0,1100));await restored.waitForTimeout(300);await restored.reload();await restored.waitForTimeout(900);
 if(await restored.evaluate(()=>Math.abs(scrollY-1100))>5)throw Error('Entry overrode reload position');await restored.close();
 return {results,anchor:'preserved',reload:'preserved'};
}
