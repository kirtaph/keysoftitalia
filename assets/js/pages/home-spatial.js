import * as THREE from '../lib/three/three.module.min.js';

const stage = document.getElementById('spatial-stage');
const canvas = document.getElementById('home-3d-canvas');
if (stage && canvas) {
  try { createShowroom(); }
  catch (error) {
    stage.dataset.renderer = 'fallback';
    stage.querySelector('.spatial-hint').textContent = 'Riparazioni, dispositivi e idee. Trova la tua prossima soluzione.';
    stage.querySelector('.spatial-controls').hidden = true;
    console.warn('Showroom 3D unavailable; photographic alternative active.', error.message);
  }
}

function createShowroom() {
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const renderer = new THREE.WebGLRenderer({canvas, alpha:true, antialias:true, powerPreference:'low-power'});
  renderer.setPixelRatio(Math.min(devicePixelRatio, 1.65));
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.2;
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(34, 1, .1, 100);
  camera.position.set(0, 1.5, 11.5);
  camera.lookAt(0, .25, 0);

  // A local studio environment creates actual metallic reflections.
  // No remote HDRI, model downloads or image service is required.
  const studio = new THREE.Scene();
  studio.background = new THREE.Color('#25272c');
  const lightPanel = (w, h, color, x, y, z) => {
    const panel = new THREE.Mesh(new THREE.PlaneGeometry(w,h), new THREE.MeshBasicMaterial({color,side:THREE.DoubleSide}));
    panel.position.set(x,y,z); panel.lookAt(0,0,0); studio.add(panel);
  };
  lightPanel(5,9,0xffffff,-5,3,3);
  lightPanel(3,7,0xffbd95,5,1,2);
  lightPanel(8,3,0xffffff,0,6,-2);
  lightPanel(4,6,0x989ca5,0,-2,-5);
  const generator = new THREE.PMREMGenerator(renderer);
  const environment = generator.fromScene(studio,.04);
  scene.environment = environment.texture;
  generator.dispose();
  studio.traverse(object=>{object.geometry?.dispose(); if(object.material) object.material.dispose();});
  scene.add(new THREE.HemisphereLight(0xffffff,0x29272c,2));
  const key = new THREE.DirectionalLight(0xffffff,4);
  key.position.set(-4,6,7); scene.add(key);
  const rim = new THREE.DirectionalLight(0xff854e,3);
  rim.position.set(5,2,-3); scene.add(rim);
  const fill = new THREE.DirectionalLight(0xffffff,2);
  fill.position.set(3,-2,5); scene.add(fill);

  const silver = new THREE.MeshPhysicalMaterial({color:0xbec2c9,metalness:1,roughness:.24,clearcoat:.4});
  const graphite = new THREE.MeshPhysicalMaterial({color:0x252731,metalness:.75,roughness:.3,clearcoat:.65});
  const copper = new THREE.MeshPhysicalMaterial({color:0xff743e,metalness:.7,roughness:.26,clearcoat:.8});
  const black = new THREE.MeshStandardMaterial({color:0x080a0e,metalness:.3,roughness:.36});
  const lens = new THREE.MeshPhysicalMaterial({color:0x0b1628,metalness:.5,roughness:.06,clearcoat:1});
  const boardMaterial = new THREE.MeshStandardMaterial({color:0x23413a,metalness:.3,roughness:.48});

  function shape(w,h,r) {
    const s=new THREE.Shape(),x=-w/2,y=-h/2;
    s.moveTo(x+r,y); s.lineTo(x+w-r,y); s.quadraticCurveTo(x+w,y,x+w,y+r);
    s.lineTo(x+w,y+h-r); s.quadraticCurveTo(x+w,y+h,x+w-r,y+h);
    s.lineTo(x+r,y+h); s.quadraticCurveTo(x,y+h,x,y+h-r);
    s.lineTo(x,y+r); s.quadraticCurveTo(x,y,x+r,y);
    return s;
  }
  function rounded(w,h,d,r,material) {
    const bevel=Math.min(d*.2,.035);
    const geometry=new THREE.ExtrudeGeometry(shape(w-bevel*2,h-bevel*2,r),{
      depth:d-bevel*2,bevelEnabled:true,bevelSegments:3,steps:1,bevelSize:bevel,bevelThickness:bevel,curveSegments:12
    });
    geometry.translate(0,0,-d/2+bevel);
    return new THREE.Mesh(geometry,material);
  }
  function face(w,h,r,material) {
    const geometry=new THREE.ShapeGeometry(shape(w,h,r),20);
    const positions=geometry.attributes.position,uv=geometry.attributes.uv;
    for(let i=0;i<positions.count;i++) uv.setXY(i,positions.getX(i)/w+.5,positions.getY(i)/h+.5);
    return new THREE.Mesh(geometry,material);
  }
  function screenTexture(kind) {
    const image=document.createElement('canvas');
    image.width=kind==='phone'?768:1280; image.height=kind==='phone'?1536:768;
    const ctx=image.getContext('2d'),w=image.width,h=image.height;
    ctx.fillStyle='#111318'; ctx.fillRect(0,0,w,h);
    // Sculptural screen wallpaper; the moving device itself is real geometry.
    const gradient=ctx.createRadialGradient(w*.9,h*.42,0,w*.6,h*.5,w*.95);
    gradient.addColorStop(0,'#ffbf84'); gradient.addColorStop(.35,'#ff6b35'); gradient.addColorStop(.7,'#a72d0b'); gradient.addColorStop(1,'#111318');
    ctx.fillStyle=gradient; ctx.beginPath(); ctx.ellipse(w*.74,h*.5,w*.73,h*.33,-.65,0,Math.PI*2); ctx.fill();
    ctx.strokeStyle='rgba(255,255,255,.3)'; ctx.lineWidth=3;
    for(let i=0;i<5;i++){ctx.beginPath();ctx.ellipse(w*.78,h*.48,w*(.43+i*.05),h*(.24+i*.025),-.65,0,Math.PI*2);ctx.stroke();}
    ctx.fillStyle='#ffffff';ctx.font='600 '+(kind==='phone'?94:86)+'px Arial';
    ctx.fillText('KEY',w*.09,h*.22);ctx.fillText('SOFT',w*.09,h*.22+100);
    ctx.font='24px Arial'; ctx.fillText('Il tuo mondo. Più possibilità.',w*.09,h*.88);
    ctx.font='18px Arial';ctx.fillStyle='#d5d8dc';ctx.fillText('GINOSA · DAL 2004',w*.09,h*.93);
    const texture=new THREE.CanvasTexture(image);
    texture.colorSpace=THREE.SRGBColorSpace;
    texture.anisotropy=Math.min(4,renderer.capabilities.getMaxAnisotropy());
    return texture;
  }
  const phoneDisplay=new THREE.MeshBasicMaterial({map:screenTexture('phone'),toneMapped:false});
  const laptopDisplay=new THREE.MeshBasicMaterial({map:screenTexture('laptop'),toneMapped:false});

  function phone(material,withInterior=false) {
    const device=new THREE.Group();
    device.add(rounded(1.78,3.62,.2,.22,material));
    const back=face(1.65,3.49,.19,graphite); back.rotation.y=Math.PI;back.position.z=-.108;device.add(back);
    const bump=rounded(.95,1.04,.13,.2,material);bump.position.set(-.28,.99,-.17);device.add(bump);
    [[-.48,1.25],[-.07,1.08],[-.47,.81]].forEach(([x,y])=>{
      const ring=new THREE.Mesh(new THREE.CylinderGeometry(.185,.185,.09,32),silver);
      ring.rotation.x=Math.PI/2;ring.position.set(x,y,-.27);device.add(ring);
      const glass=new THREE.Mesh(new THREE.CircleGeometry(.153,32),lens);
      glass.rotation.y=Math.PI;glass.position.set(x,y,-.321);device.add(glass);
      const glint=new THREE.Mesh(new THREE.CircleGeometry(.039,16),new THREE.MeshBasicMaterial({color:0x314d74}));
      glint.rotation.y=Math.PI;glint.position.set(x-.04,y+.04,-.324);device.add(glint);
    });
    const flash=new THREE.Mesh(new THREE.CircleGeometry(.075,20),new THREE.MeshStandardMaterial({color:0xffe5ca,emissive:0x654321}));
    flash.rotation.y=Math.PI;flash.position.set(-.02,1.4,-.255);device.add(flash);
    const sideKey=rounded(.055,.38,.07,.02,material);sideKey.position.set(.91,.59,0);device.add(sideKey);
    const volume=sideKey.clone();volume.scale.y=.7;volume.position.set(-.91,.59,0);device.add(volume);
    const port=rounded(.29,.026,.065,.01,black);port.rotation.x=Math.PI/2;port.position.set(0,-1.82,0);device.add(port);
    const front=new THREE.Group();
    front.add(face(1.65,3.49,.19,black));
    const display=face(1.57,3.4,.17,phoneDisplay);display.position.z=.008;front.add(display);
    const island=rounded(.48,.11,.019,.055,black);island.position.set(0,1.49,.024);front.add(island);
    front.position.z=.115;device.add(front);
    const battery=new THREE.Group();
    const internals=[];
    if(withInterior) {
      const circuit=face(1.6,3.44,.15,boardMaterial);circuit.position.z=.105;device.add(circuit);internals.push(circuit);
      for(let i=0;i<8;i++){
        const chip=rounded(.18+(i%3)*.06,.14,.025,.01,i%2?black:copper);
        chip.position.set(-.55+(i%3)*.34,1.3-Math.floor(i/3)*.25,.135);device.add(chip);internals.push(chip);
      }
      const pack=rounded(1.14,1.82,.09,.1,graphite);battery.add(pack);
      const batteryLabel=face(.69,.29,.02,new THREE.MeshBasicMaterial({color:0xff743e}));
      batteryLabel.position.set(0,.2,.05);battery.add(batteryLabel);
      battery.position.set(0,-.33,.16);device.add(battery);
      const coil=new THREE.Mesh(new THREE.TorusGeometry(.29,.026,8,48),copper);
      coil.position.set(0,-.31,.052);battery.add(coil);
      internals.push(battery);
    }
    return {device,front,battery,internals};
  }
  function laptop() {
    const device=new THREE.Group();
    const base=rounded(3.65,2.35,.12,.14,silver);base.rotation.x=-Math.PI/2;base.position.set(0,-.75,.28);device.add(base);
    const lid=new THREE.Group();lid.position.set(0,-.71,-.85);lid.rotation.x=-.23;device.add(lid);
    const casing=rounded(3.64,2.22,.1,.12,silver);casing.position.y=1.11;lid.add(casing);
    const surround=face(3.47,2.05,.09,black);surround.position.set(0,1.11,.057);lid.add(surround);
    const screen=face(3.31,1.89,.065,laptopDisplay);screen.position.set(0,1.12,.065);lid.add(screen);
    const webcam=new THREE.Mesh(new THREE.CircleGeometry(.021,12),lens);webcam.position.set(0,2.145,.062);lid.add(webcam);
    const keys=new THREE.InstancedMesh(new THREE.BoxGeometry(.205,.018,.17),black,65);
    const matrix=new THREE.Matrix4();let n=0;
    for(let row=0;row<5;row++) for(let col=0;col<13;col++){matrix.makeTranslation(-1.43+col*.238,-.677,-.56+row*.23);keys.setMatrixAt(n++,matrix);}
    device.add(keys);
    const touchpad=rounded(1.17,.57,.01,.06,graphite);
    touchpad.rotation.x=-Math.PI/2;touchpad.position.set(0,-.681,1.06);device.add(touchpad);
    const hinge=new THREE.Mesh(new THREE.CylinderGeometry(.065,.065,3.35,20),graphite);
    hinge.rotation.z=Math.PI/2;hinge.position.set(0,-.7,-.84);device.add(hinge);
    return {device,lid};
  }
  const assembly=new THREE.Group();scene.add(assembly);
  const primary=phone(silver,true),secondary=phone(copper),notebook=laptop();
  assembly.add(primary.device,secondary.device,notebook.device);
  const orbit=new THREE.Mesh(new THREE.TorusGeometry(2.9,.012,6,120),new THREE.MeshBasicMaterial({color:0xff6b35,transparent:true,opacity:.35}));
  orbit.rotation.set(1.1,.35,.2);orbit.position.set(.3,-.1,-1.2);assembly.add(orbit);
  const orbit2=orbit.clone();orbit2.scale.setScalar(1.18);orbit2.rotation.set(.6,-.35,-.3);orbit2.material=orbit.material.clone();orbit2.material.opacity=.12;assembly.add(orbit2);

  const layouts={
    choose:{phone:[-.85,.5,.9,.11,-.4,-.14,1],second:[1.63,1.24,-.6,-.15,2.68,.2,.82],laptop:[.65,-1.15,-.2,-.1,-.28,.08,.91],explode:0,caption:'Il tuo prossimo dispositivo. Da ogni prospettiva.'},
    repair:{phone:[-.4,.15,.7,.2,-.87,-.13,1.22],second:[2.3,2.25,-1.3,-.3,2.6,.4,.6],laptop:[1.5,-1.55,-1.4,-.15,-.5,.08,.68],explode:1,caption:'Dentro la tecnologia. Il nostro laboratorio.'},
    create:{phone:[-2,-.2,-.5,.1,-.35,-.2,.75],second:[1.8,1.7,-1.2,-.1,2.8,.2,.6],laptop:[.2,-.2,.8,-.05,-.18,.02,1.25],explode:0,caption:'Dalle tue idee, alle soluzioni digitali.'}
  };
  let mode='choose',paused=false,visible=true,raf=0,last=0,time=0,frames=0,dragging=false,dragStart=0,manualTurn=0;
  const pointer=new THREE.Vector2(),smoothPointer=new THREE.Vector2();
  const devicePairs=[[primary.device,'phone'],[secondary.device,'second'],[notebook.device,'laptop']];
  function pose(dt,instant=false) {
    const target=layouts[mode],lambda=instant?10000:6;
    devicePairs.forEach(([device,key],i)=>{
      const p=target[key],wave=reduced.matches||paused?0:Math.sin(time*.65+i*1.8);
      device.position.x=THREE.MathUtils.damp(device.position.x,p[0],lambda,dt);
      device.position.y=THREE.MathUtils.damp(device.position.y,p[1]+wave*.16,lambda,dt);
      device.position.z=THREE.MathUtils.damp(device.position.z,p[2],lambda,dt);
      device.rotation.x=THREE.MathUtils.damp(device.rotation.x,p[3]+wave*.055,lambda,dt);
      device.rotation.y=THREE.MathUtils.damp(device.rotation.y,p[4]+(reduced.matches||paused?0:Math.sin(time*.43+i)*.23),lambda,dt);
      device.rotation.z=THREE.MathUtils.damp(device.rotation.z,p[5]+wave*.045,lambda,dt);
      device.scale.setScalar(THREE.MathUtils.damp(device.scale.x,p[6],lambda,dt));
    });
    primary.front.position.z=THREE.MathUtils.damp(primary.front.position.z,.115+target.explode*1.1,lambda,dt);
    primary.front.position.x=THREE.MathUtils.damp(primary.front.position.x,target.explode*-.45,lambda,dt);
    primary.front.rotation.y=THREE.MathUtils.damp(primary.front.rotation.y,target.explode*.16,lambda,dt);
    primary.battery.position.z=THREE.MathUtils.damp(primary.battery.position.z,.16+target.explode*.37,lambda,dt);
    primary.internals.forEach(part=>{part.visible=primary.front.position.z>.15;});
    smoothPointer.x=THREE.MathUtils.damp(smoothPointer.x,pointer.x,5,dt);
    smoothPointer.y=THREE.MathUtils.damp(smoothPointer.y,pointer.y,5,dt);
    assembly.rotation.y=manualTurn+smoothPointer.x*.18;
    assembly.rotation.x=smoothPointer.y*.11;
    orbit.rotation.z=time*.09;
    orbit2.rotation.z=-time*.065;
    renderer.render(scene,camera);
    stage.dataset.frames=String(++frames);
    stage.dataset.scene=mode;
  }
  function tick(now) {
    raf=0;
    if(!visible||document.hidden||paused||reduced.matches) return;
    const dt=Math.min((now-last)/1000||.016,.05);last=now;time+=dt;
    pose(dt);
    raf=requestAnimationFrame(tick);
  }
  function start() {
    if(!raf&&visible&&!document.hidden&&!paused&&!reduced.matches){last=performance.now();raf=requestAnimationFrame(tick);}
  }
  function stop(){if(raf) cancelAnimationFrame(raf);raf=0;}
  function resize() {
    const w=stage.clientWidth,h=stage.clientHeight-125;
    renderer.setSize(w,h,false);camera.aspect=w/h;
    camera.position.z=w<500?13.7:11.5;
    camera.updateProjectionMatrix();pose(1,true);
  }
  new ResizeObserver(resize).observe(stage);resize();
  stage.classList.add('spatial-ready');stage.dataset.renderer='webgl';
  const caption=document.getElementById('spatial-caption');
  const status=document.getElementById('spatial-status');
  stage.querySelectorAll('[data-scene]').forEach(button=>button.addEventListener('click',()=>{
    mode=button.dataset.scene;
    stage.querySelectorAll('[data-scene]').forEach(item=>item.setAttribute('aria-pressed',String(item===button)));
    caption.textContent=layouts[mode].caption;status.textContent=layouts[mode].caption;
    manualTurn=0;
    if(paused||reduced.matches) pose(1,true);else start();
  }));
  const pause=document.getElementById('spatial-pause');
  pause.addEventListener('click',()=>{
    paused=!paused;
    pause.setAttribute('aria-pressed',String(paused));
    pause.setAttribute('aria-label',paused?'Riprendi l’animazione 3D':'Metti in pausa l’animazione 3D');
    pause.querySelector('i').className=paused?'ri-play-line':'ri-pause-line';
    if(paused)stop();else start();
  });
  stage.addEventListener('pointerdown',event=>{
    if(event.target.closest('button'))return;
    dragging=true;dragStart=event.clientX;canvas.setPointerCapture(event.pointerId);
  });
  stage.addEventListener('pointermove',event=>{
    const rect=stage.getBoundingClientRect();
    if(dragging){manualTurn+=(event.clientX-dragStart)*.007;dragStart=event.clientX;}
    else if(event.pointerType!=='mouse') return;
    pointer.set(((event.clientX-rect.left)/rect.width-.5)*2,((event.clientY-rect.top)/rect.height-.5)*2);
    if(paused||reduced.matches)pose(1,true);
  });
  const endDrag=()=>{dragging=false;};
  stage.addEventListener('pointerup',endDrag);stage.addEventListener('pointercancel',endDrag);
  stage.addEventListener('pointerleave',()=>{pointer.set(0,0);});
  new IntersectionObserver(entries=>{
    visible=entries[0].isIntersecting;
    if(visible)start();else stop();
  },{threshold:.05}).observe(stage);
  document.addEventListener('visibilitychange',()=>{if(document.hidden)stop();else start();});
  reduced.addEventListener('change',()=>{stop();pose(1,true);if(!reduced.matches)start();});
  canvas.addEventListener('webglcontextlost',event=>{
    event.preventDefault();stop();stage.classList.remove('spatial-ready');stage.dataset.renderer='fallback';
  });
  canvas.addEventListener('webglcontextrestored',()=>{stage.classList.add('spatial-ready');resize();start();});
  start();
}
