document.querySelector(".menu-toggle").addEventListener("click",function(){var m=document.querySelector(".main-nav");m.classList.toggle("is-open");this.setAttribute("aria-expanded",m.classList.contains("is-open"))});

document.querySelectorAll(".mega-menu > a").forEach(function(a){a.addEventListener("click",function(e){e.preventDefault();var p=this.parentElement;var w=p.classList.contains("is-open");document.querySelectorAll(".mega-menu.is-open").forEach(function(m){m.classList.remove("is-open")});if(!w)p.classList.add("is-open")})});

document.addEventListener("click",function(e){if(!e.target.closest(".mega-menu"))document.querySelectorAll(".mega-menu.is-open").forEach(function(m){m.classList.remove("is-open")})});
