<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('disable_emoji'); ?>>
  <div class="col-left">
    <div class="label-title">絵文字の無効化</div>
    <div class="note">絵文字に関連するjs, css, dns-prefetchを無効化します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[disable_emoji]" value="false">
      <input type="checkbox" name="blankblanc_config_values[disable_emoji]" id="bb-config-disable-emoji" value="true"<?php if ($config_values['disable_emoji']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['disable_emoji']); ?></div>
  </div>
</fieldset>
