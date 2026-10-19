import * as THREE from '../lib/three/three.module.min.js';

// The native scroll timeline supplies progress; the leaflet has no permanent loop.
export async function createFlyer(flyer) {
 const host=flyer.querySelector('.cinematic-flyer-cover'),image=host.querySelector('img');
 if(!image)throw new Error('No flyer cover');
 if(new URL(image.currentSrc||image.src,location.href).origin!==location.origin)throw new Error('External cover uses the static preview');
 await image.decode();
 let renderer,texture,geometry,front,back,edgeGeometry,edgeMaterial,observer,raf=0,dead=false,phase=1,px=0,py=0;
 const canvas=document.createElement('canvas');canvas.className='cinematic-flyer-canvas';canvas.setAttribute('aria-hidden','true');
 function dispose(){
  if(dead)return;dead=true;cancelAnimationFrame(raf);observer?.disconnect();
  host.removeEventListener('pointermove',move);host.removeEventListener('pointerleave',leave);
  host.classList.remove('has-flyer-webgl');canvas.remove();
  geometry?.dispose();edgeGeometry?.dispose();edgeMaterial?.dispose();front?.dispose();back?.dispose();texture?.dispose();renderer?.dispose();
 }
 let group,scene,camera;
 function render(){
  raf=0;if(dead||innerWidth<768)return;
  group.rotation.set(.035+py*.06,-.1-(1-phase)*.45+px*.1,-.025-(1-phase)*.07);
  group.position.y=-(1-phase)*.12;
  try{renderer.render(scene,camera);}catch{dispose();}
 }
 function queue(){if(!dead&&!raf)raf=requestAnimationFrame(render);}
 function move(e){if(e.pointerType!=='mouse')return;const r=host.getBoundingClientRect();px=(e.clientX-r.left)/r.width-.5;py=(e.clientY-r.top)/r.height-.5;queue();}
 function leave(){px=0;py=0;queue();}
 function draw(value){const next=Math.round(Math.max(0,Math.min(1,value))*1000)/1000;if(next!==phase){phase=next;queue();}}
 try{
  renderer=new THREE.WebGLRenderer({canvas,alpha:true,antialias:true,powerPreference:'low-power'});
  renderer.setPixelRatio(Math.min(devicePixelRatio||1,1.5));renderer.outputColorSpace=THREE.SRGBColorSpace;
  scene=new THREE.Scene();camera=new THREE.PerspectiveCamera(34,1,.1,20);camera.position.z=5;
  scene.add(new THREE.HemisphereLight(0xffffff,0xb8b2a8,2.6));
  const light=new THREE.DirectionalLight(0xffffff,1.5);light.position.set(-2,3,5);scene.add(light);
  texture=new THREE.Texture(image);texture.colorSpace=THREE.SRGBColorSpace;texture.needsUpdate=true;
  const ratio=image.naturalWidth/image.naturalHeight,height=2.5,width=height*ratio;
  geometry=new THREE.PlaneGeometry(width,height,36,12);
  const positions=geometry.attributes.position;
  for(let i=0;i<positions.count;i++){const x=positions.getX(i);positions.setZ(i,.055*x*x+.012*Math.sin(positions.getY(i)*2));}
  geometry.computeVertexNormals();
  front=new THREE.MeshStandardMaterial({map:texture,roughness:.9,metalness:0,side:THREE.FrontSide});
  back=new THREE.MeshStandardMaterial({color:0xf4f1e9,roughness:1,side:THREE.BackSide});
  group=new THREE.Group();const face=new THREE.Mesh(geometry,front),reverse=new THREE.Mesh(geometry,back);reverse.position.z=-.012;group.add(reverse,face);scene.add(group);
  const edge=[];
  const point=(x,y)=>new THREE.Vector3(x,y,.055*x*x+.012*Math.sin(y*2)+.002);
  for(let i=0;i<=36;i++)edge.push(point(-width/2+width*i/36,height/2));
  for(let i=1;i<=12;i++)edge.push(point(width/2,height/2-height*i/12));
  for(let i=1;i<=36;i++)edge.push(point(width/2-width*i/36,-height/2));
  for(let i=1;i<12;i++)edge.push(point(-width/2,-height/2+height*i/12));
  edgeGeometry=new THREE.BufferGeometry().setFromPoints(edge);edgeMaterial=new THREE.LineBasicMaterial({color:0xe4ddd0,transparent:true,opacity:.24});
  group.add(new THREE.LineLoop(edgeGeometry,edgeMaterial));
  host.append(canvas);
  const resize=()=>{
   if(dead)return;const w=host.clientWidth,h=host.clientHeight;
   if(!w||!h)return;
   renderer.setSize(w,h,false);camera.aspect=w/h;
   camera.position.z=Math.max(height/2/Math.tan(THREE.MathUtils.degToRad(17)),width/2/(Math.tan(THREE.MathUtils.degToRad(17))*camera.aspect))*1.18;
   camera.updateProjectionMatrix();render();
  };
  observer=new ResizeObserver(resize);observer.observe(host);resize();
  if(!renderer.info.programs?.length||renderer.info.programs.some(p=>p.diagnostics?.runnable===false))throw new Error('Flyer shader unavailable');
  host.classList.add('has-flyer-webgl');
  host.addEventListener('pointermove',move);host.addEventListener('pointerleave',leave);
  canvas.addEventListener('webglcontextlost',e=>{e.preventDefault();dispose();},{once:true});
  return {draw,dispose};
 }catch(error){dispose();throw error;}
}
