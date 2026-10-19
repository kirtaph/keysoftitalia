async page => {
 const broken=await page.context().newPage();
 await broken.route('**/cinematic-v2/manifest.json*',route=>route.fulfill({status:404,body:''}));
 await broken.goto('http://keysoftitalia.test/');
 await broken.waitForFunction(()=>document.documentElement.classList.contains('welcome-ready'));
 if(!await broken.locator('.cinematic-computer h2').isVisible()||await broken.locator('.welcome-loader').isVisible())throw Error('Manifest failure lost content');
 await broken.close();
 const context=await page.context().browser().newContext({javaScriptEnabled:false,viewport:{width:390,height:844}});
 const plain=await context.newPage();await plain.goto('http://keysoftitalia.test/');
 if(!await plain.locator('.cinematic-computer h2').isVisible()||await plain.locator('.welcome-loader').isVisible())throw Error('No JS lost content');
 await context.close();
 return {missingManifest:'both chapters readable',noJS:'both chapters readable'};
}
