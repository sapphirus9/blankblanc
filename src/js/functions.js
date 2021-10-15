/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです (Require: jQuery)
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
      if (ua.brands.length) config.browser = ua.brands[0].brand.toLowerCase();
    }
    if (config.os == 'other' || config.browser == 'other') {
      ua = navigator.userAgent;
      // OS
      if (/Windows/.test(ua)) config.os = 'windows';
      else if (/Macintosh/.test(ua)) config.os = 'macosx';
      else if (/Android/.test(ua)) config.os = 'android';
      else if (/iP.*?Mac OS X/.test(ua)) config.os = 'ios';
      else if (/Linux/.test(ua)) config.os = 'linux';
      // Device
      if (/iPhone.*?Mac OS X/.test(ua)) config.device = 'mobile';
      else if (/Android.*?Mobile/.test(ua)) config.device = 'mobile';
      // Browser
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
    const init = {
      loaded: false,
      offset: false,
      speed: 100,
      desktop: 0,
      mobile: 0,
      breakpoint: _BbBreakPoint
    };
    /**
     * loaded: ページの表示完了後に実行（ページ遷移時など）
     * offcet: 以下の設定を有効化
     * desktop: Desktop表示時の上部マージン
     * mobile: Mobile表示時の上部マージン
     * breakpoint: 画面幅によるモバイル/PC表示切替
     */
    Object.keys(init).forEach((key) => {
      if (option[key]) init[key] = option[key];
    });
    // if (!position) position = 0;
    if (init.offset) {
      position = position - (document.documentElement.clientWidth < init.breakpoint ? init.mobile : init.desktop);
      if (position < 0) position = 0;
    }
    const currentPos = window.pageYOffset;
    let timer = null;
    let cancel = false;
    let direction = 'neutral'
    if (currentPos > position) direction = 'up';
    else if (currentPos < position) direction = 'down';
    // scroll
    function scrolling(setPos) {
      if (!setPos) setPos = currentPos;
      const distance = Math.abs(position - setPos);
      setPos = parseInt(distance - distance * init.speed / 1000);
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

const _BbBreakPoint = 768;
(() => {
  'use strict';
  // forEach (ie11 measures)
  if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }

  document.addEventListener('DOMContentLoaded', () => {
    new BbSetUserAgent().addClass();
    _MoreContent();
    _WidgetArchive();
    _WidgetComments();
    _ModifySelect();
    _ModifySearchForm();
    _BbFormStyle();
    _goPageTop();
  });

  window.addEventListener('load', () => {
    _setBackgroundImage();
    _TableContents();
  });

  /**
   * 画像をバックグラウンドイメージに配置
   */
  const _setBackgroundImage = () => {
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
  const _ModifySelect = () => {
    const $selectAll = document.querySelectorAll('select');
    $selectAll.forEach(($select) => {
      $select.outerHTML = '<div class="select-area">' + $select.outerHTML + '</div>';
    })
  };

  /**
   * 検索フォームの修飾
   */
  const _ModifySearchForm = () => {
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
   * フォームの修飾（bb-form-style）
   */
  const _BbFormStyle = () => {
    const formBlock = '.bb-form-style';
    const formTop = '.bb-form-style-top';
    const $formBlockAll = document.querySelectorAll(formBlock);
    const scrollCfg = { offset: true, loaded: true, desktop: 80, mobile: 65 };
    $formBlockAll.forEach(($formBlock) => {
      // input: error
      if (!$formBlock) return;
      const $groupAll = $formBlock.querySelectorAll('.group');
      let firstError = true;
      $groupAll.forEach(($group) => {
        const $error = $group.querySelector('.error');
        if ($error) {
          if (firstError) {
            const rect = $group.getBoundingClientRect();
            new BbSmoothScroll(rect.top, scrollCfg);
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

      // MW WP Formプラグイン向け
      const $submitBack = $formBlock.querySelector('[name="submitBack"]');
      $submitBack && $submitBack.addEventListener('click', () => {
        localStorage.setItem('submitBack', 1);
      });
      if (localStorage.getItem('submitBack') == 1) {
        const $formBlockTop = document.createElement('div')
        $formBlockTop.classList.add(formTop.substring(1));
        $formBlock.parentNode.insertBefore($formBlockTop, $formBlock);
        const rect = $formBlockTop.getBoundingClientRect();
        new BbSmoothScroll(rect.top, scrollCfg);
      }
      localStorage.setItem('submitBack', null);
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
      $table.innerHTML = '';
      $tableContent.appendChild($tableArea);
      $tableArea.appendChild($arrowLeft);
      $tableArea.appendChild($arrowRight);
      $table.parentNode.insertBefore($tableContent, $table);
      const indicate = () => {
        const distance = $table.offsetWidth - $tableArea.offsetWidth;
        const position = $tableArea.scrollLeft;
        const margin = 3;
        // left
        if (margin <= position) $arrowLeft.classList.add('active');
        else $arrowLeft.classList.remove('active');
        // right
        if (distance - margin >= position) $arrowRight.classList.add('active');
        else $arrowRight.classList.remove('active');
      }
      indicate();
      $tableArea.addEventListener('scroll', indicate);
    });
  };

  /**
   * ページトップへスクロール
   */
  const _goPageTop = (() => {
    const $gotopBtn = document.querySelector('#gotop-button');
    const $div = document.createElement('div');
    // ['gotop-cfg', 'gotop-start', 'gotop-offset'].forEach((cls) => {
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
      new BbSmoothScroll();
    });
  });

  /**
   * ページ内スクロール
   */
  const $anchorAll = document.querySelectorAll('.main-article a[href*="#"]')
  $anchorAll && $anchorAll.forEach(($anchor) => {
    $anchor.addEventListener('click', (e) => {
      const anchor = $anchor.getAttribute('href').split('#');
      if (
        !anchor[1] ||
        anchor[1].match(/comment\-.*?$/) ||
        anchor[1].match(/respond/) ||
        anchor[1].match(/more\-.*?$/)
      ) return;
      const $_anchor = document.querySelector('#' + anchor[1]);
      if ($_anchor) {
        e.preventDefault();
        const rect = $_anchor.getBoundingClientRect();
        new BbSmoothScroll(rect.top + window.pageYOffset);
      }
    });
  });
})();
