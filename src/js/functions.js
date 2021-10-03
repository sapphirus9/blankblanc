/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 共通で使用するJavaScriptです (Require: jQuery)
 */
(() => {
  'use strict';
  /**
   * ブラウザー確認
   */
  document.addEventListener('DOMContentLoaded', () => {
    let ua = ''
    let os = ''
    let browser = ''
    let device = ''
    if (window.navigator.userAgentData) {
      ua = navigator.userAgentData
      os = ua.platform
      if (ua.brands.length) browser = ua.brands[0].brand
      if (ua.mobile) device = 'mobile'
    }
    if (!os || !browser) {
      ua = navigator.userAgent
      // OS
      if (/Windows/.test(ua)) os = 'windows'
      else if (/Macintosh/.test(ua)) os = 'macosx'
      else if (/Android/.test(ua)) os = 'android'
      else if (/iP.*?Mac OS X/.test(ua)) os = 'ios'
      else if (/Linux/.test(ua)) os = 'linux'
      // Browser
      if (/Mac OS X(?!.*Chrome)(?=.*Safari)/.test(ua)) browser = 'safari'
      else if (/Trident\/7\.0/.test(ua)) browser = 'ie11'
      // Device
      if (/iPhone.*?Mac OS X/.test(ua)) device = 'mobile'
      else if (/Android.*?Mobile/.test(ua)) device = 'mobile'
    }
    if (os) document.documentElement.classList.add('os-' + os.toLowerCase())
    if (browser) document.documentElement.classList.add('browser-' + browser.toLowerCase())
    if (device) document.documentElement.classList.add('device-' + device.toLowerCase())
  })

  /**
   * 画像をバックグラウンドイメージに配置
   */
  window.addEventListener('load', () => {
    const imgs = document.querySelectorAll('.background-image-src')
    Array.prototype.forEach.call(imgs, (img) => {
      img.style.display = 'none'
      img.parentNode.style.backgroundImage = 'url(' + img.getAttribute('src') + ')'
      img.parentNode.classList.add('set-background-image')
    })
  })

  /**
   * 続きを読む
   */
  document.addEventListener('DOMContentLoaded', () => {
    const moreLink = document.querySelector('.more-link')
    moreLink && moreLink.addEventListener('click', (e) => {
      moreLink.classList.add('active')
      const moreContent = document.querySelector('.more-content')
      moreContent.classList.add('active')
      e.preventDefault()
    })
  })

  /**
   * フォーム
   */
  document.addEventListener('DOMContentLoaded', () => {
    const selects = document.querySelectorAll('select')
    Array.prototype.forEach.call(selects, (select) => {
      select.outerHTML = '<div class="select-area">' + select.outerHTML + '</div>'
    })
    const searchForm = document.querySelectorAll('.search-form')
    Array.prototype.forEach.call(searchForm, (sform) => {
      const submit = sform.querySelector('.search-submit')
      const events = ['mouseenter', 'mouseleave']
      Array.prototype.forEach.call(events, (event) => {
        submit.addEventListener(event, (e) => {
          sform.querySelector('label').classList.toggle('hover')
        })
      })
    })
  })

  /**
   * 月間アーカイブの階層化
   */
  document.addEventListener('DOMContentLoaded', () => {
    const widgetArchive = document.querySelectorAll('.widget_archive > ul')
    Array.prototype.forEach.call(widgetArchive, (archive) => {
      const monthList = document.createElement('ul')
      monthList.classList.add('month-list')
      let months = archive.querySelectorAll('li:not(.year-title)')
      // months = [...months].reverse()
      Array.prototype.forEach.call(months, (month) => {
        monthList.appendChild(month)
      })
      archive.querySelector('.year-title').appendChild(monthList)
    })
  })

  /**
   * 最近のコメント
   */
  document.addEventListener('DOMContentLoaded', () => {
    const widgetComments = document.querySelectorAll('.widget_recent_comments .recentcomments')
    Array.prototype.forEach.call(widgetComments, (comments) => {
      const author = comments.querySelector('.comment-author-link')
      const html = comments.querySelector('a')
      html.innerHTML = '<span class="entry-title">' + html.innerHTML + '</span>' + '<span class="comment-user">' + author.innerHTML + '</span>'
      comments.innerHTML = ''
      comments.appendChild(html)
    })
  })
})();


(function ($) {
  'use strict';
  var breakpoint = 768 // 画面幅(px)によるモバイル<PC表示切替

  /**
   * ページ内スクロール＆ページのトップへ
   */
  $(function () {
    var gotop = $('#gotop')
    var gotopBtn = $('#gotop-button')
    var mbWin
    gotop.prepend('<div class="gotop-cfg gotop-start gotop-end gotop-offset">')
    var cfg = $('.gotop-cfg', gotop)
    var start = parseInt(cfg.css('top')) || 0
    var bottom = parseInt(cfg.css('bottom')) || 0
    mbWin = {
      'bp': parseInt(cfg.css('width')) || breakpoint,
      'off': parseInt(cfg.css('margin-top')) || 0
    }
    var _move = function (anchor) {
      var offset = $(anchor).offset()
      var offt = document.documentElement.clientWidth < mbWin['bp'] ? mbWin['off'] : 0
      var pos = !offset ? 0 : offset.top - offt
      $('html,body').stop().animate({ scrollTop: pos }, 'normal', 'easing')
    }
    var _show = function (e) {
      var scrT = $(document).scrollTop()
      var btmT = $(document).height() - document.documentElement.clientHeight
      if (btmT > start) {
        gotopBtn.toggleClass('gotop-show', start <= scrT ? true : false)
        gotopBtn.toggleClass('gotop-bottom gotop-end', btmT - bottom <= scrT ? true : false)
      }
    }
    // 状態
    _show()
    $(window).on('scroll resize', _show)
    // リンク
    $('a[href*="#"]').on('click', function (e) {
      $(this).trigger('blur')
      var _anchor = $(this).attr('href').split('#')
      var anchor = '#PageTop'
      if (_anchor[1]) {
        anchor = '#' + _anchor[1]
      }
      if (
        anchor.match(/#(comment\-.*)?$/) ||
        anchor.match(/#respond/) ||
        anchor.match(/#(more\-.*)?$/) ||
        !$(anchor)[0]
      ) {
        return
      }
      e.preventDefault()
      _move(anchor)
    })
    // トップへ
    gotop.on('click', '.gotop-symbol', function (e) {
      $(this).trigger('blur')
      e.preventDefault()
      _move(null)
    })
  })

  /**
  * テーブルスクロール
  */
  $(function () {
    var spd = 5 // スクロールステップ(px)
    var ex = function (obj) {
      var ofx = Math.floor($('table', obj).outerWidth() - $(obj).outerWidth())
      var psx = $(obj).scrollLeft()
      if (psx <= 3) {
        $('.table-left-arrow', obj).fadeOut(200)
      } else {
        $('.table-left-arrow', obj).fadeIn(200)
      }
      if (psx >= ofx - 3) {
        $('.table-right-arrow', obj).fadeOut(200)
      } else {
        $('.table-right-arrow', obj).fadeIn(200)
      }
    }
    $('.entry-body table').wrap('<div class="table-content"><div class="table-area"></div></div>')
    $('.table-area')
      .prepend('<div class="table-arrow table-left-arrow"></div><div class="table-arrow table-right-arrow"></div>')
      .each(function () {
        var mvx, timer
        var obj = this
        $('.table-left-arrow, .table-right-arrow', this).on({
          mousedown: function () {
            var dir = $(this).hasClass('table-right-arrow')
            mvx = $(obj).scrollLeft()
            timer = setInterval(function () {
              var ofx = Math.ceil($('table', obj).outerWidth() - $(obj).outerWidth())
              if (mvx >= 0 && mvx <= ofx) {
                mvx = dir ? mvx + spd : mvx - spd
                if (mvx < 0) {
                  mvx = 0
                }
                $(obj).scrollLeft(mvx)
              } else {
                $(obj).scrollLeft()
                clearInterval(timer)
              }
            }, 5)
          },
          mouseup: function () {
            mvx = $(obj).scrollLeft()
            clearInterval(timer)
          },
          mouseout: function () {
            mvx = $(obj).scrollLeft()
            clearInterval(timer)
          }
        })
      })
      .on('scroll', function () {
        ex(this)
      })
    $(window).on('load resize', function () {
      ex($('.table-area'))
    })
  })

  /**
   * フォーム（bb-form-style）
   */
  $(window).on('load', function () {
    var $formBlock = '.bb-form-style'
    var $formTop = '.bb-form-style-top'
    var offset = {
      pc: 80,
      mb: 65
    }
    var move = function (pos) {
      pos = pos - (document.documentElement.clientWidth < breakpoint ? offset.mb : offset.pc)
      $('html,body').animate({ scrollTop: pos }, 200, 'easing')
    }

    // input: error
    // ---------------------------------
    $('.error', $formBlock).each(function (i) {
      var f = $(this)
      f.parents('.group').addClass('group-error')
      if (i === 0) {
        move(f.parents('.group').offset().top)
      }
      f.parent().on('focus click', 'input, select, textarea, .error', function () {
        if ($(this).hasClass('error')) {
          f.parent().find('input, select, textarea').trigger('focus')
        }
        f.parents('.group').removeClass('group-error')
        f.addClass('error-hidden')
      })
    })

    // MW WP Formプラグイン向け
    // ---------------------------------
    $('[name="submitBack"]', $formBlock).on('click', function () {
      localStorage.setItem('submitBack', 1)
    })
    if (localStorage.getItem('submitBack') == 1) {
      $($formBlock).before('<div class="bb-form-style-top"></div>')
      move($($formTop).offset().top)
    }
    localStorage.setItem('submitBack', null)
  })

  // easing
  $.extend($.easing, {
    easing: function (x) {
      return 1 - Math.pow(1 - x, 5)
    }
  })
})(jQuery);
