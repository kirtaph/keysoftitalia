async (page) => {
 await page.setViewportSize({width:390,height:844});
 await page.goto('http://keysoftitalia.test/');
 await page.waitForFunction(()=>document.querySelector('#home-cinematic').dataset.renderer==='sequence');
 const menu=page.locator('.menu-toggle');
 if(await menu.isVisible()) {
  await menu.click();
  await page.waitForTimeout(300);
  if(await menu.getAttribute('aria-expanded')!=='true')throw Error('Menu does not open');
  await page.keyboard.press('Escape');
  if(await menu.getAttribute('aria-expanded')!=='false')throw Error('Menu does not close');
 }
 await page.route('**/chapter-01/manifest.json*',route=>route.abort());
 await page.reload();
 await page.waitForFunction(()=>document.querySelector('#home-cinematic').dataset.renderer==='poster');
 if(!await page.locator('#home-title').isVisible())throw Error('Failed media hides content');
 await page.unroute('**/chapter-01/manifest.json*');
 const context=await page.context().browser().newContext({javaScriptEnabled:false,viewport:{width:390,height:844}});
 const staticPage=await context.newPage();
 await staticPage.goto('http://keysoftitalia.test/');
 if(!await staticPage.locator('#home-title').isVisible())throw Error('No JS hides title');
 const source=await staticPage.locator('.cinematic-poster').evaluate(e=>e.currentSrc);
 if(!source.includes('/mobile/'))throw Error('Static mobile poster incorrect');
 await context.close();
 await page.setViewportSize({width:1440,height:1000});
 await page.goto('http://keysoftitalia.test/');
 return {mobileMenu:'opens and closes',mediaFailure:'readable',noJavaScript:'readable portrait poster'};
}
