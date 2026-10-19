// Screen corners measured in the supplied chapter-02 clips, in source pixels.
// Interpolate the camera track; use the frame actually painted, never a pending target.
const tracks={
 desktop:[
  [24,[[120,78],[596,163],[772,607],[358,695]]],
  [48,[[208,80],[720,165],[892,635],[433,722]]],
  [72,[[239,61],[865,163],[1070,692],[476,802]]],
  [96,[[248,30],[1048,152],[1251,750],[484,884]]],
  [120,[[239,-15],[1220,95],[1450,879],[462,1020]]],
  [144,[[174,-98],[1398,69],[1669,938],[375,1190]]],
  [168,[[66,-260],[1588,-23],[1850,1040],[284,1400]]],
 ],
 mobile:[
  [24,[[-180,400],[220,500],[420,1070],[-80,1230]]],
  [48,[[-115,350],[508,471],[718,1137],[133,1284]]],
  [72,[[-30,235],[701,420],[992,1230],[217,1405]]],
  [96,[[20,70],[927,335],[1260,1300],[296,1560]]],
  [120,[[0,-65],[1045,256],[1438,1442],[242,1660]]],
  [144,[[-55,-160],[1070,187],[1560,1450],[15,1750]]],
  [168,[[-270,-520],[1270,-15],[1830,1660],[-185,2120]]],
 ],
};
const clamp=value=>Math.max(0,Math.min(1,value));
function surface(corners){
 const [[x0,y0],[x1,y1],[x2,y2],[x3,y3]]=corners;
 const dx1=x1-x2,dx2=x3-x2,dx3=x0-x1+x2-x3;
 const dy1=y1-y2,dy2=y3-y2,dy3=y0-y1+y2-y3;
 const denominator=dx1*dy2-dx2*dy1;
 const g=(dx3*dy2-dx2*dy3)/denominator,h=(dx1*dy3-dx3*dy1)/denominator;
 return [x1-x0+g*x1,x3-x0+h*x3,x0,y1-y0+g*y1,y3-y0+h*y3,y0,g,h];
}
function point(matrix,u,v){
 const [a,b,c,d,e,f,g,h]=matrix,w=g*u+h*v+1;
 return [(a*u+b*v+c)/w,(d*u+e*v+f)/w];
}
function cssMatrix(corners){
 const [a,b,c,d,e,f,g,h]=surface(corners);
 return `matrix3d(${[a/640,d/640,0,g/640,b/400,e/400,0,h/400,0,0,1,0,c,f,0,1].join(',')})`;
}
export function createScreenAssistance(element){
 if(!element)return {hide(){},paint(){}};
 const scan=element.querySelector('.screen-assistance-scan');
 const steps=[...element.querySelectorAll('.screen-assistance-step')];
 return {
  hide(){element.style.opacity='0';element.style.visibility='hidden';},
  paint(frame,mobile,placement){
   const index=frame-240;
   const enter=clamp((index-34)/18),exit=clamp((index-126)/24);
   const opacity=enter*(1-exit);
   element.style.opacity=opacity.toFixed(4);
   element.style.visibility=opacity>.001?'visible':'hidden';
   if(opacity<=.001)return;
   const track=tracks[mobile?'mobile':'desktop'];
   const right=track.findIndex(key=>key[0]>=index);
   const end=track[right<0?track.length-1:right],start=track[Math.max(0,right-1)];
   const t=clamp((index-start[0])/Math.max(1,end[0]-start[0]));
   const corners=start[1].map((p,n)=>p.map((value,axis)=>value+(end[1][n][axis]-value)*t));
   const projection=surface(corners);
   const bounds=mobile?[[.08,.17],[.92,.17],[.92,.82],[.08,.82]]:[[.10,.13],[.90,.13],[.90,.85],[.10,.85]];
   const projected=bounds.map(([u,v])=>point(projection,u,v).map((value,axis)=>value*placement.scale+(axis===0?placement.x:placement.y)));
   element.style.transform=cssMatrix(projected);
   element.dataset.frame=String(index);
   element.dataset.phase=index<72?'diagnosis':index<104?'intervention':'verification';
   scan.style.transform=`translate3d(0,${(clamp((index-42)/82)*204).toFixed(2)}px,0)`;
   steps.forEach((step,n)=>{
    const value=clamp((index-(44+n*27))/25);
    step.style.setProperty('--step-progress',value.toFixed(4));
    step.style.opacity=(.36+.64*value).toFixed(4);
    step.style.transform=`translate3d(${((1-value)*24).toFixed(2)}px,0,0)`;
   });
  },
 };
}
