<fieldset <?php $this->has_modified('fixed_widget'); ?>>
  <div class="col-left">
    <div class="label-title">サイドバー（ウィジェット）の固定</div>
    <div class="note">スクロール時にサイドバー（ウィジェット）を上限と下限で固定します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[fixed_widget]" value="false">
      <input type="checkbox" name="blankblanc_config_values[fixed_widget]" id="bb-config-fixed-widget" value="true"<?php if ($config_values['fixed_widget']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['fixed_widget']); ?></div>
  </div>
</fieldset>
