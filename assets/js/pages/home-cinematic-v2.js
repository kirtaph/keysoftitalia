// Native scroll owns a bounded, reversible timeline. Text always remains HTML.
// The method shown on the notebook display is composited into the chapter-02 frames.
const section=document.getElementById('home-cinematic');
if(section) initializeStory();
async function initializeStory(){
  const stage=section.querySelector('.cinematic-stage');
  const canvas=section.querySelector('canvas');
  const shade=section.querySelector('.cinematic-shade');
  const outroLayer=section.querySelector('.cinematic-outro');
  const footer=section.querySelector('.cinematic-footer');
  const meter=section.querySelector('[role=progressbar]');
  const skip=section.querySelector('.cinematic-skip');
  const chapterLinks=[...section.querySelectorAll('.cinematic-chapters [data-goto]')].map(el=>({el,from:+el.dataset.from,to:+el.dataset.to,goto:+el.dataset.goto}));
  const ctx=canvas.getContext('2d',{alpha:false});
  const reduced=matchMedia('(prefers-reduced-motion: reduce)');
  const chapters=[...section.querySelectorAll('[data-chapter]')];
  const memory=section.querySelector('.founders-memory');
  const memoryState={};
  let memoryFx=null,memoryFxAttempted=false;
  const flyer=section.querySelector('.cinematic-flyer');
  let flyerFx=null,flyerAttempted=false,flyerProgress=0;
  function loadFlyer(){
    if(flyerAttempted||!flyer||reduced.matches||matchMedia('(max-width:767px)').matches)return;
    flyerAttempted=true;
    import('./home-flyer.js').then(m=>m.createFlyer(flyer)).then(fx=>{
      if(!active||reduced.matches){fx.dispose();return;}
      flyerFx=fx;flyer.dataset.renderer='three';fx.draw(flyerProgress);
    }).catch(()=>{flyer.dataset.renderer='image';});
  }
  function loadMemoryBlend(){
    if(memoryFxAttempted||!memory||reduced.matches)return;
    memoryFxAttempted=true;
    import('./home-founders-blend.js').then(m=>m.createFoundersBlend(memory)).then(fx=>{
      if(!active||reduced.matches){fx.dispose();return;}
      memoryFx=fx;memory.dataset.effect='three';
      fx.draw(Number(memoryState.blend||0),Number(memoryState.color||0));
    }).catch(()=>{memory.dataset.effect='css';});
  }
  // Per-unit state remembers the last written values, so scrolling writes only real changes.
  const copy=chapters.map(element=>({element,id:element.dataset.chapter,timing:null,state:{},units:[...element.querySelector('.welcome-intro').children].map(unit=>({unit,controls:unit.matches('a,button')||!!unit.querySelector('a,button'),side:unit.dataset.motionSide||element.dataset.copySide,state:{}}))}));
  const header=document.querySelector('.header');
  const back=document.getElementById('backToTop');
  const connection=navigator.connection||{};
  const frugal=!!connection.saveData||/(^|-)2g$|3g/.test(connection.effectiveType||'');
  // Compressed frames are cheap to keep; decoded bitmaps are not (≈4MB each).
  const blobs=new Map(),bitmaps=new Map(),pending=new Map(),decoding=new Map(),failed=new Map();
  const MAX_BLOBS=frugal?90:260,MAX_BITMAPS=12,MAX_REQUESTS=6;
  let manifest,base,pattern,extension='webp',epoch=0,target=0,drawn=-1,progress=0,raf=0,active=false,chapter='',direction=1;
  let cssWidth=0,cssHeight=0,headerHeight=0,distance=1,stageHeight=0,narrow=false;
  let inView=null,copySide='',meterValue=-1,outro=-1,engaged=false,currentLink=null;
  let entryPending=!reduced.matches;
  if(document.documentElement.classList.contains('welcome-loading'))document.getElementById('main-content')?.setAttribute('aria-busy','true');
  function finishBoot(){
    document.getElementById('main-content')?.setAttribute('aria-busy','false');
    if(window.__ksWelcomeBoot)window.__ksWelcomeBoot.finish();
    else document.documentElement.classList.add('welcome-ready');
  }
  function showFallback(){fallback();finishBoot();}
  addEventListener('welcome-boot-timeout',showFallback,{once:true});
  function release(){epoch++;pending.forEach(p=>p.controller.abort());pending.clear();bitmaps.forEach(b=>b.close());bitmaps.clear();blobs.clear();decoding.clear();drawn=-1;canvas.style.visibility='hidden';}
  function fallback(){
    flyerFx?.dispose();flyerFx=null;flyerAttempted=false;
    active=false;inView=null;document.body.classList.remove('welcome-in-view');if(back)back.inert=false;
    section.classList.remove('cinematic-enhanced');section.dataset.renderer='poster';release();
    chapters.forEach(c=>{c.inert=false;c.removeAttribute('aria-hidden');});
    copy.forEach(({state,units})=>{for(const k in state)delete state[k];units.forEach(({unit,state})=>{for(const k in state)delete state[k];unit.style.removeProperty('opacity');unit.style.removeProperty('transform');unit.style.removeProperty('pointer-events');unit.inert=false;});});
    if(outroLayer)outroLayer.style.removeProperty('opacity');if(footer)footer.style.removeProperty('opacity');
    if(memory){
      memoryFx?.dispose();memoryFx=null;
      ['--memory-color','--memory-old','--memory-now','--memory-scale','--memory-lift','--memory-year-offset'].forEach(name=>memory.style.removeProperty(name));
      memory.querySelector('.founders-year').textContent='2026';
      memory.querySelector('.founders-monochrome').setAttribute('aria-hidden','true');
      memory.querySelector('.founders-present').removeAttribute('aria-hidden');
      for(const key in memoryState)delete memoryState[key];
    }
    skip.hidden=true;
  }
  // Sample the shared cubic-bezier(.23,1,.32,1) once; scroll updates interpolate.
  const easing=Array.from({length:101},(_,i)=>{
    const x=i/100;let low=0,high=1;
    for(let n=0;n<14;n++){const t=(low+high)/2,u=1-t;const at=3*u*u*t*.23+3*u*t*t*.32+t*t*t;if(at<x)low=t;else high=t;}
    return 1-Math.pow(1-(low+high)/2,3);
  });
  const ease=value=>{if(value<=0)return 0;if(value>=1)return 1;const index=value*100,a=Math.floor(index);return easing[a]+(easing[a+1]-easing[a])*(index-a);};
  const clamp=value=>Math.max(0,Math.min(1,value));
  function set(state,key,value,write){if(state[key]!==value){state[key]=value;write(value);}}
  // Chapter copy timing lives in the manifest (scroll positions, never timers):
  // visible = window in which the chapter owns the stage, minFrame = wait for the
  // painted film, enter/exit = staggered scroll ranges. Reversal is exact.
  const DEFAULT_COPY={
    opening:{visible:[0,.17],exit:{at:.08,span:.06,stagger:.007}},
    computer:{visible:[.80,1],minFrame:390,side:'right',enter:{at:.80,span:.07,stagger:.010}},
  };
  function timings(){copy.forEach(chapter=>{chapter.timing=(manifest.copy&&manifest.copy[chapter.id])||DEFAULT_COPY[chapter.id]||{visible:[2,2]};});}
  const ramp=(range,position,index)=>range?ease((position-(range.at+index*(range.stagger||0)))/range.span):null;
  function composeCopy(position){
    if(flyer&&manifest.copy.choosing){
      const entry=manifest.copy.choosing.enter;
      flyerProgress=clamp((position-entry.at)/entry.span);
      if(position>entry.at-.06&&position<manifest.copy.choosing.visible[1])loadFlyer();
      flyerFx?.draw(flyerProgress);
    }
    let backdrop=0,side='left';
    const lateral=narrow?90:240;
    copy.forEach(({element,timing,state:chapterState,units})=>{
      const [from,to]=timing.visible;
      const current=position>=from&&(position<to||to>=1)&&drawn>=(timing.minFrame||0);
      if(current)side=timing.side||'left';
      let readable=false;
      units.forEach(({unit,controls,side:unitSide,state},index)=>{
        const enter=current?(ramp(timing.enter,position,index)??1):0;
        const exit=current?(ramp(timing.exit,position,index)??0):0;
        const opacity=enter*(1-exit);
        if(current)backdrop=Math.max(backdrop,opacity);
        const shift=(1-enter)*(index===1?44:28)-exit*22;
        // Enter from the quiet side; return there on exit, leaving the film clear.
        const travel=element.dataset.chapter==='people'?(narrow?24:64):lateral;
        const shiftX=((unitSide||timing.side)==='right'?1:-1)*((1-enter)*travel+exit*travel*.65);
        set(state,'opacity',opacity.toFixed(3),v=>unit.style.opacity=v);
        set(state,'transform',`translate3d(${shiftX.toFixed(1)}px,${shift.toFixed(1)}px,0)`,v=>unit.style.transform=v);
        const interactive=current&&opacity>.9;
        set(state,'inert',controls?!interactive:(!current||opacity<.05),v=>unit.inert=v);
        set(state,'pointer',interactive?'':'none',v=>unit.style.pointerEvents=v);
        readable ||= opacity>.05;
      });
      const hidden=!current||!readable;
      set(chapterState,'hidden',hidden,v=>{element.inert=v;element.setAttribute('aria-hidden',String(v));});
      set(chapterState,'current',current,v=>element.classList.toggle('is-current',v));
    });
    if(side!==copySide){copySide=side;stage.dataset.copySide=side;}
    if(shade)set(shade,'_opacity',backdrop.toFixed(3),v=>shade.style.opacity=v);
    if(memory&&manifest.copy.people){
      const [from,to]=manifest.copy.people.visible;
      const recollection=clamp((position-from)/(to-from));
      if(position>from-.1)loadMemoryBlend();
      const color=clamp((recollection-.12)/.24);
      const blend=clamp((recollection-.4)/.4);
      const smooth=(a,b,x)=>{const t=clamp((x-a)/(b-a));return t*t*(3-2*t);};
      set(memoryState,'blend',blend.toFixed(4),()=>{});
      set(memoryState,'old',(1-smooth(.04,.47,blend)).toFixed(3),v=>memory.style.setProperty('--memory-old',v));
      set(memoryState,'now',smooth(.53,.96,blend).toFixed(3),v=>memory.style.setProperty('--memory-now',v));
      const year=2004+Math.floor(22*clamp((recollection-.32)/.48));
      set(memoryState,'date',year,v=>{memory.querySelector('.founders-year').textContent=String(v);});
      set(memoryState,'accessible',blend>.5,v=>{
        memory.querySelector('.founders-monochrome').setAttribute('aria-hidden',String(v));
        memory.querySelector('.founders-present').setAttribute('aria-hidden',String(!v));
      });
      set(memoryState,'color',(color*color*(3-2*color)).toFixed(3),v=>memory.style.setProperty('--memory-color',v));
      set(memoryState,'scale',(1.025-.025*recollection).toFixed(4),v=>memory.style.setProperty('--memory-scale',v));
      set(memoryState,'lift',`${(12*(1-recollection)).toFixed(1)}px`,v=>memory.style.setProperty('--memory-lift',v));
      set(memoryState,'year',`${(-12*recollection).toFixed(1)}px`,v=>memory.style.setProperty('--memory-year-offset',v));
      memoryFx?.draw(blend,Number(memoryState.color));
    }
  }
  function filename(index){
    const source=manifest.sources.find(source=>index>=source.start&&index<=source.end);
    return new URL(source[pattern].replace('{index}',String(index-source.start).padStart(4,'0')).replace(/\.webp$/,'.'+extension)+(manifest.frameVersion?'?v='+encodeURIComponent(manifest.frameVersion):''),base).href;
  }
  // Network: fetch compressed frames by priority. Decoding happens only near the target.
  async function fetchFrame(index){
    const generation=epoch,controller=new AbortController();pending.set(index,{controller});
    try{
      const response=await fetch(filename(index),{signal:controller.signal,cache:'force-cache'});
      if(!response.ok)throw new Error('Missing frame');
      const blob=await response.blob();
      if(generation!==epoch)return;
      blobs.set(index,blob);trimBlobs();
      if(Math.abs(index-target)<=8)schedule();
    }catch(error){if(error.name!=='AbortError'&&generation===epoch)failed.set(index,(failed.get(index)||0)+1);}
    finally{if(pending.get(index)?.controller===controller)pending.delete(index);if(generation===epoch&&active)pump();}
  }
  function decode(index){
    if(decoding.has(index))return decoding.get(index);
    if(bitmaps.has(index)||!blobs.has(index))return Promise.resolve();
    const generation=epoch;
    const job=createImageBitmap(blobs.get(index)).then(bitmap=>{
      if(generation!==epoch){bitmap.close();return;}
      bitmaps.set(index,bitmap);trimBitmaps();schedule();
    },()=>{failed.set(index,2);blobs.delete(index);}).finally(()=>decoding.delete(index));
    decoding.set(index,job);return job;
  }
  function farthest(map,limit,dispose){
    while(map.size>limit){let far=-1,gap=-1;map.forEach((_,i)=>{const d=Math.abs(i-target)+(Math.sign(i-target)===-direction?.5:0);if(d>gap){far=i;gap=d;}});dispose?.(map.get(far));map.delete(far);}
  }
  const trimBlobs=()=>farthest(blobs,MAX_BLOBS);
  const trimBitmaps=()=>farthest(bitmaps,MAX_BITMAPS,b=>b.close());
  const usable=index=>index>=0&&index<manifest.frameCount&&(failed.get(index)||0)<2;
  function priorities(){
    // 1) the exact frame, 2) a short run ahead, 3) a sparse keyframe layer so a fast
    // scrub always finds a near neighbour, 4) the full film in the background.
    const list=[target],step=frugal?8:6,ahead=direction;
    for(let n=1;n<=6;n++)list.push(target+ahead*n);
    for(let n=1;n<=2;n++)list.push(target-ahead*n);
    const key=Math.round(target/step)*step;
    for(let n=0;n<=10;n++)list.push(key+ahead*n*step);
    for(let n=1;n<=5;n++)list.push(key-ahead*n*step);
    for(let n=7;n<=18;n++)list.push(target+ahead*n);
    // The whole-film layers wait for real scrolling: a visitor who stays on the opening downloads little.
    if(!frugal&&engaged){for(let k=0;k<manifest.frameCount;k+=step)list.push(k);for(let n=19;n<=60;n++)list.push(target+ahead*n);}
    return list;
  }
  function pump(){
    if(!active||document.hidden)return;
    // Abandon remote positions after a fast jump.
    pending.forEach((p,i)=>{if(Math.abs(i-target)>90&&i%6){p.controller.abort();pending.delete(i);}});
    for(const index of priorities()){
      if(pending.size>=MAX_REQUESTS)break;
      // Near frames always load (eviction makes room); background layers stop when full.
      if(Math.abs(index-target)>18&&blobs.size>=MAX_BLOBS-8)continue;
      if(usable(index)&&!blobs.has(index)&&!pending.has(index))fetchFrame(index);
    }
    // Decode the target first; when it is missing, the nearest compressed neighbour.
    if(blobs.has(target))decode(target);
    else{let best=-1,gap=Infinity;blobs.forEach((_,i)=>{const d=Math.abs(i-target);if(d<gap){best=i;gap=d;}});if(best>=0&&!bitmaps.has(best))decode(best);}
    for(let n=1;n<=2;n++)if(blobs.has(target+direction*n))decode(target+direction*n);
  }
  function draw(){
    let index=target;
    if(!bitmaps.has(index)){let gap=Infinity;index=undefined;bitmaps.forEach((_,i)=>{const d=Math.abs(i-target);if(d<gap){index=i;gap=d;}});}
    if(index===undefined||index===drawn)return;
    const bitmap=bitmaps.get(index);if(!bitmap)return;
    ctx.fillStyle='#101114';ctx.fillRect(0,0,canvas.width,canvas.height);
    const source=manifest.sources.find(source=>index>=source.start&&index<=source.end);
    // Frames shot on a plain page-graphite background may be drawn smaller than cover:
    // the empty margins melt into the page and leave room for the copy.
    const shrink=(narrow?source?.mobileScale:source?.desktopScale)||1;
    const scale=Math.max(cssWidth/bitmap.width,cssHeight/bitmap.height)*shrink;
    const width=bitmap.width*scale,height=bitmap.height*scale;
    // A source may name the horizontal focus of its subject (0–1 of the frame width):
    // when the viewport crops the frame, the crop stays centred on it.
    const focus=source?.[narrow?'mobileFocusX':'desktopFocusX'];
    const x=focus==null?(cssWidth-width)/2:Math.max(cssWidth-width,Math.min(0,cssWidth/2-focus*width));
    // Keep the opening headroom on wide desktops, then follow the device below.
    const framing=narrow?.5:(index<240?Math.min(1,index/144):1-.5*Math.min(1,(index-240)/191));
    const alignY=source?.[narrow?'mobileAlignY':'desktopAlignY'];
    const y=(cssHeight-height)*(alignY??framing);
    const ratio=canvas.width/cssWidth;
    ctx.drawImage(bitmap,x*ratio,y*ratio,width*ratio,height*ratio);
    if(drawn<0)canvas.style.visibility='visible';
    drawn=index;section.dataset.frame=String(index);
  }
  function update(){
    raf=0;if(!active)return;
    // One layout read per frame; sizes are measured in resize().
    const top=section.getBoundingClientRect().top;
    const visible=top<=0&&top+distance>0;
    if(visible!==inView){inView=visible;document.body.classList.toggle('welcome-in-view',visible);if(back)back.inert=visible;}
    progress=clamp(-top/distance);
    if(!engaged&&Math.abs(progress-(manifest.entryProgress||0))>.004)engaged=true;
    const beat=manifest.beats.find(b=>progress<=b.scroll[1])||manifest.beats.at(-1);
    const local=clamp((progress-beat.scroll[0])/(beat.scroll[1]-beat.scroll[0]));
    const next=Math.round(beat.frames[0]+local*(beat.frames[1]-beat.frames[0]));
    if(next!==target){direction=next>target?1:-1;target=next;}
    if(chapter!==beat.id){chapter=beat.id;section.dataset.chapter=chapter;}
    draw();
    composeCopy(progress);
    // The film dissolves into the page colour during the last reading stretch,
    // so the next section continues the same surface instead of cutting.
    const fade=ease(clamp((progress-(manifest.outro?.at??.955))/(manifest.outro?.span??.045)));
    // Documentary HTML may continue after the generated film has dissolved.
    const filmRange=manifest.filmOutro||manifest.outro;
    const filmFade=ease(clamp((progress-(filmRange?.at??.955))/(filmRange?.span??.045)));
    if(outroLayer)set(outroLayer,'_fade',filmFade.toFixed(3),v=>outroLayer.style.opacity=v);
    if(fade!==outro){outro=fade;if(footer)footer.style.opacity=(1-Math.min(1,fade*1.6)).toFixed(3);}
    const value=Math.round(progress*100);
    const link=chapterLinks.find(c=>progress>=c.from&&progress<c.to)||null;
    if(link!==currentLink){currentLink?.el.removeAttribute('aria-current');link?.el.setAttribute('aria-current','step');currentLink=link;}
    if(value!==meterValue){meterValue=value;meter.style.setProperty('--story-progress',String(progress.toFixed(3)));meter.setAttribute('aria-valuenow',String(value));}
    pump();
  }
  function schedule(){if(!raf&&active&&!document.hidden)raf=requestAnimationFrame(update);}
  function resize(){
    if(!active)return;
    narrow=innerWidth<768;
    const next=narrow?'mobilePattern':'desktopPattern';
    if(next!==pattern){release();failed.clear();pattern=next;}
    headerHeight=header?.getBoundingClientRect().height||0;
    section.style.setProperty('--story-header',headerHeight+'px');
    cssWidth=stage.clientWidth;cssHeight=stage.clientHeight;stageHeight=stage.offsetHeight;
    distance=Math.max(1,section.offsetHeight-stageHeight);
    const ratio=Math.min(devicePixelRatio||1,1.5);
    const width=Math.round(cssWidth*ratio),height=Math.round(cssHeight*ratio);
    if(canvas.width!==width||canvas.height!==height){
      canvas.width=width;canvas.height=height;drawn=-1;
    }
    // ResizeObserver runs after animation callbacks: repaint before this paint,
    // rather than leaving a cleared black buffer until the next animation frame.
    draw();schedule();
  }
  async function activate(){
    if(reduced.matches||!ctx||window.__ksWelcomeBoot?.expired)return showFallback();
    active=true;narrow=innerWidth<768;pattern=narrow?'mobilePattern':'desktopPattern';
    // AVIF halves the film's weight; browsers that cannot decode it fall back to WebP.
    extension=manifest.avif?'avif':'webp';
    await fetchFrame(0);await decode(0);
    if(!bitmaps.has(0)&&extension==='avif'){extension='webp';release();failed.clear();await fetchFrame(0);await decode(0);}
    if(!bitmaps.has(0))return showFallback();
    if(document.documentElement.classList.contains('welcome-loading')&&document.fonts){
      await Promise.race([
        Promise.allSettled([document.fonts.load('500 48px Poppins'),document.fonts.load('400 16px Inter')]),
        new Promise(resolve=>setTimeout(resolve,2000))
      ]);
    }
    if(window.__ksWelcomeBoot?.expired||reduced.matches)return showFallback();
    section.style.setProperty('--story-height',`${100*(1+manifest.activeViewportHeights)}svh`);
    section.classList.add('cinematic-enhanced');section.dataset.renderer='sequence';
    skip.hidden=false;
    resize();
    if(entryPending){
      entryPending=false;
      const navigation=performance.getEntriesByType('navigation')[0];
      if(location.hash&&navigation?.type!=='back_forward'){
        // Pinning changes document height after the browser's initial anchor jump.
        let anchor;
        try{anchor=document.getElementById(decodeURIComponent(location.hash.slice(1)));}catch{}
        if(anchor)requestAnimationFrame(()=>anchor.scrollIntoView({behavior:'instant'}));
      }
      // Enter once on a fresh visit; retain anchors and a restored reading position.
      if(!location.hash&&scrollY<2&&!['back_forward','reload'].includes(navigation?.type)){
        document.body.classList.add('welcome-entering');
        header?.classList.add('scrolled');
        resize();
        scrollTo({top:section.getBoundingClientRect().top+scrollY+distance*manifest.entryProgress,behavior:'instant'});
        requestAnimationFrame(()=>document.body.classList.remove('welcome-entering'));
      }
    }
    update();
    if(document.documentElement.classList.contains('welcome-loading')){
      // Let the initial scroll, sticky header and fonts settle under the curtain.
      await new Promise(resolve=>requestAnimationFrame(()=>requestAnimationFrame(resolve)));
      if(window.__ksWelcomeBoot?.expired)return showFallback();
      resize();update();
    }
    finishBoot();
  }
  try{
    const url=new URL(section.dataset.manifest,location.href);base=url;
    const response=await fetch(url);if(!response.ok)throw new Error('Manifest unavailable');manifest=await response.json();
    if(!Number.isInteger(manifest.frameCount)||manifest.frameCount<2||!Array.isArray(manifest.beats))throw new Error('Invalid sequence');
    timings();
    await activate();
    if(header&&typeof ResizeObserver!=='undefined')new ResizeObserver(resize).observe(header);
    addEventListener('scroll',schedule,{passive:true});addEventListener('resize',resize,{passive:true});
    document.addEventListener('visibilitychange',()=>{if(document.hidden){if(raf)cancelAnimationFrame(raf);raf=0;}else schedule();});
    reduced.addEventListener('change',()=>{if(reduced.matches)showFallback();else activate();});
    // Chapter marks jump straight to that chapter's reading pose.
    chapterLinks.forEach(c=>c.el.addEventListener('click',()=>scrollTo({top:section.getBoundingClientRect().top+scrollY+distance*c.goto,behavior:'instant'})));
    skip.addEventListener('click',()=>{
      document.getElementById('home-services').scrollIntoView({behavior:'instant'});
      const heading=document.getElementById('home-services-title');heading.setAttribute('tabindex','-1');heading.focus({preventScroll:true});
    });
    addEventListener('pagehide',()=>{active=false;release();memoryFx?.dispose();memoryFx=null;memoryFxAttempted=false;flyerFx?.dispose();flyerFx=null;flyerAttempted=false;if(raf)cancelAnimationFrame(raf);});
    addEventListener('pageshow',e=>{if(e.persisted)activate();});
  }catch(error){showFallback();console.warn('Cinematic sequence unavailable; readable poster layout retained.',error.message);}
}
