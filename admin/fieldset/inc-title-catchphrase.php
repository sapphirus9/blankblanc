<fieldset <?php $this->has_modified('title_catchphrase'); ?>>
  <div class="col-left">
    <div class="label-title">titleに併記するキャッチフレーズ</div>
    <div class="note">無指定の場合、一般設定のキャッチフレーズを適用します。</div>
  </div>
  <div class="col-right">
    <div class="group group-full">
      <input type="text" name="blankblanc_config_values[title_catchphrase]" id="bb-config-title-catchphrase" class="l-text" value="<?php echo esc_textarea($config_values['title_catchphrase']); ?>">
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['title_catchphrase']); ?></div>
  </div>
</fieldset>
