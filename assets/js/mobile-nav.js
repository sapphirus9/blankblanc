!function(){"use strict";var e={slideNav:[],footerNav:[]};document.addEventListener("DOMContentLoaded",(function(){"bbCfgMobileNav"in window&&(Object.assign?e=Object.assign(e,bbCfgMobileNav):Object.keys(e).forEach((function(n){bbCfgMobileNav[n]&&(e[n]=bbCfgMobileNav[n].concat(e[n].filter((function(t){return-1===e[n].indexOf(t)}))))}))),e.slideNav.length>0&&r(),e.footerNav.length>0&&a()}));var n=".is-mobile",t="#global-nav",o="#header-nav",i=function(e,n,t){var o=document.createElement(e);return n&&(o.id=n.substring(1)),t&&o.classList.add(t.substring(1)),o},r=function(){var r="#main-screen",a="#main-screen-mask",c="#nav-window-area",l="#nav-window-scroll",d="#nav-window-open-btn",s="#nav-window-close-btn",u="nav-window-show",v=document.querySelector(r);v.parentNode.insertBefore(i("div",c,n),v.nextElementSibling),v.appendChild(i("div",a,n));var f=document.querySelector(c);f.appendChild(i("div",l)),e.slideNav.forEach((function(e){var n=i("ol",e+"-slidenav");if(e==t||e==o){var r=document.querySelector(e+" .menu");r&&(n.innerHTML="<li>"+r.outerHTML.replace(/ id=["|'].*?["|']/g,"")+"</li>",n.classList.add("widget_nav_menu"))}else{var a=document.querySelector(e);a&&(n.innerHTML=a.outerHTML.replace(/ id=["|'].*?["|']/g,""))}document.querySelector(l).appendChild(n)})),document.querySelectorAll("#nav-window-scroll .child-group").forEach((function(e){e.previousElementSibling.appendChild(i("span",null,".icon-toggle"));var n=e.parentNode;n.classList.add("acoordion-menu");var t=function(n){var t=0;if("open"==n){var o=e.querySelector("ul").getBoundingClientRect();t=parseInt(o.height)+25}e.style.maxHeight="".concat(t,"px")};n.querySelector(".icon-toggle").addEventListener("click",(function(e){n.classList.toggle("active"),e.stopPropagation(),e.preventDefault(),n.classList.contains("active")?t("open"):t()}))}));var m=i("div",s);m.appendChild(i("span",null,".btn-symbol")),f.insertBefore(m,f.firstElementChild);var p=i("div",d,n);p.appendChild(i("span",null,".btn-symbol")),document.querySelector("#global-header").appendChild(p);var b=[r,a,c,s],g=window.scrollY,w=document.querySelector(d);w&&w.addEventListener("click",(function(e){g=window.scrollY,document.body.classList.add(u),b.forEach((function(e){document.querySelector(e).classList.add(u)})),e.preventDefault()})),[s,a].forEach((function(e){var n=document.querySelector(e);n&&n.addEventListener("click",(function(e){b.forEach((function(e){window.scroll(0,g),document.body.classList.remove(u),document.querySelector(e).classList.remove(u)})),e.preventDefault()}))})),window.addEventListener("pageshow",(function(e){b.forEach((function(e){window.scroll(0,g),document.body.classList.remove(u),document.querySelector(e).classList.remove(u)})),e.preventDefault()})),window.addEventListener("resize",(function(){var e=window._BbBreakPoint?window._BbBreakPoint:768;document.documentElement.clientWidth>=e&&b.forEach((function(e){document.body.classList.remove(u),document.querySelector(e).classList.remove(u)}))}))},a=function(){var r=i("ul",null,n);e.footerNav.forEach((function(e){if(e==t||e==o){var n=document.querySelector(e+" .menu"),a=i("li",e+"-footernav",".widget");a.innerHTML=n.outerHTML.replace(/ id=["|'].*?["|']/g,""),r.appendChild(a)}else{var c=document.querySelector(e),l=document.createElement("div");l.innerHTML=c.outerHTML.replace(/ id=["|'].*?["|']/g,""),l.firstElementChild.setAttribute("id",e.substring(1)+"-footernav"),r.appendChild(l.firstChild)}var d=document.querySelector("#global-footer .footer-widgets");d.insertBefore(r,d.firstElementChild)}))}}();