// Mobile menu toggle — close on link click
(function(){
var toggle=document.querySelector(".menu-toggle");
var nav=document.querySelector(".main-nav");
if(!toggle||!nav)return;
toggle.addEventListener("click",function(){
nav.classList.toggle("is-open");
var open=nav.classList.contains("is-open");
this.setAttribute("aria-expanded",open);
document.body.style.overflow=open?'hidden':'';
});
nav.querySelectorAll("a").forEach(function(a){
a.addEventListener("click",function(){
if(window.innerWidth<=768)nav.classList.remove("is-open");
});
});
})();

// Reading progress bar
document.addEventListener('DOMContentLoaded',function(){
var bar=document.querySelector('.reading-progress-bar');
if(!bar)return;
var article=document.querySelector('.article-content');
var target=article||document.documentElement;
function update(){
var h=target.scrollHeight;
var s=window.scrollY||window.pageYOffset;
var max=h-window.innerHeight;
var p=max>0?Math.min(100,Math.round(s/max*100)):0;
bar.style.width=p+'%';
}
window.addEventListener('scroll',update,{passive:true});
window.addEventListener('resize',update);
update();
});
