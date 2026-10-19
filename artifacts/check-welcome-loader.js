async page => {
 const results=[];
 for(const viewport of [{width:1440,height:900},{width:390,height:844}]) {
  const fresh=await page.context().newPage();await fresh.setViewportSize(viewport);
  await fresh.route('**/sequence/frame-0000.webp',async route=>{await new Promise(r=>setTimeout(r,1000));await route.continue();});
  await fresh.goto('http://keysoftitalia.test/',{waitUntil:'domcontentloaded'});
  await fresh.waitForTimeout(150);
  if(!await fresh.locator('.welcome-loader').isVisible())throw Error('Spinner missing during delayed first frame');
  if(await fresh.locator('.header').evaluate(e=>getComputedStyle(e).visibility)!=='hidden')throw Error('Unsettled header visible');
  await fresh.screenshot({path:`artifacts/welcome-loader-${viewport.width}.png`});
  await fresh.waitForFunction(()=>document.documentElement.classList.contains('welcome-ready'));
  await fresh.waitForTimeout(800);
  if(await fresh.locator('.welcome-loader').isVisible())throw Error('Loader remains after ready');
  const state=await fresh.evaluate(()=>{const s=document.querySelector('#home-cinematic'),c=s.querySelector('canvas');return {renderer:s.dataset.renderer,frame:Number(s.dataset.frame),scroll:scrollY,canvasVisible:getComputedStyle(c).visibility,headReady:document.querySelector('.header').getBoundingClientRect().top===0};});
  if(state.renderer!=='sequence'||state.canvasVisible!=='visible'||state.scroll===0||!state.headReady)throw Error('Scene not positioned before reveal');
  results.push({viewport,...state});await fresh.close();
 }
 const broken=await page.context().newPage();await broken.route('**/sequence/frame-0000.webp',route=>route.fulfill({status:404,body:''}));await broken.goto('http://keysoftitalia.test/');await broken.waitForFunction(()=>document.documentElement.classList.contains('welcome-ready'));if(await broken.locator('.welcome-loader').isVisible())throw Error('Missing frame leaves spinner');if(!await broken.locator('#home-title').isVisible())throw Error('Missing frame hides content');await broken.close();
 const missingScript=await page.context().newPage();await missingScript.route('**/home-cinematic-v2.js*',route=>route.abort());await missingScript.goto('http://keysoftitalia.test/');await missingScript.waitForFunction(()=>document.documentElement.classList.contains('welcome-ready'),null,{timeout:11000});if(await missingScript.locator('.welcome-loader').isVisible())throw Error('Missing JS leaves spinner');if(!await missingScript.locator('#home-title').isVisible())throw Error('Watchdog hides fallback');await missingScript.close();
 return {delayedMedia:results,missingMedia:'readable fallback',missingRenderer:'watchdog releases spinner'};
}
