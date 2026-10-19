async page => {
 const results=[];
 for(const size of [{width:1920,height:900},{width:1440,height:600},{width:390,height:844},{width:320,height:667}]){
  const p=await page.context().newPage();await p.setViewportSize(size);await p.goto('http://keysoftitalia.test/');
  await p.waitForFunction(()=>document.querySelector('#home-cinematic')?.dataset.renderer==='sequence'&&!document.documentElement.classList.contains('welcome-loading'));
  const reject=p.getByRole('button',{name:'Rifiuta',exact:true});if(await reject.isVisible())await reject.click();
  const samples=[];
  for(const progress of [.1,.60,.69,.76,.90,.69]){
   await p.evaluate(value=>{const s=document.querySelector('#home-cinematic'),stage=s.querySelector('.cinematic-stage'),h=document.querySelector('.header').getBoundingClientRect().height;scrollTo(0,s.getBoundingClientRect().top+scrollY-h+(s.offsetHeight-stage.offsetHeight)*value);},progress);
   await p.waitForTimeout(600);
   const sample=await p.evaluate(()=>{
    const s=document.querySelector('#home-cinematic'),overlay=s.querySelector('.screen-assistance'),c=s.querySelector('.cinematic-computer');
    return {frame:Number(s.dataset.frame),overlayFrame:overlay.dataset.frame,phase:overlay.dataset.phase,opacity:Number(getComputedStyle(overlay).opacity),transform:overlay.style.transform,hiddenFromAT:overlay.getAttribute('aria-hidden')==='true'&&overlay.inert,copyOpacity:Number(getComputedStyle(c.querySelector('h2')).opacity),overflow:document.documentElement.scrollWidth>innerWidth};
   });
   if(sample.overflow||!sample.hiddenFromAT||sample.transform.includes('NaN'))throw Error('Invalid overlay '+JSON.stringify({size,progress,sample}));
   if([.1,.90].includes(progress)&&sample.opacity!==0)throw Error('Overlay leaks into opening or boundary');
   if(progress===.69&&(sample.opacity<.99||sample.copyOpacity!==0))throw Error('Graphic and copy overlap');
   if(progress===.90&&sample.copyOpacity<.99)throw Error('Reading copy missing');
   if([.60,.69,.90].includes(progress))await p.screenshot({path:`artifacts/screen-assistance-${size.width}-${progress}.png`});
   samples.push({progress,...sample});
  }
  const forward=samples[2],reverse=samples.at(-1);
  if(forward.frame!==reverse.frame||forward.transform!==reverse.transform||forward.phase!==reverse.phase)throw Error('Reverse lost frame synchronization');
  await p.emulateMedia({reducedMotion:'reduce'});await p.waitForTimeout(100);
  if(await p.locator('.screen-assistance').isVisible()||!await p.locator('.cinematic-computer h2').isVisible())throw Error('Reduced motion failed');
  results.push({size,samples});await p.close();
 }
 return results;
}
