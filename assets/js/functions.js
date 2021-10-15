"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです (Require: jQuery)
 */

/**
 * ブラウザー確認
 */
var BbSetUserAgent = /*#__PURE__*/function () {
  function BbSetUserAgent() {
    _classCallCheck(this, BbSetUserAgent);

    var ua = '';
    var config = {
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
      ua = navigator.userAgent; // OS

      if (/Windows/.test(ua)) config.os = 'windows';else if (/Macintosh/.test(ua)) config.os = 'macosx';else if (/Android/.test(ua)) config.os = 'android';else if (/iP.*?Mac OS X/.test(ua)) config.os = 'ios';else if (/Linux/.test(ua)) config.os = 'linux'; // Device

      if (/iPhone.*?Mac OS X/.test(ua)) config.device = 'mobile';else if (/Android.*?Mobile/.test(ua)) config.device = 'mobile'; // Browser

      if (/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(ua)) config.browser = 'safari';else if (/Trident\/7\.0/.test(ua)) config.browser = 'ie11';else if (/Firefox/.test(ua)) config.browser = 'firefox';else if (/Chrome/.test(ua)) config.browser = 'chromium';
    }

    this.config = config;
  }

  _createClass(BbSetUserAgent, [{
    key: "addClass",
    value: function addClass() {
      document.documentElement.classList.add('os-' + this.config.os);
      document.documentElement.classList.add('device-' + this.config.device);
      document.documentElement.classList.add('browser-' + this.config.browser);
    }
  }]);

  return BbSetUserAgent;
}();

;
/**
 * ウィンドウスクロール
 */

var BbSmoothScroll = function BbSmoothScroll() {
  var position = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
  var option = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

  _classCallCheck(this, BbSmoothScroll);

  var init = {
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

  Object.keys(init).forEach(function (key) {
    if (option[key]) init[key] = option[key];
  }); // if (!position) position = 0;

  if (init.offset) {
    position = position - (document.documentElement.clientWidth < init.breakpoint ? init.mobile : init.desktop);
    if (position < 0) position = 0;
  }

  var currentPos = window.pageYOffset;
  var timer = null;
  var cancel = false;
  var direction = 'neutral';
  if (currentPos > position) direction = 'up';else if (currentPos < position) direction = 'down'; // scroll

  function scrolling(setPos) {
    if (!setPos) setPos = currentPos;
    var distance = Math.abs(position - setPos);
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

    timer = requestAnimationFrame(function () {
      return scrolling(setPos);
    });
  }

  ;

  if (direction != 'neutral') {
    if (init.loaded) window.addEventListener('load', function () {
      return scrolling();
    });else scrolling();
  }
};

var _BbBreakPoint = 768;

(function () {
  'use strict'; // forEach (ie11 measures)

  if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }

  document.addEventListener('DOMContentLoaded', function () {
    new BbSetUserAgent().addClass();

    _MoreContent();

    _WidgetArchive();

    _WidgetComments();

    _ModifySelect();

    _ModifySearchForm();

    _BbFormStyle();

    _goPageTop();
  });
  window.addEventListener('load', function () {
    _setBackgroundImage();

    _TableContents();
  });
  /**
   * 画像をバックグラウンドイメージに配置
   */

  var _setBackgroundImage = function _setBackgroundImage() {
    var $imgAll = document.querySelectorAll('.background-image-src');
    $imgAll.forEach(function ($img) {
      $img.style.display = 'none';
      $img.parentNode.style.backgroundImage = 'url(' + $img.getAttribute('src') + ')';
      $img.parentNode.classList.add('set-background-image');
    });
  };
  /**
   * 続きを読む
   */


  var _MoreContent = function _MoreContent() {
    var $moreLink = document.querySelector('.more-link');
    $moreLink && $moreLink.addEventListener('click', function (e) {
      $moreLink.classList.add('active');
      document.querySelector('.more-content').classList.add('active');
      e.preventDefault();
    });
  };
  /**
   * 月間アーカイブの階層化
   */


  var _WidgetArchive = function _WidgetArchive() {
    var $archiveAll = document.querySelectorAll('.widget_archive > ul');
    $archiveAll.forEach(function ($archive) {
      var $monthList = document.createElement('ul');
      $monthList.classList.add('month-list');
      var $monthAll = $archive.querySelectorAll('li:not(.year-title)');
      $monthAll.forEach(function ($month) {
        $monthList.appendChild($month);
      });
      $archive.querySelector('.year-title').appendChild($monthList);
    });
  };
  /**
   * 最近のコメント
   */


  var _WidgetComments = function _WidgetComments() {
    var $commentsAll = document.querySelectorAll('.widget_recent_comments .recentcomments');
    $commentsAll.forEach(function ($comments) {
      var $author = $comments.querySelector('.comment-author-link');
      var $html = $comments.querySelector('a');
      $html.innerHTML = '<span class="entry-title">' + $html.innerHTML + '</span>' + '<span class="comment-user">' + $author.innerHTML + '</span>';
      $comments.innerHTML = '';
      $comments.appendChild($html);
    });
  };
  /**
   * selectタグの修飾
   */


  var _ModifySelect = function _ModifySelect() {
    var $selectAll = document.querySelectorAll('select');
    $selectAll.forEach(function ($select) {
      $select.outerHTML = '<div class="select-area">' + $select.outerHTML + '</div>';
    });
  };
  /**
   * 検索フォームの修飾
   */


  var _ModifySearchForm = function _ModifySearchForm() {
    var $searchFormAll = document.querySelectorAll('.search-form');
    $searchFormAll.forEach(function ($sform) {
      var events = ['mouseenter', 'mouseleave'];
      events.forEach(function (event) {
        $sform.querySelector('.search-submit').addEventListener(event, function () {
          $sform.querySelector('label').classList.toggle('hover');
        });
      });
    });
  };
  /**
   * フォームの修飾（bb-form-style）
   */


  var _BbFormStyle = function _BbFormStyle() {
    var formBlock = '.bb-form-style';
    var formTop = '.bb-form-style-top';
    var $formBlockAll = document.querySelectorAll(formBlock);
    var scrollCfg = {
      offset: true,
      loaded: true,
      desktop: 80,
      mobile: 65
    };
    $formBlockAll.forEach(function ($formBlock) {
      // input: error
      if (!$formBlock) return;
      var $groupAll = $formBlock.querySelectorAll('.group');
      var firstError = true;
      $groupAll.forEach(function ($group) {
        var $error = $group.querySelector('.error');

        if ($error) {
          if (firstError) {
            var rect = $group.getBoundingClientRect();
            new BbSmoothScroll(rect.top, scrollCfg);
            firstError = false;
          }

          $group.classList.add('group-error');
          var objectAll = ['input', 'select', 'textarea', '.error'];
          var eventAll = ['click', 'focus'];
          objectAll.forEach(function (object) {
            eventAll.forEach(function (event) {
              var $objects = $group.querySelectorAll(object);
              $objects.forEach(function ($object) {
                $object && $object.addEventListener(event, function () {
                  $group.classList.remove('group-error');
                  $error.classList.add('error-hidden');
                });
              });
            });
          });
        }
      }); // MW WP Formプラグイン向け

      var $submitBack = $formBlock.querySelector('[name="submitBack"]');
      $submitBack && $submitBack.addEventListener('click', function () {
        localStorage.setItem('submitBack', 1);
      });

      if (localStorage.getItem('submitBack') == 1) {
        var $formBlockTop = document.createElement('div');
        $formBlockTop.classList.add(formTop.substring(1));
        $formBlock.parentNode.insertBefore($formBlockTop, $formBlock);
        var rect = $formBlockTop.getBoundingClientRect();
        new BbSmoothScroll(rect.top, scrollCfg);
      }

      localStorage.setItem('submitBack', null);
    });
  };
  /**
   * テーブルを修飾
   */


  var _TableContents = function _TableContents() {
    var $tables = document.querySelectorAll('.entry-body table');
    $tables.forEach(function ($table) {
      var $tableContent = document.createElement('div');
      $tableContent.classList.add('table-content');
      var $tableArea = document.createElement('div');
      $tableArea.classList.add('table-area');
      var $arrowLeft = document.createElement('div');
      $arrowLeft.classList.add('table-arrow');
      $arrowLeft.classList.add('table-arrow-left');
      var $arrowRight = document.createElement('div');
      $arrowRight.classList.add('table-arrow');
      $arrowRight.classList.add('table-arrow-right');
      $tableArea.innerHTML = $table.outerHTML;
      $table.innerHTML = '';
      $tableContent.appendChild($tableArea);
      $tableArea.appendChild($arrowLeft);
      $tableArea.appendChild($arrowRight);
      $table.parentNode.insertBefore($tableContent, $table);

      var indicate = function indicate() {
        var distance = $table.offsetWidth - $tableArea.offsetWidth;
        var position = $tableArea.scrollLeft;
        var margin = 3; // left

        if (margin <= position) $arrowLeft.classList.add('active');else $arrowLeft.classList.remove('active'); // right

        if (distance - margin >= position) $arrowRight.classList.add('active');else $arrowRight.classList.remove('active');
      };

      indicate();
      $tableArea.addEventListener('scroll', indicate);
    });
  };
  /**
   * ページトップへスクロール
   */


  var _goPageTop = function _goPageTop() {
    var $gotopBtn = document.querySelector('#gotop-button');
    var $div = document.createElement('div'); // ['gotop-cfg', 'gotop-start', 'gotop-offset'].forEach((cls) => {

    ['gotop-cfg', 'gotop-start', 'gotop-end'].forEach(function (cls) {
      $div.classList.add(cls);
    });
    $gotopBtn.parentNode.insertBefore($div, $gotopBtn);
    var style = window.getComputedStyle($div);
    var start = parseInt(style.getPropertyValue('top')) || 0;
    var bottom = parseInt(style.getPropertyValue('bottom')) || 0;
    var eventAll = ['load', 'scroll', 'resize'];
    eventAll.forEach(function (event) {
      var indicate = function indicate() {
        var currentPos = window.pageYOffset;
        var pageBottomTop = document.documentElement.scrollHeight - document.documentElement.clientHeight;

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
    $gotopBtn && $gotopBtn.addEventListener('click', function () {
      new BbSmoothScroll();
    });
  };
  /**
   * ページ内スクロール
   */


  var $anchorAll = document.querySelectorAll('.main-article a[href*="#"]');
  $anchorAll && $anchorAll.forEach(function ($anchor) {
    $anchor.addEventListener('click', function (e) {
      var anchor = $anchor.getAttribute('href').split('#');
      if (!anchor[1] || anchor[1].match(/comment\-.*?$/) || anchor[1].match(/respond/) || anchor[1].match(/more\-.*?$/)) return;
      var $_anchor = document.querySelector('#' + anchor[1]);

      if ($_anchor) {
        e.preventDefault();
        var rect = $_anchor.getBoundingClientRect();
        new BbSmoothScroll(rect.top + window.pageYOffset);
      }
    });
  });
})();
//# sourceMappingURL=functions.js.map
