<fieldset class="title-suffix">
  <div class="col-left">
    <div class="label-title">一覧ページのタイトル接尾辞</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="text" name="blankblanc_config_values[title_suffix]" id="bb-config-title-suffix" class="m-text" value="<?php echo esc_textarea($config_values['title_suffix']); ?>">
    </div>
    <div class="default">初期値: <?php _echo(@$bb_theme_default['title_suffix']); ?></div>
  </div>
</fieldset>
