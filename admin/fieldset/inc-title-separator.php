<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('title_separator'); ?>>
  <div class="col-left">
    <div class="label-title">titleタグのセパレーター</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="text" name="blankblanc_config_values[title_separator]" id="bb-config-title-separator" class="s-text" value="<?php echo esc_textarea($config_values['title_separator']); ?>">
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['title_separator']); ?></div>
  </div>
</fieldset>
