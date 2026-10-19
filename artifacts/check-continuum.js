async page => {
 await page.setViewportSize({width:1440,height:1000});
 await page.goto('http://keysoftitalia.test/');
 await page.evaluate(()=>localStorage.setItem('ksoft_consent_v2',JSON.stringify({state:'denied',ts:Date.now()})));
 await page.reload();
 const results={};
 async function shot(selector,path){
  await page.locator(selector).evaluate(el=>scrollTo({top:scrollY+el.getBoundingClientRect().top-100,behavior:'instant'}));
  await page.locator(selector+' img').evaluateAll(async images=>{await Promise.all(images.map(im=>im.decode().catch(()=>{})));});
  await page.waitForTimeout(250);
  await page.screenshot({path});
 }
 await shot('#home-services','artifacts/continuum-services-final.png');
 for(const [index,expected] of [[1,'hero-ricondizionati.webp'],[2,'hero-it.webp'],[0,'frame-0120.webp']]){
  await page.locator('.continuum-service').nth(index).focus();
  await page.waitForFunction(expected=>document.getElementById('continuum-service-image').src.includes(expected),expected);
 }
 results.preview=await page.locator('#continuum-service-image').evaluate(el=>({loaded:el.complete&&el.naturalWidth>0,opacity:getComputedStyle(el).opacity}));
 await shot('.continuum-catalogue','artifacts/continuum-catalogue-final.png');
 results.products=await page.locator('.continuum-product-info h3').evaluateAll(els=>els.map(el=>({title:el.innerText,opacity:getComputedStyle(el).opacity})));
 await shot('.continuum-story','artifacts/continuum-story-final.png');
 await shot('.continuum-contact','artifacts/continuum-contact-final.png');
 await page.setViewportSize({width:390,height:844});
 await shot('#home-services','artifacts/continuum-services-mobile.png');
 await shot('.continuum-catalogue','artifacts/continuum-catalogue-mobile.png');
 await shot('.continuum-contact','artifacts/continuum-contact-mobile.png');
 return results;
}
