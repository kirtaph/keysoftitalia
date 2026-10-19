// One image plane. Scroll is supplied by the existing story; no animation loop.
import * as THREE from '../lib/three/three.module.min.js';

// Both photographs carry their own alpha, shared by WebGL and the CSS fallback.
function textureFromImage(image) {
  const canvas=document.createElement('canvas');canvas.width=1200;canvas.height=900;
  const ctx=canvas.getContext('2d');
  const scale=Math.min(1200/image.naturalWidth,900/image.naturalHeight);
  const w=image.naturalWidth*scale,h=image.naturalHeight*scale,x=(1200-w)/2,y=900-h;
  ctx.drawImage(image,x,y,w,h);
  const texture=new THREE.CanvasTexture(canvas);texture.colorSpace=THREE.SRGBColorSpace;
  texture.minFilter=THREE.LinearFilter;texture.generateMipmaps=false;return texture;
}

export async function createFoundersBlend(memory) {
  const oldImage=memory.querySelector('.founders-monochrome');
  const newImage=memory.querySelector('.founders-present img');
  const host=memory.querySelector('.founders-portraits');
  await Promise.all([oldImage.decode(),newImage.decode()]);
  let renderer,material,geometry,oldTexture,newTexture,observer;
  const canvas=document.createElement('canvas');canvas.className='founders-blend-canvas';canvas.setAttribute('aria-hidden','true');
  let dead=false,phase=0,color=0,last='';
  function dispose(){
    if(dead)return;dead=true;observer?.disconnect();memory.classList.remove('has-founders-webgl');canvas.remove();
    oldTexture?.dispose();newTexture?.dispose();material?.dispose();geometry?.dispose();renderer?.dispose();
  }
  function draw(blend=phase,saturation=color){
    if(dead)return;
    phase=blend;color=saturation;
    const key=phase.toFixed(4)+':'+color.toFixed(4);
    if(key===last)return;last=key;
    material.uniforms.uPhase.value=phase;material.uniforms.uColor.value=color;
    try{renderer.render(scene,camera);}catch{dispose();}
  }
  let scene,camera;
  try{
    renderer=new THREE.WebGLRenderer({canvas,alpha:true,antialias:false,powerPreference:'low-power'});
    renderer.setPixelRatio(Math.min(devicePixelRatio||1,1.5));renderer.setClearColor(0x000000,0);
    renderer.outputColorSpace=THREE.SRGBColorSpace;
    oldTexture=textureFromImage(oldImage);newTexture=textureFromImage(newImage);
    material=new THREE.ShaderMaterial({transparent:true,depthTest:false,depthWrite:false,toneMapped:false,
      uniforms:{uOld:{value:oldTexture},uNow:{value:newTexture},uPhase:{value:0},uColor:{value:0}},
      vertexShader:'varying vec2 vUv; void main(){vUv=uv;gl_Position=vec4(position.xy,0.,1.);}',
      fragmentShader:`
        varying vec2 vUv; uniform sampler2D uOld; uniform sampler2D uNow; uniform float uPhase; uniform float uColor;
        float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}
        float noise(vec2 p){vec2 i=floor(p),f=fract(p);f=f*f*(3.-2.*f);return mix(mix(hash(i),hash(i+vec2(1.,0.)),f.x),mix(hash(i+vec2(0.,1.)),hash(i+vec2(1.,1.)),f.x),f.y);}
        void main(){
          vec4 a=texture2D(uOld,vUv),b=texture2D(uNow,vUv);
          float gray=dot(a.rgb,vec3(.2126,.7152,.0722));a.rgb=mix(vec3(gray),a.rgb,uColor);
          float grain=(noise(vUv*5.)-.5)*.045;
          // Pass through graphite between photographs: two different faces never overlap.
          float oldWeight=1.-smoothstep(.04,.47,uPhase+grain);
          float newWeight=smoothstep(.53,.96,uPhase+grain);
          float alpha=a.a*oldWeight+b.a*newWeight;
          vec3 rgb=(a.rgb*a.a*oldWeight+b.rgb*b.a*newWeight)/max(alpha,.00001);
          gl_FragColor=vec4(rgb,alpha);
          #include <colorspace_fragment>
        }`
    });
    scene=new THREE.Scene();camera=new THREE.Camera();geometry=new THREE.PlaneGeometry(2,2);scene.add(new THREE.Mesh(geometry,material));
    host.append(canvas);
    const resize=()=>{if(dead)return;renderer.setSize(Math.max(1,host.clientWidth),Math.max(1,host.clientHeight),false);last='';draw();};
    observer=new ResizeObserver(resize);observer.observe(host);resize();
    if(!renderer.info.programs?.length||renderer.info.programs.some(p=>p.diagnostics?.runnable===false))throw new Error('Shader unavailable');
    memory.classList.add('has-founders-webgl');
    canvas.addEventListener('webglcontextlost',e=>{e.preventDefault();dispose();},{once:true});
    return {draw,dispose};
  }catch(error){dispose();throw error;}
}
