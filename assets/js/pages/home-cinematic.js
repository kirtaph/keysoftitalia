// Native scroll owns a bounded, reversible timeline. Text always remains HTML.
const section=document.getElementById('home-cinematic');
if(section) initializeStory();
async function initializeStory(){
  const stage=section.querySelector('.cinematic-stage');
  const canvas=section.querySelector('canvas');
  const shade=section.querySelector('.cinematic-shade');
  const ctx=canvas.getContext('2d',{alpha:false});
  const reduced=matchMedia('(prefers-reduced-motion: reduce)');
  const chapters=[...section.querySelectorAll('[data-chapter]')];
  const copy=chapters.map(element=>({element,units:element.dataset.chapter==='people'?[element.querySelector('figure'),...element.querySelector('.cinematic-people-copy').children]:[...element.children].filter(child=>!child.classList.contains('cinematic-chapter-poster'))}));
  const header=document.querySelector('.header');
  const cache=new Map(),pending=new Map(),failed=new Map();
  let manifest,base,pattern,epoch=0,target=0,drawn=-1,progress=0,raf=0,active=false,chapter='';
  let cssWidth=0,cssHeight=0;
  function release(){epoch++;pending.forEach(p=>p.controller.abort());pending.clear();cache.forEach(b=>b.close());cache.clear();drawn=-1;}
  function fallback(){active=false;section.classList.remove('cinematic-enhanced');section.dataset.renderer='poster';release();chapters.forEach(c=>{c.inert=false;c.removeAttribute('aria-hidden');});copy.forEach(({units})=>units.forEach(unit=>{unit.style.removeProperty('opacity');unit.style.removeProperty('transform');unit.style.removeProperty('pointer-events');unit.inert=false;}));section.querySelector('.cinematic-skip').hidden=true;}
  // Sample the shared cubic-bezier(.23,1,.32,1) once; scroll updates interpolate.
  const easing=Array.from({length:101},(_,i)=>{
    const x=i/100;let low=0,high=1;
    for(let n=0;n<14;n++){const t=(low+high)/2,u=1-t;const at=3*u*u*t*.23+3*u*t*t*.32+t*t*t;if(at<x)low=t;else high=t;}
    return 1-Math.pow(1-(low+high)/2,3);
  });
  const ease=value=>{if(value<=0)return 0;if(value>=1)return 1;const index=value*100,a=Math.floor(index);return easing[a]+(easing[a+1]-easing[a])*(index-a);};
  function composeCopy(beat,position){
    const travel=manifest.activeViewportHeights;
    let backdrop=0;
    copy.forEach(({element,units})=>{
      const current=element.dataset.chapter===beat.id;
      let readable=false;
      units.forEach((unit,index)=>{
        // These are scroll distances, not time-based animations. Reversal is exact.
        const enterStart=beat.scroll[0]+index*.025/travel;
        const entrance=beat.id==='people'?.45:.16;
        const enter=current?(beat.id==='opening'?1:ease((position-enterStart)/(entrance/travel))):0;
        const exit=current?ease((position-(beat.scroll[1]-(.16+(units.length-1-index)*.02)/travel))/(.16/travel)):0;
        const opacity=enter*(1-exit);
        if(current&&(cssWidth<768||['care','choose','create','perspective','selection','people'].includes(beat.id)))backdrop=Math.max(backdrop,opacity);
        const shift=(1-enter)*(index===1?44:28)-exit*22;
        // Enter from the quiet left side; return there on exit, leaving the film clear.
        const lateral=innerWidth<768?24:(beat.id==='people'?180:80);
        const direction=(unit.dataset.motionSide||element.dataset.copySide)==='right'?1:-1;
        const shiftX=direction*((1-enter)*lateral+exit*lateral*.65);
        const values={opacity:opacity.toFixed(4),transform:`translate3d(${shiftX.toFixed(2)}px,${shift.toFixed(2)}px,0)`};
        for(const [key,value] of Object.entries(values))if(unit.style[key]!==value)unit.style[key]=value;
        const interactive=current&&opacity>.9;
        const hasControls=unit.matches('a,button')||unit.querySelector('a,button');
        unit.inert=hasControls?!interactive:(!current||opacity<.05);
        unit.style.pointerEvents=interactive?'':'none';
        readable ||= opacity>.05;
      });
      element.inert=!current||!readable;
      element.setAttribute('aria-hidden',String(!current||!readable));
      element.classList.toggle('is-current',current);
    });
    if(shade)shade.style.opacity=backdrop.toFixed(4);
  }
  function trim(){while(cache.size>18){let far=null,distance=-1;cache.forEach((b,i)=>{if(Math.abs(i-target)>distance){far=i;distance=Math.abs(i-target);}});cache.get(far).close();cache.delete(far);}}
  function filename(index){
    const source=manifest.frameSources?.find(s=>index>=s.start&&index<=s.end);
    const selected=source?(pattern===manifest.mobilePattern?source.mobilePattern:source.desktopPattern):pattern;
    return new URL(selected.replace('{index}',String(index-(source?.start||0)).padStart(4,'0')),base).href;
  }
  async function load(index){
    if(index<0||index>=manifest.frameCount||cache.has(index)||pending.has(index))return;
    if((failed.get(index)||0)>=2)return;
    const generation=epoch,controller=new AbortController();pending.set(index,{controller,generation});
    try{
      const response=await fetch(filename(index),{signal:controller.signal,cache:'force-cache'});
      if(!response.ok)throw new Error('Missing frame');
      const bitmap=await createImageBitmap(await response.blob());
      if(generation!==epoch){bitmap.close();return;}
      cache.set(index,bitmap);trim();schedule();
    }catch(error){if(error.name!=='AbortError'&&generation===epoch)failed.set(index,(failed.get(index)||0)+1);}
    finally{if(pending.get(index)?.controller===controller)pending.delete(index);if(generation===epoch&&active)pump();}
  }
  function pump(){
    if(!active||document.hidden)return;
    // Abandon remote positions after a fast jump; prioritize current and nearby frames.
    pending.forEach((p,i)=>{if(Math.abs(i-target)>20){p.controller.abort();pending.delete(i);}});
    const wanted=[target];for(let n=1;n<=7;n++)wanted.push(target+n,target-n);
    for(const index of wanted){if(pending.size>=4)break;if(index>=0&&index<manifest.frameCount&&!cache.has(index)&&!pending.has(index)&&(failed.get(index)||0)<2)load(index);}
  }
  function draw(){
    let index=target;
    if(!cache.has(index)){const nearby=[...cache.keys()].sort((a,b)=>Math.abs(a-target)-Math.abs(b-target));index=nearby[0];}
    if(index===undefined||index===drawn)return;
    const bitmap=cache.get(index);if(!bitmap)return;
    ctx.fillStyle='#101114';ctx.fillRect(0,0,canvas.width,canvas.height);
    const mobile=cssWidth<768;
    const scale=Math.max(cssWidth/bitmap.width,cssHeight/bitmap.height);
    const width=bitmap.width*scale,height=bitmap.height*scale;
    // Follow the fourth film's lateral reframing instead of cropping the notebook away.
    const reframe=manifest.mobileReframe;
    const tracking=reframe?Math.max(0,Math.min(1,(index-reframe.frames[0])/(reframe.frames[1]-reframe.frames[0]))):0;
    const focus=reframe?reframe.from+(reframe.to-reframe.from)*tracking:.58;
    const x=mobile?cssWidth*.5-width*focus:(cssWidth-width)/2;
    const y=(cssHeight-height)/2;
    const ratio=canvas.width/cssWidth;
    ctx.drawImage(bitmap,x*ratio,y*ratio,width*ratio,height*ratio);
    drawn=index;section.dataset.frame=String(index);
  }
  function update(){
    raf=0;if(!active)return;
    const top=header?.getBoundingClientRect().height||0;
    const rect=section.getBoundingClientRect();
    const distance=Math.max(1,section.offsetHeight-stage.offsetHeight);
    progress=Math.max(0,Math.min(1,(top-rect.top)/distance));
    const beat=manifest.beats.find(b=>progress<=b.scroll[1])||manifest.beats.at(-1);
    let local=Math.max(0,Math.min(1,(progress-beat.scroll[0])/(beat.scroll[1]-beat.scroll[0])));
    // Real reading holds, separately mapped from the moving intervals.
    if(beat.id==='opening')local=Math.max(0,(local-.22)/.78);
    if(beat.id==='care')local=Math.min(1,local/.85);
    if(beat.id==='restart')local=Math.min(1,local/.72);
    if(beat.id==='choose'||beat.id==='create'||beat.id==='perspective')local=Math.min(1,local/.35);
    // The supplied clips change device pose at the join. Hide that cut in black.
    const filmOpacity=beat.id==='people'?1-ease((progress-beat.scroll[0])*manifest.activeViewportHeights/.5):beat.id.startsWith('join')?Math.abs(local*2-1):1;
    section.style.setProperty('--story-picture-opacity',String(filmOpacity));
    target=Math.round(beat.frames[0]+local*(beat.frames[1]-beat.frames[0]));
    if(chapter!==beat.id){chapter=beat.id;section.dataset.chapter=chapter;}
    stage.dataset.copySide=['perspective','selection'].includes(beat.id)?'right':'left';
    composeCopy(beat,progress);
    section.style.setProperty('--story-progress',String(progress));
    const meter=section.querySelector('[role=progressbar]');meter.setAttribute('aria-valuenow',String(Math.round(progress*100)));
    draw();pump();
  }
  function schedule(){if(!raf&&active&&!document.hidden)raf=requestAnimationFrame(update);}
  function resize(){
    if(!active)return;
    const next=innerWidth<768?manifest.mobilePattern:manifest.desktopPattern;
    if(next!==pattern){release();failed.clear();pattern=next;}
    const h=header?.getBoundingClientRect().height||0;
    section.style.setProperty('--story-header',h+'px');
    cssWidth=stage.clientWidth;cssHeight=stage.clientHeight;
    const ratio=Math.min(devicePixelRatio||1,1.5);canvas.width=Math.round(cssWidth*ratio);canvas.height=Math.round(cssHeight*ratio);drawn=-1;schedule();
  }
  async function activate(){
    if(reduced.matches||!ctx)return fallback();
    active=true;pattern=innerWidth<768?manifest.mobilePattern:manifest.desktopPattern;
    await load(0);if(!cache.has(0))return fallback();
    section.style.setProperty('--story-height',`${100*(1+manifest.activeViewportHeights)}svh`);
    section.classList.add('cinematic-enhanced');section.dataset.renderer='sequence';
    section.querySelector('.cinematic-skip').hidden=false;
    resize();update();
  }
  try{
    const url=new URL(section.dataset.manifest,location.href);base=url;
    const response=await fetch(url);if(!response.ok)throw new Error('Manifest unavailable');manifest=await response.json();
    if(!Number.isInteger(manifest.frameCount)||manifest.frameCount<2||!Array.isArray(manifest.beats))throw new Error('Invalid sequence');
    await activate();
    if(header&&typeof ResizeObserver!=='undefined')new ResizeObserver(resize).observe(header);
    addEventListener('scroll',schedule,{passive:true});addEventListener('resize',resize,{passive:true});
    document.addEventListener('visibilitychange',()=>{if(document.hidden){if(raf)cancelAnimationFrame(raf);raf=0;}else schedule();});
    reduced.addEventListener('change',()=>{if(reduced.matches)fallback();else activate();});
    section.querySelector('.cinematic-skip').addEventListener('click',()=>{
      document.getElementById('home-services').scrollIntoView({behavior:'instant'});
      const heading=document.getElementById('home-services-title');heading.setAttribute('tabindex','-1');heading.focus({preventScroll:true});
    });
    addEventListener('pagehide',()=>{active=false;release();if(raf)cancelAnimationFrame(raf);});
    addEventListener('pageshow',e=>{if(e.persisted)activate();});
  }catch(error){fallback();console.warn('Cinematic sequence unavailable; readable poster layout retained.',error.message);}
}
