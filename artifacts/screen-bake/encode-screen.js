// Build chapter-02 sequence-screen: baked frames where the UI is on screen, originals elsewhere.
const sharp=require('sharp'),fs=require('fs');
const BAKE='C:/Users/patri/AppData/Local/Temp/claude/D--webprojects-keysoftitalia/2d4a8b72-f6ad-45ff-9237-c3e5cc89e21d/scratchpad/bake/';
const B='D:/webprojects/keysoftitalia/assets/img/home/cinematic-v2/chapter-02/';
sharp.concurrency(2);
(async()=>{for(const [v,dir] of (process.env.ONLY==='desktop'?[['desktop','full-d']]:[['desktop','full-d'],['mobile','full-m']])){
 const src=B+v+'/sequence/',dst=B+v+'/sequence-screen/';fs.mkdirSync(dst,{recursive:true});let baked=0;
 const jobs=[];for(let i=0;i<192;i++)jobs.push(i);
 const work=async()=>{while(jobs.length){const i=jobs.shift();const n='frame-'+String(i).padStart(4,'0');const png=BAKE+dir+'/'+n+'.png';
  if(fs.existsSync(png)){baked++;await sharp(png).webp({quality:80}).toFile(dst+n+'.webp');await sharp(png).avif({quality:55,effort:4}).toFile(dst+n+'.avif');}
  else{fs.copyFileSync(src+n+'.webp',dst+n+'.webp');fs.copyFileSync(src+n+'.avif',dst+n+'.avif');}}};
 await Promise.all([work(),work(),work(),work()]);console.log(v,'baked',baked,'of 192');}})();
