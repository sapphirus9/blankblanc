/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです (Require: jQuery)
 */

(function ($) {
  /**
   * ブラウザー確認
   */
  $(function () {
    var ua = navigator.userAgent;
    // OS
    var os = '';
    if (/Windows/.test(ua)) os = 'windows';
    else if (/Macintosh/.test(ua)) os = 'macosx';
    else if (/Android/.test(ua)) os = 'android';
    else if (/iP.*?Mac OS X/.test(ua)) os = 'ios';
    else if (/Linux/.test(ua)) os = 'linux';
    $('html').addClass('os-' + os);
    // Safari
    if (/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(ua)) $('html').addClass('browser-safari');
  });

  /**
   * ページ内スクロール＆ページのトップへ
   */
  $(function () {
    var gotop = $('#gotop');
    var gotopBtn = $('#gotop-button');
    var spWin;
    gotop.prepend('<div class="gotop-cfg gotop-start gotop-bottom gotop-offset">');
    var cfg = $('.gotop-cfg', gotop);
    var start = parseInt(cfg.css('top')) || 0;
    var bottom = parseInt(cfg.css('bottom')) || 0;
    spWin = {
      'bp'  : parseInt(cfg.css('width')) || 0,
      'off' : parseInt(cfg.css('margin-top')) || 0
    };
    var _move = function (anchor) {
      var offset = $(anchor).offset();
      var offt = document.documentElement.clientWidth <= spWin['bp'] ? spWin['off'] : 0;
      var pos = !offset ? 0 : offset.top - offt;
      $('html,body').stop().animate({ scrollTop: pos }, 'normal', 'easeOutExpo');
    };
    $(window).on('scroll resize', function (e) {
      var scrT = $(document).scrollTop();
      var btmT = $(document).height() - document.documentElement.clientHeight - bottom;
      gotopBtn.toggleClass('gotop-show', start <= scrT ? true : false);
      gotopBtn.toggleClass('gotop-bottom', btmT <= scrT ? true : false);
    });
    // リンク
    $('a[href*="#"]').on('click', function (e) {
      $(this).blur();
      var _anchor = $(this).attr('href').split('#');
      var anchor = '#PageTop';
      if (_anchor[1]) {
        anchor = '#' + _anchor[1];
      }
      if (
        anchor.match(/#(comment\-.*)?$/) ||
        anchor.match(/#respond/) ||
        anchor.match(/#(more\-.*)?$/) ||
        !$(anchor)[0]
      ) {
        return;
      }
      e.preventDefault();
      _move(anchor);
    });
    // トップへ
    gotop.on('click', function (e) {
      $(this).blur();
      e.preventDefault();
      _move(null);
    });
  });
})(jQuery);

(function ($) {
  /**
   * フォーム
   */
  $(function () {
    $('select').each(function () {
      $(this).wrap('<div class="select-area"></div>');
    });
    $('.search-form').each(function () {
      var obj = this;
      $('input[type="submit"]', obj).on('mouseenter mouseleave', function () {
        $('label', obj).toggleClass('hover');
      });
    });
  });

  /**
   * 続きを読む
   */
  $(function () {
    $('.more-link').on('click', function (e) {
      $(this).addClass('active');
      $(this).parent().next().addClass('active').children('p:first').remove();
      e.preventDefault();
    });
  });

  /**
   * 月間アーカイブの階層化
   */
  $(function () {
    $('.widget_archive > ul').each(function () {
      $('li:nth-child(n+2)', this).wrapAll('<ul class="month-list">');
      $('.month-list', this).appendTo($('.year-title', this));
    });
  });

  /**
  * テーブルスクロール
  */
  $(function () {
    var spd = 5; // スクロールステップ(px)
    var ex = function (obj) {
      var ofx = $('table', obj).outerWidth() - $(obj).outerWidth();
      var psx = $(obj).scrollLeft();
      if (psx <= 0) {
        $('.table-left-arrow', obj).fadeOut(200);
      } else {
        $('.table-left-arrow', obj).fadeIn(200);
      }
      if (psx >= ofx) {
        $('.table-right-arrow', obj).fadeOut(200);
      } else {
        $('.table-right-arrow', obj).fadeIn(200);
      }
    };
    $('.entry-body table').wrap('<div class="table-content"><div class="table-area"></div></div>');
    $('.table-area')
      .prepend('<div class="table-arrow table-left-arrow"></div><div class="table-arrow table-right-arrow"></div>')
      .each(function () {
        var mvx, timer;
        var obj = this;
        ex(this);
        $('.table-left-arrow, .table-right-arrow', this).on({
          mousedown: function () {
            var dir = $(this).hasClass('table-right-arrow');
            mvx = $(obj).scrollLeft();
            timer = setInterval(function () {
              var ofx = $('table', obj).outerWidth() - $(obj).outerWidth();
              if (mvx >= 0 && mvx <= ofx) {
                mvx = dir ? mvx + spd : mvx - spd;
                $(obj).scrollLeft(mvx);
              } else {
                $(obj).scrollLeft();
                clearInterval(timer);
              }
            }, 5);
          },
          mouseup: function () {
            mvx = $(obj).scrollLeft();
            clearInterval(timer);
          },
          mouseout: function () {
            mvx = $(obj).scrollLeft();
            clearInterval(timer);
          }
        });
      })
      .on('scroll', function () {
        ex(this);
      });
  });

  /**
   * 最近のコメント
   */
  $(function () {
    $('.widget_recent_comments .recentcomments').each(function () {
      var a = $('a:first', this).prop('outerHTML');
      $(this).find('a:first').remove();
      var txt = $('.comment-author-link', this).text();
      $(this).html(a);
      $('a', this).wrapInner('<span class="entry-title"></span>').append('<span class="comment-user">（' + txt + '）</span>');
    });
  });

  /**
   * Easing
   */
  $(function () {
    $.extend($.easing, {
      easeOutExpo: function (x, t, b, c, d) {
        return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
      }
    });
  });
})(jQuery);
