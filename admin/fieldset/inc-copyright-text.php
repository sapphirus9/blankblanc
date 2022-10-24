<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('copyright'); ?>>
  <div class="col-left">
    <div class="label-title">コピーライトの表記</div>
    <div class="note">テキストが無指定の場合、コピーライトは出力されません。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <label for="bb-config-copyright-text-1" class="prefix">接頭辞</label>
      <input type="text" name="blankblanc_config_values[copyright][prefix]" id="bb-config-copyright-text-1" class="m-text" value="<?php echo $config_values['copyright']['prefix']; ?>">
    </div>
    <div class="group">
      <label for="bb-config-copyright-text-2" class="prefix">開始年</label>
      <input type="number" name="blankblanc_config_values[copyright][start_year]" id="bb-config-copyright-text-2" class="s-num" value="<?php echo $config_values['copyright']['start_year']; ?>">
    </div>
    <div class="group">
      <label for="bb-config-copyright-text-3" class="prefix">テキスト</label>
      <input type="text" name="blankblanc_config_values[copyright][text]" id="bb-config-copyright-text-3" class="l-text" value="<?php echo $config_values['copyright']['text']; ?>">
    </div>
    <div class="group">
      <label for="bb-config-copyright-text-4" class="suffix">接尾辞</label>
      <input type="text" name="blankblanc_config_values[copyright][suffix]" id="bb-config-copyright-text-4" class="m-text" value="<?php echo $config_values['copyright']['suffix']; ?>">
    </div>
    <div class="default">初期値: 接頭辞 <?php _echo($bb_theme_default['copyright']['prefix']); ?>／開始年 <?php _echo($bb_theme_default['copyright']['start_year']); ?>／テキスト <?php _echo($bb_theme_default['copyright']['text']); ?>／接尾辞 <?php _echo($bb_theme_default['copyright']['suffix']); ?></div>
  </div>
</fieldset>
