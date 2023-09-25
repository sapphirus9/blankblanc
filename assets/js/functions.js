!function(){"use strict";class e{constructor(){let e="";const t={os:"other",device:"desktop",browser:"other",touchevent:!1};window.navigator.userAgentData&&(e=navigator.userAgentData,t.os=e.platform.toLowerCase(),e.mobile&&(t.device="mobile"),e.brands.length&&e.brands.forEach((e=>{/Chromium/.test(e.brand)&&(t.browser="chromium")}))),"other"!=t.os&&"other"!=t.browser||(e=navigator.userAgent,/Windows/.test(e)?t.os="windows":/Macintosh/.test(e)?t.os="macosx":/Android/.test(e)?t.os="android":/iP.*?Mac OS X/.test(e)?t.os="ios":/Silk/.test(e)?t.os="firehd":/Linux/.test(e)&&(t.os="linux"),(/iPhone.*?Mac OS X/.test(e)||/Android.*?Mobile/.test(e))&&(t.device="mobile"),/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(e)?t.browser="safari":/Firefox/.test(e)?t.browser="firefox":/Chrome/.test(e)?t.browser="chromium":/Silk/.test(e)&&(t.browser="silk")),/windows/.test(t.os)?t.touchevent=!1:(/android|ios|firehd/.test(t.os)||void 0!==window.ontouchstart&&navigator.maxTouchPoints>0)&&(t.touchevent=!0),window.BbOptions.deviceInfo=t}addClass(){document.documentElement.classList.add("os-"+window.BbOptions.deviceInfo.os),document.documentElement.classList.add("device-"+window.BbOptions.deviceInfo.device),document.documentElement.classList.add("browser-"+window.BbOptions.deviceInfo.browser),window.BbOptions.deviceInfo.touchevent&&document.documentElement.classList.add("touch-device")}}class t{constructor(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},o={loaded:!1,offset:!0,speed:50,desktop:0,mobile:0,breakPoint:window.BbOptions.breakPoint};if(Object.keys(o).forEach((e=>{(t[e]||!1===t[e])&&(o[e]=t[e])})),"number"!=typeof e){const t="string"==typeof e?document.querySelector(e):e;if(t){const n=t.getBoundingClientRect();e=n.top+window.scrollY;const s=t.dataset.option;s&&s.split(",").forEach((e=>{const t=e.split(":");let n=t[1].trim();switch(n){case"true":n=!0;break;case"false":n=!1;break;default:n=Number(n)}o[t[0].trim()]=n}))}}o.offset&&(e-=document.documentElement.clientWidth<o.breakPoint?o.mobile:o.desktop,e<0&&(e=0));const n=window.scrollY;let s=null,r=!1,c="neutral";function a(t){t||(t=n);const i=Math.abs(e-t);t=parseInt(i-i*o.speed*o.speed/1e4),"down"==c?e<=(t=e-t)&&(r=!0):"up"==c&&e>=(t=e+t)&&(r=!0),window.scroll(0,t),r?cancelAnimationFrame(s):s=requestAnimationFrame((()=>a(t)))}n>e?c="up":n<e&&(c="down"),"neutral"!=c&&(o.loaded?window.addEventListener("load",(()=>a())):a())}}(()=>{if(!document.getElementById("main-screen"))return window.BbOptions=[],document.documentElement.id="blankblanc-wp-admin",void(new e).addClass();const o=()=>{const e=document.querySelector("#header-part");if(e){const t=e.getBoundingClientRect();window.scrollY>=parseInt(window.scrollY+t.top)?e.classList.add("fixed"):e.classList.remove("fixed")}},n=()=>{const e=.94*window.BbOptions.shrinkHeight;document.querySelectorAll('[data-bb-option="fade-in"]').forEach((t=>{const o=t.getBoundingClientRect();parseInt(o.top-e)<0&&t.classList.add("active")}))};let s={top:null,offset:null};const r=()=>{const e=document.querySelector("#second-column");if(!e||"none"==window.getComputedStyle(e).getPropertyValue("display"))return;const t=document.querySelector(".bottom-fixed-widget");if(!t)return;null===s.offset&&(t.classList.add("initial"),s.offset=parseInt(window.getComputedStyle(t).getPropertyValue("top")),t.classList.remove("initial"));const o=e.getBoundingClientRect(),n=t.getBoundingClientRect();if(o.height<=n.height)return;const r=window.scrollY+window.BbOptions.shrinkHeight;null===s.top&&(s.top=window.scrollY+n.top);const c=document.querySelector("#global-nav").getBoundingClientRect(),a=document.querySelector("#global-widget").getBoundingClientRect();window.BbOptions.shrinkHeight>parseInt(a.height-c.height)?t.classList.add("sticky"):(r>=parseInt(s.top+n.height)?t.classList.add("fixed"):t.classList.remove("fixed"),r>=parseInt(window.scrollY+o.bottom)?t.classList.add("absolute"):t.classList.remove("absolute"))},c=()=>{const e=document.querySelector("#main-screen"),t=parseInt(window.getComputedStyle(e).getPropertyValue("min-width"));window.BbOptions.shrinkRatio=t&&t>document.documentElement.clientWidth?parseFloat(t/document.documentElement.clientWidth):1,window.BbOptions.shrinkHeight=document.documentElement.clientHeight*window.BbOptions.shrinkRatio},a=782;let i={scrollCommon:{loaded:!1,offset:!0,speed:40,desktop:65,mobile:65,breakPoint:a},toAnchorLink:{loaded:!1,offset:!0,speed:95,desktop:65,mobile:65,breakPoint:a},scrollPageTop:{loaded:!1,offset:!1,speed:40,desktop:0,mobile:0,breakPoint:a},scrollForm:{loaded:!0,offset:!0,speed:40,desktop:80,mobile:65,breakPoint:a},breakPoint:a,pageLoaded:!1};document.addEventListener("DOMContentLoaded",(()=>{"bbOptions"in window&&Object.keys(bbOptions).forEach((e=>{const t=bbOptions[e];Object.keys(t).forEach((o=>{(t[o]||!1===t[o])&&(i[e][o]=t[o])}))})),window.BbOptions=i,(new e).addClass(),c(),document.querySelectorAll("img.background-image-src").forEach((e=>{const t=document.createElement("img");t.src=e.src,e.style.display="none",e.parentNode.classList.add("background-image-block");const o=document.createElement("div");o.classList.add("background-image-body"),o.style.backgroundImage="url("+t.src+")",e.parentNode.insertBefore(o,e),o.appendChild(e),t.addEventListener("load",(()=>{o.parentNode.classList.add("show")}))})),(()=>{const e=document.querySelector(".more-link");e&&e.addEventListener("click",(t=>{e.classList.add("active"),document.querySelector(".more-content").classList.add("active"),t.preventDefault()}))})(),document.querySelectorAll("select").forEach((e=>{e.outerHTML='<div class="select-area">'+e.outerHTML+"</div>"})),(()=>{const e=document.querySelector("#gotop-button");if(!e)return;const o=document.createElement("div");["gotop-cfg","gotop-start","gotop-end"].forEach((e=>{o.classList.add(e)})),e.parentNode.insertBefore(o,e);const n=window.getComputedStyle(o),s=parseInt(n.getPropertyValue("top"))||0,r=parseInt(n.getPropertyValue("bottom"))||0;["load","scroll","resize"].forEach((t=>{window.addEventListener(t,(()=>{const t=window.scrollY,o=document.documentElement.scrollHeight-window.BbOptions.shrinkHeight;s<t?e.classList.add("gotop-show"):e.classList.remove("gotop-show"),o-r<t?(e.classList.add("gotop-bottom"),e.classList.add("gotop-end")):(e.classList.remove("gotop-bottom"),e.classList.remove("gotop-end"))}))}));const c=e.querySelector(".gotop-symbol");c&&c.addEventListener("click",(()=>{new t(0,Object.create(i.scrollPageTop))}))})(),(()=>{const e=document.querySelectorAll('a[href*="#"]'),o=location.href.split("#");e&&e.forEach((e=>{e.addEventListener("click",(n=>{const s=e.getAttribute("href").split("#"),r=document.createElement("a");if(r.href=s[0],s[0]&&o[0].replace(/(https?:)?\/\/(.*?)\/?$/,"$2")!=r.href.replace(/(https?:)?\/\/(.*?)\/?$/,"$2")||!s[1]||s[1].match(/respond/)||s[1].match(/more\-.*?$/))return;const c=document.querySelector("#"+s[1]);return c&&(n.preventDefault(),new t(c,Object.create(i.scrollCommon)),["#main-screen","#main-screen-mask","#nav-window-area","#nav-window-close-btn"].forEach((e=>{document.body.classList.remove("nav-window-show");const t=document.querySelector(e);t&&t.classList.remove("nav-window-show")}))),n.preventDefault(),!1}))}))})(),(()=>{const e=location.hash;if(e){const o=document.querySelector(e);new t(o,Object.create(i.toAnchorLink))}})(),document.querySelectorAll(".search-form").forEach((e=>{["mouseenter","mouseleave"].forEach((t=>{e.querySelector(".search-submit").addEventListener(t,(()=>{e.querySelector("label").classList.toggle("hover")}))}))})),document.querySelectorAll(".bb-toc-block").forEach((e=>{const t=e.querySelector(".bb-toc-toggle"),o=()=>{const t=e.querySelector(".bb-toc-body-inner").getBoundingClientRect(),o=parseInt(t.height)+25;e.querySelector(".bb-toc-body").style.maxHeight=`${o}px`};window.addEventListener("load",o),window.addEventListener("resize",o),t&&t.addEventListener("click",(()=>{e.classList.toggle("changed")}))})),(()=>{const e=["#header-nav","#global-nav"];e.forEach((t=>{const o=document.querySelector(t);if(!o)return;const n=o.querySelectorAll(".menu > .menu-item-has-children");let s="";n.forEach((o=>{const n=e=>{let t=0;if("open"==e){const e=o.querySelector(".sub-menu").getBoundingClientRect();t=parseInt(e.height)+25}o.querySelector(".child-group").style.maxHeight=`${t}px`};window.BbOptions.deviceInfo.touchevent?(o.addEventListener("touchstart",(()=>{t==e[1]&&document.querySelector("#main-container").classList.add("gnav-active"),n("open"),o.classList.add("menu-active"),s=o,o.classList.contains("touchstart")||o.classList.add("touchstart")}),{passive:!0}),o.addEventListener("mouseout",(()=>{t==e[1]&&s===o&&document.querySelector("#main-container").classList.remove("gnav-active"),n(),o.classList.remove("menu-active"),o.classList.remove("touchstart")}))):(o.addEventListener("mouseover",(()=>{t==e[1]&&document.querySelector("#main-container").classList.add("gnav-active"),n("open")})),o.addEventListener("mouseout",(()=>{t==e[1]&&document.querySelector("#main-container").classList.remove("gnav-active"),n()})))}))}))})(),(()=>{const e=document.querySelectorAll(".bb-form-style"),o=i.scrollForm?Object.create(i.scrollForm):Object.create(i.scrollCommon);o.loaded=!0,e.forEach((e=>{if(!e)return;const n=e.querySelectorAll(".group");let s=!0;n.forEach((e=>{const n=e.querySelector(".error");if(n){s&&(new t(e,o),s=!1),e.classList.add("group-error");const r=["click","focus"];["input","select","textarea",".error"].forEach((t=>{r.forEach((o=>{e.querySelectorAll(t).forEach((t=>{t&&t.addEventListener(o,(()=>{e.classList.remove("group-error"),n.classList.add("error-hidden")}))}))}))}))}}));const r="submitBack",c=e.querySelector('[name="submitBack"]');if(c&&c.addEventListener("click",(()=>{localStorage.setItem(r,1)})),1==localStorage.getItem(r)){const n=document.createElement("div");n.classList.add(".bb-form-style-top".substring(1)),e.parentNode.insertBefore(n,e),new t(n,o),localStorage.removeItem(r)}}))})(),r(),document.querySelectorAll(".set-user-action").forEach((e=>{e.addEventListener("click",(()=>{r()}))})),setTimeout((()=>{window.BbOptions.pageLoaded||(document.documentElement.classList.add("page-load-abort"),document.documentElement.classList.add("page-loaded"))}),3e3)})),window.addEventListener("load",(()=>{document.querySelectorAll(".entry-body table").forEach((e=>{const t=document.createElement("div");t.classList.add("table-content");const o=document.createElement("div");o.classList.add("table-area");const n=document.createElement("div");n.classList.add("table-arrow"),n.classList.add("table-arrow-left");const s=document.createElement("div");s.classList.add("table-arrow"),s.classList.add("table-arrow-right"),o.innerHTML=e.outerHTML,t.appendChild(o),o.appendChild(n),o.appendChild(s);const r=e.offsetWidth;e.parentNode.insertBefore(t,e),e.parentNode.removeChild(e);const c=()=>{const e=r-o.offsetWidth,t=o.scrollLeft;3<=t?n.classList.add("active"):n.classList.remove("active"),e-3>=t?s.classList.add("active"):s.classList.remove("active")};c(),o.addEventListener("scroll",c)})),o(),n(),(()=>{const e="cookieBanner",t=document.querySelector("#cookie-banner");if(!t)return void localStorage.removeItem(e);const o=t.dataset.expire?parseInt(t.dataset.expire):365,n=localStorage.getItem(e),s=new Date;(null==n||s.getTime()>parseInt(n))&&(t.classList.add("indicate"),t.querySelector(".cookie-btn").addEventListener("click",(()=>{localStorage.setItem(e,s.getTime()+86400*o*1e3),t.classList.remove("indicate")})))})(),document.querySelectorAll(".embeded-iframe-src").forEach((function(e){e.getAttribute("data-src")&&e.setAttribute("src",e.getAttribute("data-src"))})),document.documentElement.classList.add("page-loaded"),window.BbOptions.pageLoaded=!0})),window.addEventListener("pageshow",(()=>{r()})),window.addEventListener("scroll",(()=>{o(),r(),n()})),window.addEventListener("resize",(()=>{c(),o(),r(),n()}))})()}();