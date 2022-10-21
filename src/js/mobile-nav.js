/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: モバイルビューの時に使用するJavaScriptです
 */
(() => {
  'use strict';
  // html確認
  if (!document.getElementById('main-screen')) return;
  /**
   * スライドナビの設定
   */
  let nav = {
    slideNav: [], /* スライドナビ ウイジェットの li が対象 */
    footerNav: [] /* フッターナビ用 ウイジェットの li が対象 */
  };

  /**
   * DOM読み込み後に実行
   */
  document.addEventListener('DOMContentLoaded', () => {
    if ('bbCfgMobileNav' in window) {
      nav = Object.assign(nav, bbCfgMobileNav);
    }
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
    const nameNavWindowContainer = '#nav-window-container';
    const nameNavWindowWidgets = '#nav-window-widgets';
    const nameOpenBtn = '#nav-window-open-btn';
    const nameCloseBtn = '#nav-window-close-btn';
    const nameShowBtn = 'nav-window-show';
    const $mainScreen = document.querySelector(nameMainScreen);
    $mainScreen.appendChild(createHtml('div', nameMainScreenMask, isMobile));
    const $navWindowArea = document.querySelector(nameNavWindowArea);
    if (!$navWindowArea) return;

    /**
     * メニュー／ウィジェットの追加
     */
    let navEnable = false;
    nav.slideNav.forEach((slide) => {
      const list = createHtml('ol', slide + '-slidenav');
      if (slide == globalNav || slide == headerNav) {
        const $nav = document.querySelector(slide + ' .menu');
        if ($nav) {
          navEnable = true;
          list.innerHTML = '<li>' + $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '') + '</li>';
          list.classList.add('widget_nav_menu');
        }
      } else {
        const $nav = document.querySelector(slide);
        if ($nav) {
          navEnable = true;
          list.innerHTML = $nav.outerHTML.replace(/ id=["|'].*?["|']/g, '');
        }
      }
      if (navEnable) {
        document.querySelector(nameNavWindowWidgets).appendChild(list);
      }
    });

    /**
     * 動作：サブメニュー表示の切り替え
     */
    const $itemAll = document.querySelectorAll(nameNavWindowContainer + ' .child-group');
    $itemAll.forEach(($item) => {
      $item.previousElementSibling.appendChild(createHtml('span', null, '.icon-toggle'));
      const $itemParent = $item.parentNode;
      $itemParent.classList.add('acoordion-menu');
      const maxHeight = (act) => {
        let height = 0;
        if (act == 'open') {
          const $subMenu = $item.querySelector('ul').getBoundingClientRect();
          height = parseInt($subMenu.height) + 25;
        }
        $item.style.maxHeight = `${height}px`;
      };
      $itemParent.querySelector('.icon-toggle').addEventListener('click', (e) => {
        $itemParent.classList.toggle('active');
        e.stopPropagation();
        e.preventDefault();
        if ($itemParent.classList.contains('active')) maxHeight('open');
        else maxHeight();
      });
    });

    /**
     * CLOSEボタンを追加
     */
    const closeBtn = createHtml('div', nameCloseBtn);
    closeBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    $navWindowArea.insertBefore(closeBtn, $navWindowArea.firstElementChild);

    /**
     * ヘッダーにOPENボタンを追加
     */
    const openBtn = createHtml('div', nameOpenBtn, isMobile);
    openBtn.appendChild(createHtml('span', null, '.btn-symbol'));
    const $globalHeader = document.querySelector('#global-header');
    if (!$globalHeader) return;
    /* 動作：ナビウインドウを開く */
    const toggleElement = [
      nameMainScreen,
      nameMainScreenMask,
      nameNavWindowArea,
      nameCloseBtn
    ];
    let posY = window.scrollY;
    window.addEventListener('load', () => {
      $globalHeader.appendChild(openBtn);
      posY = window.scrollY;
      const $navWindowOpenBtn = document.querySelector(nameOpenBtn);
      $navWindowOpenBtn && $navWindowOpenBtn.addEventListener('click', (e) => {
        posY = window.scrollY;
        document.body.classList.add(nameShowBtn);
        toggleElement.forEach((toggle) => {
          document.querySelector(toggle).classList.add(nameShowBtn);
        });
        e.preventDefault();
      });
    });
    /* 動作：ナビウインドウを閉じる */
    const closeToggle = [
      nameCloseBtn,
      nameMainScreenMask
    ];
    closeToggle.forEach((close) => {
      const $navWindowCloseBtn = document.querySelector(close);
      $navWindowCloseBtn && $navWindowCloseBtn.addEventListener('click', (e) => {
        toggleElement.forEach((toggle) => {
          window.scroll(0, posY);
          document.body.classList.remove(nameShowBtn);
          document.querySelector(toggle).classList.remove(nameShowBtn);
        });
        e.preventDefault();
      });
    });

    /**
     * ブラウザバック(ios)
     */
    window.addEventListener('pageshow', (e) => {
      toggleElement.forEach((toggle) => {
        window.scroll(0, posY);
        document.body.classList.remove(nameShowBtn);
        document.querySelector(toggle).classList.remove(nameShowBtn);
      });
      e.preventDefault();
    });

    /**
     * PC幅にリサイズでリセット
     */
    window.addEventListener('resize', () => {
      const bp = !window._BbBreakPoint ? 782 : window._BbBreakPoint;
      if (document.documentElement.clientWidth >= bp) {
        toggleElement.forEach((toggle) => {
          document.body.classList.remove(nameShowBtn);
          document.querySelector(toggle).classList.remove(nameShowBtn);
        });
      }
    });
  };

  /**
   * フッターナビを構成
   */
  const footerNav = () => {
    const list = createHtml('ul', null, isMobile);
    const _nav = nav.footerNav;
    _nav.forEach((footer) => {
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
