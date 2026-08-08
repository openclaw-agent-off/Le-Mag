// Mobile menu toggle
document.querySelector(".menu-toggle").addEventListener("click",function(){var m=document.querySelector(".main-nav");m.classList.toggle("is-open");this.setAttribute("aria-expanded",m.classList.contains("is-open"))});

// Mega menu mobile click
document.querySelectorAll(".mega-menu > a").forEach(function(a){a.addEventListener("click",function(e){if(window.innerWidth>768)return;e.preventDefault();var p=this.parentElement;var w=p.classList.contains("is-open");document.querySelectorAll(".mega-menu.is-open").forEach(function(m){m.classList.remove("is-open")});if(!w)p.classList.add("is-open")})});
document.addEventListener("click",function(e){if(!e.target.closest(".mega-menu"))document.querySelectorAll(".mega-menu.is-open").forEach(function(m){m.classList.remove("is-open")})});

// Reading progress bar
(function(){var bar=document.querySelector(".reading-progress-bar");if(!bar)return;var article=document.querySelector(".article-content");var target=article||document.documentElement;function update(){var h=target.scrollHeight||target.offsetHeight;var s=window.scrollY||window.pageYOffset;var p=Math.min(100,Math.round(s/(h-window.innerHeight)*100));bar.style.width=p+"%"}window.addEventListener("scroll",update,{passive:true});window.addEventListener("resize",update);update()})();
