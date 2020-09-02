(function ($) {
  // 設定の初期化
  $(function () {
    $('.button-reset').on('click', function (e) {
      if (!confirm('現在の設定を初期化してよろしいですか？')) {
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
    var target = '#bb-config-use-auto-slug';
    func();
    $(target).on('click', func);
    function func() {
      if ($(target).prop('checked')) {
        $('.auto-post-slug').fadeIn(300).css({ display: 'inline-block' });
      } else {
        $('.auto-post-slug').fadeOut(300);
      }
    }
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
            img.view.html('<img src="' + file.attributes.sizes.large.url + '" alt="">');
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
        $response = $(xml).find('term_id').text();
        if (typeof img !== 'undefined' && $response != '') {
          img.view.html(no_img);
        }
      }
    });
  });

  // タブ
  if ($.ui) {
    $(function () {
      $('#nav-tabs').tabs({
        activate: function (event, ui) {
          $('a', this).removeClass('nav-tab-active');
          $('a', ui.newTab).addClass('nav-tab-active');
        }
      });
    });
  }

  // モバイルナビ用ウィジェットの並べ替え
  if ($.ui) {
    $(function () {
      $('#activate-mobile-nav').sortable({
        delay: 200,
        opacity: 0.6,
        scroll: false
      }).bind('sortchange', function () {
        change_value = true;
      });
      $('#activate-mobile-nav > li').each(function () {
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
  $('#bb-config-edit form').on('change', function () {
    change_value = true;
  });
  var change_value_tinymce = function () {
    setTimeout(function () {
      if (typeof tinymce != 'function') return;
      tinymce.editors.forEach(function (ed) {
        ed.on('keyup', function () {
          change_value = true;
        });
      }, 1000);
    });
  };
  $(window).on('load', change_value_tinymce);
  $('.wp-switch-editor').on('click', change_value_tinymce);
  $('#bb-config-edit [type="submit"]').on('click', function () {
    change_value = false;
  });
  $('#addtag [type="submit"], #edittag [type="submit"]').on('click', function () {
    change_value = false;
  });
  $('#submitdiv [type="submit"]').on('click', function () {
    change_value = false;
  });
  $(window).on('beforeunload', function () {
    if (change_value) {
      return '';
    }
  });
})(jQuery);
