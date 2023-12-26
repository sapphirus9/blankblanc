/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです
 */

'use strict';
/**
 * ブラウザー確認
 * element: html
 */
class BbSetUserAgent
{
  constructor() {
    let ua = '';
    const config = {
      os: 'other',
      device: 'desktop',
      browser: 'other',
      touchevent:false
    };
    if (window.navigator.userAgentData) {
      ua = navigator.userAgentData;
      config.os = ua.platform.toLowerCase();
      if (ua.mobile) config.device = 'mobile';
      if (ua.brands.length) {
        ua.brands.forEach((b) => {
          if (/Chromium/.test(b.brand)) config.browser = 'chromium';
        });
      }
    }
    if (config.os == 'other' || config.browser == 'other') {
      ua = navigator.userAgent;
      /* OS */
      if (/Windows/.test(ua)) config.os = 'windows';
      else if (/Macintosh/.test(ua)) config.os = 'macosx';
      else if (/Android/.test(ua)) config.os = 'android';
      else if (/iP.*?Mac OS X/.test(ua)) config.os = 'ios';
      else if (/Silk/.test(ua)) config.os = 'firehd';
      else if (/Linux/.test(ua)) config.os = 'linux';
      /* Device */
      if (/iPhone.*?Mac OS X/.test(ua)) config.device = 'mobile';
      else if (/Android.*?Mobile/.test(ua)) config.device = 'mobile';
      /* Browser */
      if (/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(ua)) config.browser = 'safari';
      else if (/Firefox/.test(ua)) config.browser = 'firefox';
      else if (/Chrome/.test(ua)) config.browser = 'chromium';
      else if (/Silk/.test(ua)) config.browser = 'silk';
    }
    if (/windows/.test(config.os)) config.touchevent = false;
    else if (/android|ios|firehd/.test(config.os)) config.touchevent = true;
    else if (window.ontouchstart !== undefined && navigator.maxTouchPoints > 0) config.touchevent = true;
    window.BbOptions.deviceInfo = config;
  };
  addClass() {
    document.documentElement.classList.add('os-' + window.BbOptions.deviceInfo.os);
    document.documentElement.classList.add('device-' + window.BbOptions.deviceInfo.device);
    document.documentElement.classList.add('browser-' + window.BbOptions.deviceInfo.browser);
    if (window.BbOptions.deviceInfo.touchevent) document.documentElement.classList.add('touch-device');
  };
};

/**
 * ウィンドウスクロール
 */
class BbSmoothScroll {
  constructor(position = 0, option = {}) {
    let init = {
      loaded: false,
      offset: true,
      speed: 50,
      desktop: 0,
      mobile: 0,
      breakPoint: window.BbOptions.breakPoint
    };
    /**
     * loaded: ページの表示完了後に実行（ページ遷移時など）
     * speed: スクロール速度（~100:と大きいほど速い）
     * offset: falseで以下の設定を無効化
     * desktop: Desktop表示時の上部マージン
     * mobile: Mobile表示時の上部マージン
     * breakPoint: 画面幅でモバイル/PCのoffsetを切替
     * アンカーリンク先にタグにdata-optionで個別指定
     * e.g.) data-option="speed:25,desktop:30,mobile:60"
     */
    Object.keys(init).forEach((key) => {
      if (option[key] || option[key] === false) init[key] = option[key];
    });
    if ('number' != typeof (position)) {
      const $targetElem = 'string' == typeof (position) ? document.querySelector(position) : position;
      if ($targetElem) {
        const rect = $targetElem.getBoundingClientRect();
        position = rect.top + window.scrollY;
        const dataOptions = $targetElem.dataset.option;
        if (dataOptions) {
          const _dataOptions = dataOptions.split(',');
          _dataOptions.forEach((_dataOption) => {
            const data = _dataOption.split(':');
            let data1 = data[1].trim();
            switch (data1) {
              case 'true': data1 = true; break;
              case 'false': data1 = false; break;
              default: data1 = Number(data1); break;
            }
            init[data[0].trim()] = data1;
          });
        }
      }
    }
    if (init.offset) {
      position = position - (document.documentElement.clientWidth < init.breakPoint ? init.mobile : init.desktop);
      if (position < 0) position = 0;
    }
    const currentPos = window.scrollY;
    let timer = null;
    let cancel = false;
    let direction = 'neutral'
    if (currentPos > position) direction = 'up';
    else if (currentPos < position) direction = 'down';
    /* scroll */
    function scrolling(setPos) {
      if (!setPos) setPos = currentPos;
      const distance = Math.abs(position - setPos);
      setPos = parseInt(distance - distance * init.speed * init.speed / 10000);
      if (direction == 'down') {
        setPos = position - setPos;
        if (position <= setPos) cancel = true;
      } else if (direction == 'up') {
        setPos = position + setPos;
        if (position >= setPos) cancel = true;
      }
      window.scroll(0, setPos);
      if (cancel) {
        cancelAnimationFrame(timer);
        return;
      }
      timer = requestAnimationFrame(() => scrolling(setPos));
    };
    if (direction != 'neutral') {
      if (init.loaded) window.addEventListener('load', () => scrolling());
      else scrolling();
    }
  }
}

/**
 * -------------------------
 * main
 * -------------------------
 */
this.BbOptions = {};
(() => {
  // html確認
  if (!document.getElementById('main-screen')) {
    window.BbOptions = [];
    document.documentElement.id = 'blankblanc-wp-admin';
    new BbSetUserAgent().addClass();
    return;
  }

  /**
   * 画像をバックグラウンドイメージに配置
   * attr: .background-image-src
   */
  const _BackgroundImage = () => {
    const $imgAll = document.querySelectorAll('img.background-image-src');
    let num = 0;
    let loaded = 0;
    $imgAll.forEach(($img) => {
      num++;
      const _img = document.createElement('img');
      _img.src = $img.src;
      $img.style.display = 'none';
      $img.parentNode.classList.add('background-image-block');
      const $body = document.createElement('div');
      $body.classList.add('background-image-body', 'background-image-body-' + num);
      $body.style.backgroundImage = 'url(' + _img.src + ')';
      $img.parentNode.insertBefore($body, $img);
      $body.appendChild($img);
      _img.addEventListener('load', () => {
        loaded++;
        if ($imgAll.length === loaded) {
          $body.parentNode.classList.add('show');
        }
      });
    });
  };

  /**
   * 続きを読む
   * attr: .more-link
   */
  const _MoreContent = () => {
    const $moreLink = document.querySelector('.more-link');
    $moreLink && $moreLink.addEventListener('click', (e) => {
      $moreLink.classList.add('active');
      document.querySelector('.more-content').classList.add('active');
      e.preventDefault();
    });
  };

  /**
   * selectタグの修飾
   * element: select
   */
  const _SelectTags = () => {
    const $selectAll = document.querySelectorAll('select');
    $selectAll.forEach(($select) => {
      $select.outerHTML = '<div class="select-area">' + $select.outerHTML + '</div>';
    })
  };

  /**
   * 検索フォームの修飾
   * attr: .search-form
   */
  const _SearchForm = () => {
    const $searchFormAll = document.querySelectorAll('.search-form');
    $searchFormAll.forEach(($sform) => {
      const events = ['mouseenter', 'mouseleave'];
      events.forEach((event) => {
        $sform.querySelector('.search-submit').addEventListener(event, () => {
          $sform.querySelector('label').classList.toggle('hover');
        });
      });
    });
  };

  /**
   * テーブルを修飾
   * element: .entry-body table
   */
  const _TableContents = () => {
    const $tables = document.querySelectorAll('.entry-body table');
    $tables.forEach(($table) => {
      const $tableContent = document.createElement('div');
      $tableContent.classList.add('table-content');
      const $tableArea = document.createElement('div');
      $tableArea.classList.add('table-area');
      const $arrowLeft = document.createElement('div');
      $arrowLeft.classList.add('table-arrow');
      $arrowLeft.classList.add('table-arrow-left');
      const $arrowRight = document.createElement('div');
      $arrowRight.classList.add('table-arrow');
      $arrowRight.classList.add('table-arrow-right');
      $tableArea.innerHTML = $table.outerHTML;
      $tableContent.appendChild($tableArea);
      $tableArea.appendChild($arrowLeft);
      $tableArea.appendChild($arrowRight);
      const tableWidth = $table.offsetWidth;
      $table.parentNode.insertBefore($tableContent, $table);
      $table.parentNode.removeChild($table);
      const indicate = () => {
        const distance = tableWidth - $tableArea.offsetWidth;
        const position = $tableArea.scrollLeft;
        const margin = 3;
        /* left */
        if (margin <= position) $arrowLeft.classList.add('active');
        else $arrowLeft.classList.remove('active');
        /* right */
        if (distance - margin >= position) $arrowRight.classList.add('active');
        else $arrowRight.classList.remove('active');
      }
      indicate();
      $tableArea.addEventListener('scroll', indicate);
    });
  };

  /**
   * ページトップへ
   * attr: #gotop-button
   */
  const _GoPageTop = (() => {
    const $gotop = document.querySelector('#gotop-button');
    if (!$gotop) return;
    const $div = document.createElement('div');
    ['gotop-cfg', 'gotop-start', 'gotop-end'].forEach((cls) => {
      $div.classList.add(cls);
    });
    $gotop.parentNode.insertBefore($div, $gotop);
    const style = window.getComputedStyle($div);
    const start = parseInt(style.getPropertyValue('top')) || 0;
    const bottom = parseInt(style.getPropertyValue('bottom')) || 0;
    const eventAll = [
      'load',
      'scroll',
      'resize'
    ];
    eventAll.forEach((event) => {
      const indicate = () => {
        const currentPos = window.scrollY;
        const pageBottomTop = document.documentElement.scrollHeight - window.BbOptions.shrinkHeight;
        if (start < currentPos) {
          $gotop.classList.add('gotop-show');
        } else {
          $gotop.classList.remove('gotop-show');
        }
        if (pageBottomTop - bottom < currentPos) {
          $gotop.classList.add('gotop-bottom');
          $gotop.classList.add('gotop-end');
        } else {
          $gotop.classList.remove('gotop-bottom');
          $gotop.classList.remove('gotop-end');
        }
      };
      window.addEventListener(event, indicate);
    });
    const $gotopBtn = $gotop.querySelector('.gotop-symbol');
    $gotopBtn && $gotopBtn.addEventListener('click', () => {
      new BbSmoothScroll(0, Object.create(_options.scrollPageTop));
    });
  });

  /**
   * ページ内アンカーリンク
   * element: a
   */
  const _GoAnchorLink = (() => {
    const $anchorAll = document.querySelectorAll('a[href*="#"]');
    const url = location.href.split('#');
    $anchorAll && $anchorAll.forEach(($anchor) => {
      $anchor.addEventListener('click', (e) => {
        const anchor = $anchor.getAttribute('href').split('#');
        const _anchor = document.createElement('a');
        _anchor.href = anchor[0];
        if (
          (anchor[0] && url[0].replace(/(https?:)?\/\/(.*?)\/?$/, '$2') != _anchor.href.replace(/(https?:)?\/\/(.*?)\/?$/, '$2'))
          || !anchor[1]
          || anchor[1].match(/respond/)
          || anchor[1].match(/more\-.*?$/)
        ) return;
        const $_anchor = document.querySelector('#' + anchor[1]);
        if ($_anchor) {
          e.preventDefault();
          new BbSmoothScroll($_anchor, Object.create(_options.scrollCommon));
          /* mobile-nav対応 --- */
          const toggleElement = [
            '#main-screen',
            '#main-screen-mask',
            '#nav-window-area',
            '#nav-window-close-btn'
          ];
          toggleElement.forEach((toggle) => {
            document.body.classList.remove('nav-window-show');
            const elem = document.querySelector(toggle);
            elem && elem.classList.remove('nav-window-show');
          });
          /* --- mobile-nav対応 */
        }
        e.preventDefault();
        return false;
      });
    });
  });

  /**
   * 遷移後にアンカーリンクへ
   */
  const _ToAnchorLink = (() => {
    const anchor = location.hash;
    if (anchor) {
      const $anchor = document.querySelector(anchor);
      new BbSmoothScroll($anchor, Object.create(_options.toAnchorLink));
    }
  });

  /**
   * ヘッダーを固定
   * attr: #header-part > #header-part-inner
   */
  const _FixedHeaderPart = (() => {
    const $headerPart = document.querySelector('#header-part');
    if ($headerPart) {
      const rect = $headerPart.getBoundingClientRect();
      if (window.scrollY  >= parseInt(window.scrollY  + rect.top)) $headerPart.classList.add('fixed');
      else $headerPart.classList.remove('fixed');
    }
  });

  /**
   * ウィンドウ内で対象要素を表示
   * attr: data-bb-option="fade-in"
   */
  const _FadeInActive = (() => {
    const showPos = window.BbOptions.shrinkHeight * 0.94;
    const $fadeInAll = document.querySelectorAll('[data-bb-option="fade-in"]');
    $fadeInAll.forEach(($fadeIn) => {
      const rect = $fadeIn.getBoundingClientRect();
      const position = parseInt(rect.top - showPos);
      if (position < 0) $fadeIn.classList.add('active');
    });
  });

  /**
   * 子メニューのあるグローバルナビ／ヘッダーナビ
   */
  const _GlobalNav = (() => {
    const navs = [
      '#header-nav',
      '#global-nav'
    ];
    navs.forEach((nav) => {
      const $gnav = document.querySelector(nav);
      if (!$gnav) return;
      const $navAll = $gnav.querySelectorAll('.menu > .menu-item-has-children');
      let $prev_nav = '';
      $navAll.forEach(($nav) => {
        const maxHeight = (act) => {
          let height = 0;
          if (act == 'open') {
            const $subMenu = $nav.querySelector('.sub-menu').getBoundingClientRect();
            height = parseInt($subMenu.height) + 25;
          }
          $nav.querySelector('.child-group').style.maxHeight = `${height}px`;
        };
        if (window.BbOptions.deviceInfo.touchevent) { // タッチデバイス
          $nav.addEventListener('touchstart', () => {
            if (nav == navs[1]) document.querySelector('#main-container').classList.add('gnav-active');
            maxHeight('open');
            $nav.classList.add('menu-active');
            $prev_nav = $nav;
            if (!$nav.classList.contains('touchstart')) {
              $nav.classList.add('touchstart');
            }
          }, { passive: true });
          $nav.addEventListener('mouseout', () => {
            if (nav == navs[1] && $prev_nav === $nav) document.querySelector('#main-container').classList.remove('gnav-active');
            maxHeight();
            $nav.classList.remove('menu-active');
            $nav.classList.remove('touchstart');
          });
        } else { // デスクトップ
          $nav.addEventListener('mouseover', () => {
            if (nav == navs[1]) document.querySelector('#main-container').classList.add('gnav-active');
            maxHeight('open');
          });
          $nav.addEventListener('mouseout', () => {
            if (nav ==  navs[1]) document.querySelector('#main-container').classList.remove('gnav-active');
            maxHeight();
          });
        }
      });
    });
  });

  /**
   * サイドウィジェットを下で固定
   */
  let initfixedWidget = {
    top: null,
    offset: null
  }
  const _FixedWidgetColumn = (() => {
    const $secondCol = document.querySelector('#second-column');
    // サイドカラムが非表示の場合は処理をしない
    if (!$secondCol || window.getComputedStyle($secondCol).getPropertyValue('display') == 'none') return;
    const $fixedWidget = document.querySelector('.bottom-fixed-widget');
    if (!$fixedWidget) return;
    // ウィジェットのtopオフセットをcssから取得
    if (initfixedWidget.offset === null) {
      $fixedWidget.classList.add('initial');
      initfixedWidget.offset = parseInt(window.getComputedStyle($fixedWidget).getPropertyValue('top'));
      $fixedWidget.classList.remove('initial');
    }
    const secondColRect = $secondCol.getBoundingClientRect();
    const fixedWidgetRect = $fixedWidget.getBoundingClientRect();
    // コンテンツカラムよりウィジェットの方が大きい場合は処理をしない
    if (secondColRect.height <= fixedWidgetRect.height) return;
    const currentBottom = window.scrollY + window.BbOptions.shrinkHeight;
    if (initfixedWidget.top === null) initfixedWidget.top = window.scrollY + fixedWidgetRect.top;
    const globalNavRect = document.querySelector('#global-nav').getBoundingClientRect();
    const globalWidgetRect = document.querySelector('#global-widget').getBoundingClientRect();
    // ウィンドウよりウィジェットの方が小さい場合
    if (window.BbOptions.shrinkHeight > parseInt(globalWidgetRect.height - globalNavRect.height)) {
      $fixedWidget.classList.add('sticky');
    } else {
      // ウィジェットのボトムを判定
      const fixedWidgetBottom = parseInt(initfixedWidget.top + fixedWidgetRect.height);
      if (currentBottom >= fixedWidgetBottom) $fixedWidget.classList.add('fixed');
      else $fixedWidget.classList.remove('fixed');
      // カラムのボトムを判定
      const secondColBottom = parseInt(window.scrollY + secondColRect.bottom);
      if (currentBottom >= secondColBottom) $fixedWidget.classList.add('absolute');
      else $fixedWidget.classList.remove('absolute');
    }
  });

  /**
   * 目次の開閉
   */
  const _BbToc = () => {
    const tocBlock = '.bb-toc-block';
    const $tocBlockAll = document.querySelectorAll(tocBlock);
    $tocBlockAll.forEach(($tocBlock) => {
      const $tocToggle = $tocBlock.querySelector('.bb-toc-toggle');
      const maxHeight = () => {
        const $tocBodyInner = $tocBlock.querySelector('.bb-toc-body-inner').getBoundingClientRect();
        const height = parseInt($tocBodyInner.height) + 25;
        $tocBlock.querySelector('.bb-toc-body').style.maxHeight = `${height}px`;
      };
      window.addEventListener('load', maxHeight);
      window.addEventListener('resize', maxHeight);
      $tocToggle && $tocToggle.addEventListener('click', () => {
        $tocBlock.classList.toggle('changed');
      });
    });
  };

  /**
   * フォームの修飾
   * attr: .bb-form-style
   */
  const _BbFormStyle = () => {
    const formBlock = '.bb-form-style';
    const formTop = '.bb-form-style-top';
    const $formBlockAll = document.querySelectorAll(formBlock);
    const scrollOptions = _options.scrollForm ? Object.create(_options.scrollForm) : Object.create(_options.scrollCommon);
    scrollOptions.loaded = true;
    $formBlockAll.forEach(($formBlock) => {
      /* input: error */
      if (!$formBlock) return;
      const $groupAll = $formBlock.querySelectorAll('.group');
      let firstError = true;
      $groupAll.forEach(($group) => {
        const $error = $group.querySelector('.error');
        if ($error) {
          if (firstError) {
            new BbSmoothScroll($group, scrollOptions);
            firstError = false;
          }
          $group.classList.add('group-error');
          const objectAll = [
            'input',
            'select',
            'textarea',
            '.error'
          ];
          const eventAll = [
            'click',
            'focus'
          ];
          objectAll.forEach((object) => {
            eventAll.forEach((event) => {
              const $objects = $group.querySelectorAll(object);
              $objects.forEach(($object) => {
                $object && $object.addEventListener(event, () => {
                  $group.classList.remove('group-error');
                  $error.classList.add('error-hidden');
                });
              });
            });
          });
        }
      });

      /**
       * MW WP Formプラグイン向け
       */
      const key = 'submitBack';
      const $submitBack = $formBlock.querySelector('[name="submitBack"]');
      $submitBack && $submitBack.addEventListener('click', () => {
        localStorage.setItem(key, 1);
      });
      if (localStorage.getItem(key) == 1) {
        const $formBlockTop = document.createElement('div');
        $formBlockTop.classList.add(formTop.substring(1));
        $formBlock.parentNode.insertBefore($formBlockTop, $formBlock);
        new BbSmoothScroll($formBlockTop, scrollOptions);
        localStorage.removeItem(key);
      }
    });
  };

  /**
   * 画面比率
   */
  const _ShrinkRatio = () => {
    const $mainScreen = document.querySelector('#main-screen');
    const minWidth = parseInt(window.getComputedStyle($mainScreen).getPropertyValue('min-width'));
    window.BbOptions.shrinkRatio = minWidth && minWidth > document.documentElement.clientWidth ? parseFloat(minWidth / document.documentElement.clientWidth) : 1;
    window.BbOptions.shrinkHeight = document.documentElement.clientHeight * window.BbOptions.shrinkRatio;
    // if (window.BbOptions.deviceInfo.touchevent && /ios|macosx/.test(window.BbOptions.deviceInfo.os)) {
    //   const deviceWidth = window.BbOptions.breakPoint <= document.documentElement.clientWidth ? minWidth : 'device-width';
    //   document.querySelector("meta[name='viewport']").setAttribute("content", `width=${deviceWidth}, viewport-fit=cover`);
    // }
  };

  /**
   * iframeコンテンツの読み込みを遅延
   * add) iframe class="embeded-iframe-src"
   */
  function _EmbededIframeSrc() {
    const iframes = document.querySelectorAll('.embeded-iframe-src');
    iframes.forEach(function (iframe) {
      if (iframe.getAttribute('data-src')) {
        iframe.setAttribute('src', iframe.getAttribute('data-src'));
      }
    });
  }

  /**
   *
   */
  const _CookieBanner = () => {
    const key = 'cookieBanner';
    const block = document.querySelector('#cookie-banner');
    if (!block) {
      localStorage.removeItem(key);
      return;
    }
    const expire = block.dataset.expire ? parseInt(block.dataset.expire) : 365; // 有効期限
    const data = localStorage.getItem(key);
    const now = new Date();
    if (data == null || now.getTime() > parseInt(data)) {
      block.classList.add('indicate');
      const btn = block.querySelector('.cookie-btn');
      btn.addEventListener('click', () => {
        localStorage.setItem(key, now.getTime() + expire * 86400 * 1000);
        block.classList.remove('indicate');
      });
    }
  };


  /**
   * DOM読み込み後に実行
   */
  const initBreakPoint = 782;
  let _options = {
    scrollCommon: {
      loaded: false,
      offset: true,
      speed: 40,
      desktop: 65,
      mobile: 65,
      breakPoint: initBreakPoint
    },
    toAnchorLink: {
      loaded: false,
      offset: true,
      speed: 95,
      desktop: 65,
      mobile: 65,
      breakPoint: initBreakPoint
    },
    scrollPageTop: {
      loaded: false,
      offset: false,
      speed: 40,
      desktop: 0,
      mobile: 0,
      breakPoint: initBreakPoint
    },
    scrollForm: {
      loaded: true,
      offset: true,
      speed: 40,
      desktop: 80,
      mobile: 65,
      breakPoint: initBreakPoint
    },
    breakPoint: initBreakPoint,
    pageLoaded: false
  };
  document.addEventListener('DOMContentLoaded', () => {
    if ('bbOptions' in window) {
      Object.keys(bbOptions).forEach((key) => {
        const bbOption = bbOptions[key];
        Object.keys(bbOption).forEach((_key) => {
          if (bbOption[_key] || bbOption[_key] === false) _options[key][_key] = bbOption[_key];
        });
      });
    }
    window.BbOptions = _options;
    new BbSetUserAgent().addClass();
    _ShrinkRatio();
    _BackgroundImage();
    _MoreContent();
    _SelectTags();
    _GoPageTop();
    _GoAnchorLink();
    _ToAnchorLink();
    _SearchForm();
    _BbToc();
    _GlobalNav();
    _BbFormStyle();
    _FixedWidgetColumn();
    // クラス要素「set-user-action」を対象としてユーザーアクションが発生したときに実行
    const $userAction = document.querySelectorAll('.set-user-action');
    $userAction.forEach((action) => {
      action.addEventListener('click', () => {
        _FixedWidgetColumn();
      });
    });
    // ロードが3秒以上かかる場合はクラス要素「page-load-abort page-loaded」を追加
    setTimeout(() => {
      if (!window.BbOptions.pageLoaded) {
        document.documentElement.classList.add('page-load-abort');
        document.documentElement.classList.add('page-loaded');
      }
    }, 3000);
  });

  /**
   * ページ構成完了時に実行
   */
  window.addEventListener('load', () => {
    _TableContents();
    _FixedHeaderPart();
    _FadeInActive();
    _CookieBanner();
    _EmbededIframeSrc();
    // ページ読み込み完了のクラスを追加
    document.documentElement.classList.add('page-loaded');
    window.BbOptions.pageLoaded = true;
  });

  /**
   * loadイベント後に実行
   */
  window.addEventListener('pageshow', () => {
    _FixedWidgetColumn();
  });

  /**
   * ページスクロール時に実行
   */
  window.addEventListener('scroll', () => {
    _FixedHeaderPart();
    _FixedWidgetColumn();
    _FadeInActive();
  });

  /**
   * ページリサイズ時に実行
   */
  window.addEventListener('resize', () => {
    _ShrinkRatio();
    _FixedHeaderPart();
    _FixedWidgetColumn();
    _FadeInActive();
  });
})();
