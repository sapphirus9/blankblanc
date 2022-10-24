<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('output_canonical'); ?>>
  <div class="col-left">
    <div class="label-title">rel=canonical／prev／next出力</div>
    <div class="note">プラグイン等を利用して設定を行う場合はチェックを外します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[output_canonical]" value="false">
      <input type="checkbox" name="blankblanc_config_values[output_canonical]" id="bb-config-output-canonical" value="true"<?php if ($config_values['output_canonical']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['output_canonical']); ?></div>
  </div>
</fieldset>
