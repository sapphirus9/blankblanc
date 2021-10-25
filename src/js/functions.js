/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです
 */

/**
 * ブラウザー確認
 */
class BbSetUserAgent
{
  constructor() {
    let ua = '';
    const config = {
      os: 'other',
      device: 'desktop',
      browser: 'other'
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
      else if (/Linux/.test(ua)) config.os = 'linux';
      /* Device */
      if (/iPhone.*?Mac OS X/.test(ua)) config.device = 'mobile';
      else if (/Android.*?Mobile/.test(ua)) config.device = 'mobile';
      /* Browser */
      if (/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(ua)) config.browser = 'safari';
      else if (/Trident\/7\.0/.test(ua)) config.browser = 'ie11';
      else if (/Firefox/.test(ua)) config.browser = 'firefox';
      else if (/Chrome/.test(ua)) config.browser = 'chromium';
    }
    this.config = config;
  };
  addClass() {
    document.documentElement.classList.add('os-' + this.config.os);
    document.documentElement.classList.add('device-' + this.config.device);
    document.documentElement.classList.add('browser-' + this.config.browser);
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
      breakPoint: _BbBreakPoint
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
        position = rect.top + window.pageYOffset;
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
    const currentPos = window.pageYOffset;
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
let _BbBreakPoint = 768;
(() => {
  'use strict';
  /* forEach (ie11 measures) */
  if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }

  /**
   * DOM読み込み後に実行
   */
  let _options = {
    scrollCommon: {
      loaded: false,
      offset: true,
      speed: 40,
      desktop: 40,
      mobile: 65,
      breakPoint: _BbBreakPoint
    },
    scrollPageTop: {
      loaded: false,
      offset: false,
      speed: 40,
      desktop: 0,
      mobile: 0,
      breakPoint: _BbBreakPoint
    },
    breakPoint: _BbBreakPoint
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
    if (_options.breakPoint) _BbBreakPoint = _options.breakPoint;

    new BbSetUserAgent().addClass();
    _MoreContent();
    _WidgetArchive();
    _WidgetComments();
    _SelectTags();
    _GoPageTop();
    _GoAnchorLink();
    _SearchForm();
    _BbFormStyle();
  });

  /**
   * ページ構成完了時に実行
   */
  window.addEventListener('load', () => {
    _BackgroundImage();
    _TableContents();
    _ToAnchorLink();
  });

  /**
   * 画像をバックグラウンドイメージに配置
   */
  const _BackgroundImage = () => {
    const $imgAll = document.querySelectorAll('.background-image-src');
    $imgAll.forEach(($img) => {
      $img.style.display = 'none';
      $img.parentNode.style.backgroundImage = 'url(' + $img.getAttribute('src') + ')';
      $img.parentNode.classList.add('set-background-image');
    });
  };

  /**
   * 続きを読む
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
   * 月間アーカイブの階層化
   */
  const _WidgetArchive = () => {
    const $archiveAll = document.querySelectorAll('.widget_archive > ul');
    $archiveAll.forEach(($archive) => {
      const $monthList = document.createElement('ul');
      $monthList.classList.add('month-list');
      const $monthAll = $archive.querySelectorAll('li:not(.year-title)');
      $monthAll.forEach(($month) => {
        $monthList.appendChild($month);
      });
      $archive.querySelector('.year-title').appendChild($monthList);
    });
  };

  /**
   * 最近のコメント
   */
  const _WidgetComments = () => {
    const $commentsAll = document.querySelectorAll('.widget_recent_comments .recentcomments');
    $commentsAll.forEach(($comments) => {
      const $author = $comments.querySelector('.comment-author-link');
      const $html = $comments.querySelector('a');
      $html.innerHTML = '<span class="entry-title">' + $html.innerHTML + '</span>' + '<span class="comment-user">' + $author.innerHTML + '</span>';
      $comments.innerHTML = '';
      $comments.appendChild($html);
    });
  };

  /**
   * selectタグの修飾
   */
  const _SelectTags = () => {
    const $selectAll = document.querySelectorAll('select');
    $selectAll.forEach(($select) => {
      $select.outerHTML = '<div class="select-area">' + $select.outerHTML + '</div>';
    })
  };

  /**
   * 検索フォームの修飾
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
   */
  const _GoPageTop = (() => {
    const $gotopBtn = document.querySelector('#gotop-button');
    const $div = document.createElement('div');
    ['gotop-cfg', 'gotop-start', 'gotop-end'].forEach((cls) => {
      $div.classList.add(cls);
    });
    $gotopBtn.parentNode.insertBefore($div, $gotopBtn);
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
        const currentPos = window.pageYOffset;
        const pageBottomTop = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        if (start < currentPos) {
          $gotopBtn.classList.add('gotop-show');
        } else {
          $gotopBtn.classList.remove('gotop-show');
        }
        if (pageBottomTop - bottom < currentPos) {
          $gotopBtn.classList.add('gotop-bottom');
          $gotopBtn.classList.add('gotop-end');
        } else {
          $gotopBtn.classList.remove('gotop-bottom');
          $gotopBtn.classList.remove('gotop-end');
        }
      };
      window.addEventListener(event, indicate);
    });
    $gotopBtn && $gotopBtn.addEventListener('click', () => {
      new BbSmoothScroll(0, Object.create(_options.scrollPageTop));
    });
  });

  /**
   * ページ内アンカーリンク
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
            document.querySelector(toggle).classList.remove('nav-window-show');
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
      new BbSmoothScroll($anchor, Object.create(_options.scrollCommon));
    }
  });

  /**
   * フォームの修飾（bb-form-style）
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
      const $submitBack = $formBlock.querySelector('[name="submitBack"]');
      $submitBack && $submitBack.addEventListener('click', () => {
        localStorage.setItem('submitBack', 1);
      });
      if (localStorage.getItem('submitBack') == 1) {
        const $formBlockTop = document.createElement('div')
        $formBlockTop.classList.add(formTop.substring(1));
        $formBlock.parentNode.insertBefore($formBlockTop, $formBlock);
        const rect = $formBlockTop.getBoundingClientRect();
        new BbSmoothScroll($formBlockTop, scrollOptions);
      }
      localStorage.setItem('submitBack', null);
    });
  };
})();
