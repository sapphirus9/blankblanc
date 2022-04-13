<fieldset <?php $this->has_modified('cookie_banner.indicate'); ?>>
  <div class="col-left">
    <div class="label-title">Cookieの使用同意画面を表示</div>
    <div class="note">表示用テンプレートはテーマフォルダ内の「includes/inc-cookie-banner.php」です。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[cookie_banner][indicate]" value="false">
      <input type="checkbox" name="blankblanc_config_values[cookie_banner][indicate]" id="bb-config-cookie-banner" value="true"<?php if ($config_values['cookie_banner']['indicate']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['cookie_banner']['indicate']); ?></div>
  </div>
</fieldset>

<div id="bb-config-cookie-banner_sub-field" class="sub-field-block">
  <div class="sub-field-block-inner">
    <fieldset <?php $this->has_modified('cookie_banner.text', 'sub-field'); ?>>
      <div class="col-left">
        <div class="label-title">表示する内容</div>
      </div>
      <div class="col-right">
        <?php /*
        <div class="group group-block">
          <textarea name="blankblanc_config_values[cookie_banner][text]"><?php echo esc_textarea(wp_unslash($config_values['cookie_banner']['text'])); ?></textarea>
        </div>
        */ ?>
        <?php wp_editor(
          wp_unslash($config_values['cookie_banner']['text']),
          'bb_config_cookie_banner_text',
          array(
            'textarea_name' => 'blankblanc_config_values[cookie_banner][text]',
            'editor_height' => 115,
            'media_buttons' => false,
            'teeny'         => true,
          )
        ); ?>
        <div class="default">初期値: <?php _echo($bb_theme_default['cookie_banner']['text']); ?></div>
      </div>
    </fieldset>

    <fieldset <?php $this->has_modified('cookie_banner.label', 'sub-field'); ?>>
      <div class="col-left margin-top">
        <div class="label-title">ボタンのラベル</div>
      </div>
      <div class="col-right margin-top">
        <div class="group">
          <input type="text" name="blankblanc_config_values[cookie_banner][label]" class="m-text" value="<?php echo esc_textarea($config_values['cookie_banner']['label']); ?>">
        </div>
        <div class="default">初期値: <?php _echo($bb_theme_default['cookie_banner']['label']); ?></div>
      </div>
    </fieldset>
  </div>
</div>
