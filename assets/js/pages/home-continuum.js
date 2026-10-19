// The film owns the people chapter; the closing content follows ordinary scroll.
// Each block arrives once as it enters the viewport: visible by default, nothing runs on scroll.
(()=>{
 const reduced=matchMedia('(prefers-reduced-motion: reduce)');
 if(reduced.matches||!('IntersectionObserver' in window)||!Element.prototype.animate)return;
 const items=[...document.querySelectorAll('.continuum-shortcuts > *, .continuum-faq > div, .continuum-flyer, .continuum-contact > .home-shell > *')];
 const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
  if(!entry.isIntersecting)return;
  observer.unobserve(entry.target);
  // Restored positions above the fold stay put instead of animating.
  if(document.visibilityState==='hidden'||entry.boundingClientRect.bottom<0)return;
  const el=entry.target,lateral=el.id==='home-contact-title'?-(innerWidth<768?24:100):0;
  el.animate([{opacity:.55,transform:`translate3d(${lateral}px,24px,0)`},{opacity:1,transform:'none'}],{duration:700,easing:'cubic-bezier(.23,1,.32,1)',fill:'backwards'});
 }),{rootMargin:'0px 0px -4% 0px'});
 items.forEach(el=>observer.observe(el));
 reduced.addEventListener('change',e=>{if(e.matches)observer.disconnect();});
})();
