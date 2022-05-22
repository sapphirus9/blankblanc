<fieldset <?php $this->has_modified('image_fade_in'); ?>>
  <div class="col-left">
    <div class="label-title">画面内に入ったら画像を表示</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[image_fade_in]" value="false">
      <input type="checkbox" name="blankblanc_config_values[image_fade_in]" id="bb-config-image-fade-in" value="true"<?php if ($config_values['image_fade_in']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['image_fade_in']); ?></div>
  </div>
</fieldset>
