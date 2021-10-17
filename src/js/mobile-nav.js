/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: モバイルビューの時に使用するJavaScriptです
 */
(() => {
  'use strict';
  /**
   * スライドナビの設定
   */
  let nav = {
    slideNav: [], // スライドナビ ウイジェットの li が対象
    footerNav: [] // フッターナビ用 ウイジェットの li が対象
  };
  if ('bbCfgMobileNav' in window) {
    if (Object.assign) {
      nav = Object.assign(nav, bbCfgMobileNav);
    } else { // (ie11 measures)
      Object.keys(nav).forEach((key) => {
        if (bbCfgMobileNav[key]) nav[key] = bbCfgMobileNav[key].concat(nav[key].filter((e) => nav[key].indexOf(e) === -1));
      });
    }
  }

  // 生成
  document.addEventListener('DOMContentLoaded', () => {
    if (nav.slideNav.length > 0) slideNav();
    if (nav.footerNav.length > 0) footerNav();
  });

  /**
   * id, class定数
   */
  const isMobile = '.is-mobile';
  const globalNav = '#global-nav';
  const headerNav = '#header-nav';
  const globalFooter = '#global-footer';

  /**
   * id, class付きタグ要素を生成
   */
  const createHtml = (tag, id, cls) => {
    const element = document.createElement(tag);
    if (id) element.id = id.substring(1);
    if (cls) element.classList.add(cls.substring(1));
    return element;
  };

  /**
   * スライドナビを構成
   */
  const slideNav = () => {
    const nameMainScreen = '#main-screen';
    const nameMainScreenMask = '#main-screen-mask';
    const nameNavWindowArea = '#nav-window-area';
    const nameNavWindowScroll = '#nav-window-scroll';
    const nameOpenBtn = '#nav-window-open-btn';
    const nameCloseBtn = '#nav-window-close-btn';
    const nameShowBtn = 'nav-window-show';
    const $mainScreen = document.querySelector(nameMainScreen);
    $mainScreen.parentNode.insertBefore(createHtml('div', nameNavWindowArea, isMobile), $mainScreen.nextElementSibling);
    $mainScreen.appendChild(createHtml('div', nameMainScreenMask, isMobile));
    const $navWindowArea = document.querySelector(nameNavWindowArea);
    $navWindowArea.appendChild(createHtml('div', nameNavWindowScroll));

    // メニュー／ウィジェットの追加
    Array.prototype.forEach.call(nav.slideNav, (slide) => {
      const list = createHtml('ol', slide + '-slidenav');
      if (slide == globalNav || slide == headerNav) {
        const $nav = document.querySelector(slide + ' .menu');
        list.innerHTML = '<li>' + $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '') + '</li>';
        list.classList.add('menu');
      } else {
        const $nav = document.querySelector(slide);
        list.innerHTML = $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
      }
      document.querySelector(nameNavWindowScroll).appendChild(list);
    });

    // 動作：サブメニュー表示の切り替え
    const subMenuItems = [
      nameNavWindowScroll + ' .menu > .menu-item > .sub-menu',
      nameNavWindowScroll + ' .widget_pages > ul > .page_item > .children',
      nameNavWindowScroll + ' .widget_categories > ul > .cat-item > .children'
    ];
    Array.prototype.forEach.call(subMenuItems, (items) => {
      const $itemAll = document.querySelectorAll(items);
      Array.prototype.forEach.call($itemAll, ($item) => {
        $item.previousElementSibling.appendChild(createHtml('span', null, '.icon-toggle'));
        const $itemParent = $item.parentNode;
        $itemParent.classList.add('acoordion-menu');
        $itemParent.querySelector('.icon-toggle').addEventListener('click', (e) => {
          $itemParent.classList.toggle('active');
          e.stopPropagation();
          e.preventDefault();
        });
      });
    });

    // CLOSEボタンを追加
    const closeBtn = createHtml('div', nameCloseBtn);
    closeBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    $navWindowArea.insertBefore(closeBtn, $navWindowArea.firstElementChild);
    // ヘッダーにOPENボタンを追加
    const openBtn = createHtml('div', nameOpenBtn, isMobile);
    openBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    document.querySelector('#global-header').appendChild(openBtn);
    const toggleElement = [
      nameMainScreen,
      nameMainScreenMask,
      nameNavWindowArea,
      nameCloseBtn
    ];
    let posY = window.scrollY;
    // 動作：ナビウインドウを開く
    const $navWindowOpenBtn = document.querySelector(nameOpenBtn);
    $navWindowOpenBtn && $navWindowOpenBtn.addEventListener('click', (e) => {
      posY = window.scrollY;
      document.body.classList.add(nameShowBtn);
      Array.prototype.forEach.call(toggleElement, (toggle) => {
        document.querySelector(toggle).classList.add(nameShowBtn);
      });
      e.preventDefault();
    });
    // 動作：ナビウインドウを閉じる
    const closeToggle = [
      nameCloseBtn,
      nameMainScreenMask
    ];
    Array.prototype.forEach.call(closeToggle, (close) => {
      const $navWindowCloseBtn = document.querySelector(close);
      $navWindowCloseBtn && $navWindowCloseBtn.addEventListener('click', (e) => {
        Array.prototype.forEach.call(toggleElement, (toggle) => {
          window.scroll(0, posY);
          document.body.classList.remove(nameShowBtn);
          document.querySelector(toggle).classList.remove(nameShowBtn);
        });
        e.preventDefault();
      });
    });
    // ブラウザバック(ios)
    window.addEventListener('pageshow', (e) => {
      document.body.classList.remove(nameShowBtn);
      e.preventDefault();
    });
  };

  /**
   * フッターナビを構成
   */
  const footerNav = () => {
    const list = createHtml('ul', null, isMobile);
    const _nav = nav.footerNav;
    Array.prototype.forEach.call(_nav, (footer) => {
      if (footer == globalNav || footer == headerNav) {
        const $nav = document.querySelector(footer + ' .menu');
        const _list = createHtml('li', footer + '-footernav', '.widget');
        _list.innerHTML = $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
        list.appendChild(_list);
      } else {
        const $nav = document.querySelector(footer);
        const _list = document.createElement('div');
        _list.innerHTML = $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
        _list.firstElementChild.setAttribute('id', footer.substring(1) + '-footernav');
        list.appendChild(_list.firstChild);
      }
      const $globalFooter = document.querySelector(globalFooter + ' .footer-widgets');
      $globalFooter.insertBefore(list, $globalFooter.firstElementChild);
    });
  };
})();
