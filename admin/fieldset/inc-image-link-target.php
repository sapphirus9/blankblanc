<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('image_link_target'); ?>>
  <div class="col-left">
    <div class="label-title">画像リンクを別窓で開く</div>
    <div class="note">画像へのリンクはすべて別窓（_blank）として開きます。<br>※対象：jpg, png, gif, svg, pdf</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[image_link_target]" value="false">
      <input type="checkbox" name="blankblanc_config_values[image_link_target]" id="bb-config-image-link-target" value="true"<?php if ($config_values['image_link_target']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['image_link_target']); ?></div>
  </div>
</fieldset>
