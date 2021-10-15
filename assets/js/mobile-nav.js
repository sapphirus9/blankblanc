"use strict";

/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: モバイルビューの時に使用するJavaScriptです
 */
(function () {
  'use strict';
  /**
   * スライドナビの設定
   */

  var nav = {
    slideNav: ["#search-2"],
    // スライドナビ ウイジェットの li が対象
    footerNav: [] // フッターナビ用 ウイジェットの li が対象

  };

  if ('bbCfgMobileNav' in window) {
    if (Object.assign) {
      nav = Object.assign(nav, bbCfgMobileNav);
    } else {
      // (ie11 measures)
      Object.keys(nav).forEach(function (key) {
        if (bbCfgMobileNav[key]) nav[key] = bbCfgMobileNav[key].concat(nav[key].filter(function (e) {
          return nav[key].indexOf(e) === -1;
        }));
      });
    }
  } // 生成


  document.addEventListener('DOMContentLoaded', function () {
    if (nav.slideNav.length > 0) slideNav();
    if (nav.footerNav.length > 0) footerNav();
  });
  /**
   * id, class定数
   */

  var isMobile = '.is-mobile';
  var globalNav = '#global-nav';
  var headerNav = '#header-nav';
  var globalFooter = '#global-footer';
  /**
   * id, class付きタグ要素を生成
   */

  var createHtml = function createHtml(tag, id, cls) {
    var element = document.createElement(tag);
    if (id) element.id = id.substring(1);
    if (cls) element.classList.add(cls.substring(1));
    return element;
  };
  /**
   * スライドナビを構成
   */


  var slideNav = function slideNav() {
    var nameMainScreen = '#main-screen';
    var nameMainScreenMask = '#main-screen-mask';
    var nameNavWindowArea = '#nav-window-area';
    var nameNavWindowScroll = '#nav-window-scroll';
    var nameOpenBtn = '#nav-window-open-btn';
    var nameCloseBtn = '#nav-window-close-btn';
    var nameShowBtn = 'nav-window-show';
    var $mainScreen = document.querySelector(nameMainScreen);
    $mainScreen.parentNode.insertBefore(createHtml('div', nameNavWindowArea, isMobile), $mainScreen.nextElementSibling);
    $mainScreen.appendChild(createHtml('div', nameMainScreenMask, isMobile));
    var $navWindowArea = document.querySelector(nameNavWindowArea);
    $navWindowArea.appendChild(createHtml('div', nameNavWindowScroll)); // メニュー／ウィジェットの追加

    Array.prototype.forEach.call(nav.slideNav, function (slide) {
      var list = createHtml('ol', slide + '-slidenav');

      if (slide == globalNav || slide == headerNav) {
        var $nav = document.querySelector(slide + ' .menu');
        list.innerHTML = '<li>' + $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '') + '</li>';
        list.classList.add('menu');
      } else {
        var _$nav = document.querySelector(slide);

        list.innerHTML = _$nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
      }

      document.querySelector(nameNavWindowScroll).appendChild(list);
    }); // 動作：サブメニュー表示の切り替え

    var subMenuItems = [nameNavWindowScroll + ' .menu > .menu-item > .sub-menu', nameNavWindowScroll + ' .widget_pages > ul > .page_item > .children', nameNavWindowScroll + ' .widget_categories > ul > .cat-item > .children'];
    Array.prototype.forEach.call(subMenuItems, function (items) {
      var $itemAll = document.querySelectorAll(items);
      Array.prototype.forEach.call($itemAll, function ($item) {
        $item.previousElementSibling.appendChild(createHtml('span', null, '.icon-toggle'));
        var $itemParent = $item.parentNode;
        $itemParent.classList.add('acoordion-menu');
        $itemParent.querySelector('.icon-toggle').addEventListener('click', function (e) {
          $itemParent.classList.toggle('active');
          e.stopPropagation();
          e.preventDefault();
        });
      });
    }); // CLOSEボタンを追加

    var closeBtn = createHtml('div', nameCloseBtn);
    closeBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    $navWindowArea.insertBefore(closeBtn, $navWindowArea.firstElementChild); // ヘッダーにOPENボタンを追加

    var openBtn = createHtml('div', nameOpenBtn, isMobile);
    openBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    document.querySelector('#global-header').appendChild(openBtn);
    var toggleElement = [nameMainScreen, nameMainScreenMask, nameNavWindowArea, nameCloseBtn];
    var posY = window.scrollY; // 動作：ナビウインドウを開く

    var $navWindowOpenBtn = document.querySelector(nameOpenBtn);
    $navWindowOpenBtn && $navWindowOpenBtn.addEventListener('click', function (e) {
      posY = window.scrollY;
      document.body.classList.add(nameShowBtn);
      Array.prototype.forEach.call(toggleElement, function (toggle) {
        document.querySelector(toggle).classList.add(nameShowBtn);
      });
      e.preventDefault();
    }); // 動作：ナビウインドウを閉じる

    var closeToggle = [nameCloseBtn, nameMainScreenMask];
    Array.prototype.forEach.call(closeToggle, function (close) {
      var $navWindowCloseBtn = document.querySelector(close);
      $navWindowCloseBtn && $navWindowCloseBtn.addEventListener('click', function (e) {
        Array.prototype.forEach.call(toggleElement, function (toggle) {
          window.scroll(0, posY);
          document.body.classList.remove(nameShowBtn);
          document.querySelector(toggle).classList.remove(nameShowBtn);
        });
        e.preventDefault();
      });
    }); // ブラウザバック(ios)

    window.addEventListener('pageshow', function (e) {
      document.body.classList.remove(nameShowBtn);
      e.preventDefault();
    });
  };
  /**
   * フッターナビを構成
   */


  var footerNav = function footerNav() {
    var list = createHtml('ul', null, isMobile);
    var _nav = nav.footerNav;
    Array.prototype.forEach.call(_nav, function (footer) {
      if (footer == globalNav || footer == headerNav) {
        var $nav = document.querySelector(footer + ' .menu');

        var _list = createHtml('li', footer + '-footernav', '.widget');

        _list.innerHTML = $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
        list.appendChild(_list);
      } else {
        var _$nav2 = document.querySelector(footer);

        var _list2 = document.createElement('div');

        _list2.innerHTML = _$nav2.outerHTML.replace(/ id=["|'].*?["|']/g, '');

        _list2.firstElementChild.setAttribute('id', footer.substring(1) + '-footernav');

        list.appendChild(_list2.firstChild);
      }

      var $globalFooter = document.querySelector(globalFooter + ' .footer-widgets');
      $globalFooter.insertBefore(list, $globalFooter.firstElementChild);
    });
  };
})();
//# sourceMappingURL=mobile-nav.js.map
