async (page) => {
 const results=[];
 for(const size of [{width:1920,height:900},{width:1440,height:600},{width:390,height:844}]) {
  await page.setViewportSize(size);await page.goto('http://keysoftitalia.test/');
  await page.waitForFunction(()=>document.querySelector('#home-cinematic').dataset.renderer==='sequence');
  await page.waitForTimeout(900);
  await page.screenshot({path:`artifacts/welcome-before-${size.width}.png`});
  const cycles=[];
  for(const destination of [150,0,800,0]) {
  const samples=await page.evaluate(async(destination)=>{
   const c=document.querySelector('.cinematic-canvas'),ctx=c.getContext('2d');const values=[];
   scrollTo(0,destination);
   for(let i=0;i<35;i++){
    await new Promise(requestAnimationFrame);
    const p=ctx.getImageData(c.width>>1,c.height>>1,1,1).data;
    values.push({sample:i,rgb:[...p].slice(0,3),width:c.width,height:c.height,header:document.querySelector('.header').getBoundingClientRect().height});
   }
   return values;
  },destination);
  const blank=samples.filter(s=>s.rgb.every(v=>v===0));
  if(blank.length)throw Error(`Black canvas during resize: ${size.width}, ${destination}, ${blank.length} frames`);
  cycles.push({destination,samples:samples.length,blackFrames:blank.length});
  }
  results.push({size,cycles});
 }
 return results;
}
