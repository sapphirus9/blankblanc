<fieldset <?php $this->has_modified('add_body_class'); ?>>
  <div class="col-left">
    <div class="label-title">body_classの読み込み</div>
    <div class="note">WP標準のbody_class設定をbodyタグのclassに追加します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[add_body_class]" value="false">
      <input type="checkbox" name="blankblanc_config_values[add_body_class]" id="bb-config-add-body-class" value="true"<?php if ($config_values['add_body_class']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['add_body_class']); ?></div>
  </div>
</fieldset>
