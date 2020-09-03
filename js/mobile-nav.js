/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: モバイルビューの時に使用するJavaScriptです (Require: jQuery)
 */

(function ($) {
  $(function () {
    /**
     * ナビウィンドウを開閉
     */
    var elem = {
      // スライドナビ ウイジェットの li が対象
      s_nav: [
        '#global-nav',
        '#global-widget .widget_categories',
        '#header-nav'
      ],
      // フッターナビ用 ウイジェットの li が対象
      f_nav: []
    };
    if ('bb_mobile_cfg_nav' in window) {
      $.extend(elem, bb_mobile_cfg_nav);
    }
    if ('bb_mobile_nav' in window) {
      $.extend(elem, bb_mobile_nav);
    }

    var main_screen = '#main-screen';
    var action = 'click'; // 'touchstart mousedown';
    $(main_screen).after('<div id="nav-window-area" class="is-mobile">');
    $(main_screen).after('<div id="main-screen-mask" class="is-mobile">');
    $('#nav-window-area').prepend('<div id="nav-window-scroll">');
    var s_id = function (i, val) {
      return val + '-slidenav';
    };
    $.each(elem.s_nav, function (i, e) {
      if (e == '#global-nav' || e == '#header-nav') {
        $('.menu', e).clone().appendTo('#nav-window-scroll').wrap('<ol><li>').attr('id', s_id);
        $('#nav-window-scroll li').removeAttr('id');
      } else {
        $(e).clone().appendTo('#nav-window-scroll').wrap('<ol>').attr('id', s_id);
        $('#nav-window-scroll ul').removeAttr('id');
      }
    });
    // CLOSEボタン
    $('#nav-window-area').prepend('<div id="nav-window-close-btn"><span class="btn-symbol">');
    // ヘッダーにOPENボタン
    $('#global-header').append('<div id="nav-window-open-btn" class="is-mobile"><span class="btn-symbol">');
    var sw = '#main-screen-mask, #nav-window-area, #nav-window-close-btn, ' + main_screen;
    var pos_y = $(window).scrollTop();
    // 開く
    $('#nav-window-open-btn').on(action, function (e) {
      pos_y = $(window).scrollTop();
      $('body').addClass('nav-window-show');
      $(sw).addClass('nav-window-show');
      e.preventDefault();
    });
    // 閉じる
    $('#nav-window-close-btn, #main-screen-mask').on(action, function (e) {
      $('body').removeClass('nav-window-show').scrollTop(pos_y);
      $(sw).removeClass('nav-window-show');
      e.preventDefault();
    });
    // ブラウザバック(ios)
    $(window).on('pageshow', function (e) {
      $('body').removeClass('nav-window-show');
      $(sw).removeClass('nav-window-show');
      e.preventDefault();
    });

    /**
     * フッターナビ
     */
    var add_f = '#global-footer .footer-widgets:first-child';
    var f_id = function (i, val) {
      return val + '-footernav';
    };
    $.each(elem.f_nav.reverse(), function (i, e) {
      if (e == '#header-nav' || e == '#global-nav') {
        $('.menu', e).clone().prependTo(add_f).wrap('<ul class="is-mobile"><li class="widget">').attr('id', f_id);
        $('#global-footer li').removeAttr('id');
      } else {
        $(e).clone().prependTo(add_f).wrap('<ul class="is-mobile">').attr('id', f_id);
        $('#global-footer ul').removeAttr('id');
      }
    });
  });
})(jQuery);
