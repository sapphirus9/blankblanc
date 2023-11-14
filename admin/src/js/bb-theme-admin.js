'use strict';
(function ($) {
  // 設定の初期化
  $(function () {
    $('.button-reset').on('click', function (e) {
      if (!confirm('現在の設定はすべてテーマの初期設定値に変更されます')) {
        e.preventDefault();
      }
    });
  });

  // バリデート
  $(function () {
    var obj = {
      'bb-config-archive-thumbnail-1': '【一覧ページのサムネイル画像】の『幅』が指定されていません',
      'bb-config-archive-thumbnail-2': '【一覧ページのサムネイル画像】の『高さ』が指定されていません',
      'bb-config-excerpt-length': '【記事抜粋の文字数】が指定されていません'
    };
    $('#blankblanc-config-save').on('click', function (e) {
      var err = [];
      var fcs = '';
      Object.keys(obj).forEach(function (key) {
        if (!$('#' + key).val()) {
          $('#' + key).addClass('error');
          if (!fcs) {
            fcs = $('#' + key);
          }
          err.push(obj[key]);
        }
      });
      if (err.length) {
        e.preventDefault();
        alert(err.join("\n"));
        fcs.focus();
      }
    });
  });

  // 日本語タイトル時のスラッグ設定
  $(function () {
    var targets = [
      '#bb-config-ja-auto-post-slug',
      '#bb-config-cookie-banner'
    ];
    $(targets).each(function (i, e) {
      func();
      $(e).on('click',func);
      function func() {
        $(e + '_sub-field').toggleClass('sub-field-block-open', $(e).prop('checked'));
      }
    });
  });

  // イメージアップロード
  $(function () {
    var upload_field = [
      '#bb-logo-image',
      '#bb-mainvisual',
      '#bb-mainvisual-home'
    ];
    $.each(upload_field, function (index, val) {
      var s = val + ' ';
      var btn = {
        reset: $(s + 'input:button[name="reset"]'),
        select: $(s + 'input:button[name="select"]'),
        default: $(s + 'input:button[name="default"]'),
        delete: $(s + 'input:button[name="delete"]')
      };
      var img = {
        id: $(s + '.image-id'),
        url: $(s + '.image-url'),
        view: $(s + '.image-view')
      };
      var v = img.id.val();
      var i = $(s + '.image-view img').attr('src');
      var no_img = '<span class="no-image">選択された画像はありません</span>';
      var upload;
      if (!img.view.html()) {
        img.view.html(no_img);
      }
      btn.reset.hide();
      btn.select.on('click', function (e) {
        e.preventDefault();
        if (upload) {
          upload.open();
          return;
        }
        upload = wp.media({
          title: $(s + '.media-title').text(),
          library: {
            type: 'image'
          },
          button: {
            text: '画像を選択'
          },
          multiple: false
        });
        upload.on('select', function () {
          change_value = true;
          var images = upload.state().get('selection');
          images.each(function (file) {
            img.id.val(file.id);
            img.url.val(file.attributes.url);
            img.view.html('<img src="' + file.attributes.url + '" alt="">');
            btn.reset.show();
          });
        });
        upload.open();
      });
      btn.reset.on('click', function (e) {
        change_value = true;
        e.preventDefault();
        if (v) {
          img.id.val(v);
          img.view.html('<img src="' + i + '" alt="">');
        } else {
          img.id.val('');
          img.view.html(no_img);
        }
        $(this).hide();
      });
      btn.default.on('click', function (e) {
        change_value = true;
        e.preventDefault();
        var di = $(s + 'input:hidden[name="default-image"]').val();
        btn.reset.show();
        if (di) {
          img.id.val(di);
          img.view.html('<img src="' + di + '" alt="">');
        } else {
          img.id.val('');
          img.view.html(no_img);
        }
      });
      btn.delete.on('click', function (e) {
        change_value = true;
        if (img.id.val()) {
          btn.reset.show();
        }
        img.id.val('');
        img.view.html(no_img);
      });
    });

    // ajax 利用の後処理
    $(document).ajaxComplete(function (event, xhr, settings) {
      var queryStringArr;
      try {
        queryStringArr = settings.data.split('&');
      } catch(e) {
        return false;
      }
      if ($.inArray('action=add-tag', queryStringArr) !== -1) {
        var xml = xhr.responseXML;
        var $response = $(xml).find('term_id').text();
        if (typeof img !== 'undefined' && $response != '') {
          img.view.html(no_img);
        }
      }
    });
  });

  // タブ
  if ($.ui && $.ui.tabs) {
    $(function () {
      var tabs = $('#nav-tabs');
      if (location.hash.substring(0, 4) == '#tab') {
        $(window).scrollTop(0);
        $('a', tabs).removeClass('nav-tab-active');
        $('a[href=' + location.hash + ']', tabs).addClass('nav-tab-active');
      }
      tabs.tabs({
        activate: function (event, ui) {
          $('a', this).removeClass('nav-tab-active');
          $('a', ui.newTab).addClass('nav-tab-active');
          $('#bb-config-edit > form').attr('action', $('a', ui.newTab).attr('href'));
        }
      });
    });
  }

  // モバイルナビ用ウィジェットの並べ替え
  if ($.ui && $.ui.sortable) {
    $(function () {
      $('#activate-mobile-nav, #activate-mobile-nav-footer').sortable({
        delay: 200,
        opacity: 0.6,
        scroll: false
      }).bind('sortchange', function () {
        change_value = true;
      });
      $('#activate-mobile-nav > li, #activate-mobile-nav-footer > li').each(function () {
        var cb = $('input:checkbox', this);
        $(this)
        .toggleClass('active', cb.prop('checked'))
        .on('click', function () {
          $(this).toggleClass('active', cb.prop('checked'));
        });
      });
    });
  }

  // 項目の変更有無を確認
  var change_value = false;
  $(window).on('load', function () {
    if (typeof tinymce == 'object') {
      tinymce.editors.forEach(function (ed) {
        ed.on('keyup', function () {
          change_value = true;
        });
      });
    }
    var _change = [
      '.bb-confirm-changes',
      '#bb-config-edit form'
    ];
    $(_change).each(function () {
      $(this).on('change', function () {
        change_value = true;
      });
    })
    var _click = [
      '#editor .editor-post-publish-button',
      '#bb-config-edit [type="submit"]',
      '#addtag [type="submit"]',
      '#edittag [type="submit"]',
      '#submitdiv [type="submit"]'
    ];
    $(_click).each(function () {
      $(this).on('click', function () {
        change_value = false;
      });
    });
  });
  $(window).on('beforeunload', function (e) {
    if ($('#addtag, #edittag').length) {
      change_value = false;
    }
    if (change_value) {
      e.preventDefault();
      return '';
    }
  });

  // テーマオプションを表示
  $(window).on('load', function () {
    $('#bb-config-edit, .bb-mainvisual-edit').addClass('loaded');
  });

  // ウィジェット: 有効化
  $(function () {
    $('.activate-block-header').each(function () {
      var $elem = $(this);
      $('input[type="checkbox"]', this).on('click', function () {
        $elem.next().toggleClass('active', $(this).prop('checked'));
      });
    });
  });
})(jQuery);
