async page => {
  const errors=[];
  page.on('pageerror',e=>errors.push(e.message));
  await page.setViewportSize({width:1440,height:1000});
  await page.emulateMedia({reducedMotion:'no-preference'});
  await page.goto('http://keysoftitalia.test/');
  await page.waitForFunction(()=>document.getElementById('spatial-stage').dataset.renderer==='webgl');
  const frames=()=>page.locator('#spatial-stage').getAttribute('data-frames').then(Number);
  const result={renderer:await page.locator('#spatial-stage').getAttribute('data-renderer')};
  const first=await page.locator('#home-3d-canvas').screenshot();
  const f1=await frames();
  await page.waitForTimeout(750);
  const second=await page.locator('#home-3d-canvas').screenshot();
  result.animatedFrames=(await frames())-f1;
  result.pixelsChanged=Buffer.compare(first,second)!==0;
  await page.locator('#spatial-pause').click();
  const paused=await frames();await page.waitForTimeout(500);
  result.pauseStopsFrames=(await frames())===paused;
  result.scenes=[];
  for(const name of ['repair','create','choose']) {
    await page.locator('button[data-scene='+name+']').click();
    result.scenes.push({name,rendered:await page.locator('#spatial-stage').getAttribute('data-scene'),pressed:await page.locator('button[data-scene='+name+']').getAttribute('aria-pressed')});
    await page.screenshot({path:'artifacts/home-3d-'+name+'.png'});
  }
  await page.locator('#spatial-pause').click();
  await page.locator('.home-story').scrollIntoViewIfNeeded();
  await page.waitForTimeout(200);const offscreen=await frames();await page.waitForTimeout(500);
  result.offscreenStopsFrames=(await frames())===offscreen;
  await page.evaluate(()=>scrollTo(0,0));
  await page.emulateMedia({reducedMotion:'reduce'});
  await page.waitForTimeout(200);const reduced=await frames();await page.waitForTimeout(500);
  result.reducedMotionStopsFrames=(await frames())===reduced;
  await page.locator('button[data-scene=repair]').click();
  result.reducedMotionControlsWork=await page.locator('#spatial-stage').getAttribute('data-scene')==='repair';
  await page.emulateMedia({reducedMotion:'no-preference'});
  result.breakpoints=[];
  for(const width of [320,390,768,1024,1440]) {
    await page.setViewportSize({width,height:1000});
    await page.evaluate(()=>scrollTo({top:0,behavior:'instant'}));
    result.breakpoints.push(await page.evaluate(()=>({width:innerWidth,overflow:document.documentElement.scrollWidth>innerWidth,titleFits:document.querySelector('h1').scrollWidth<=document.querySelector('h1').clientWidth+1,canvasWidth:document.querySelector('canvas').clientWidth,mobileSceneFirst:innerWidth>=768||document.querySelector('.spatial-stage').getBoundingClientRect().top<document.querySelector('h1').getBoundingClientRect().top})));
  }
  const fallbackContext=await page.context().browser().newContext();
  await fallbackContext.addInitScript(()=>{
    const original=HTMLCanvasElement.prototype.getContext;
    HTMLCanvasElement.prototype.getContext=function(type,...args){return type.includes('webgl')?null:original.call(this,type,...args);};
  });
  const fallbackPage=await fallbackContext.newPage();
  await fallbackPage.goto('http://keysoftitalia.test/');
  await fallbackPage.waitForFunction(()=>document.getElementById('spatial-stage').dataset.renderer==='fallback');
  result.webglFallback=await fallbackPage.evaluate(()=>({imageVisible:getComputedStyle(document.querySelector('.spatial-fallback')).visibility==='visible',controlsHidden:getComputedStyle(document.querySelector('.spatial-controls')).display==='none'}));
  await fallbackContext.close();
  result.errors=errors;
  result.passed=result.animatedFrames>1&&result.pixelsChanged&&result.pauseStopsFrames&&result.offscreenStopsFrames&&result.reducedMotionStopsFrames&&result.reducedMotionControlsWork&&result.scenes.every(s=>s.name===s.rendered&&s.pressed==='true')&&result.breakpoints.every(b=>!b.overflow&&b.titleFits&&b.canvasWidth>0&&b.mobileSceneFirst)&&result.webglFallback.imageVisible&&result.webglFallback.controlsHidden&&!errors.length;
  if(!result.passed)throw new Error(JSON.stringify(result));
  return result;
}
