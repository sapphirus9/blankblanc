<fieldset class="date-format">
  <div class="col-left">
    <div class="label-title">年月日個別フォーマット</div>
  </div>
  <div class="col-right">
    <div class="group">
      <label for="bb-config-date-format-1" class="prefix">年表示</label>
      <input type="text" name="blankblanc_config_values[date_format][0]" id="bb-config-date-format-1" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][0]); ?>">
    </div>
    <div class="group">
      <label for="bb-config-date-format-2" class="prefix">月表示</label>
      <input type="text" name="blankblanc_config_values[date_format][1]" id="bb-config-date-format-2" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][1]); ?>">
    </div>
    <div class="group">
      <label for="bb-config-date-format-3" class="prefix">日表示</label>
      <input type="text" name="blankblanc_config_values[date_format][2]" id="bb-config-date-format-3" class="s-text" value="<?php echo esc_textarea($config_values['date_format'][2]); ?>">
    </div>
    <div class="default">初期値: <?php _echo(implode('／', $bb_theme_default['date_format'])); ?></div>
  </div>
</fieldset>
