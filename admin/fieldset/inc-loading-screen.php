<fieldset <?php $this->has_modified('loading_screen'); ?>>
  <div class="col-left">
    <div class="label-title">ローディング画面の表示</div>
    <div class="note">ページの読み込みが完了するまでローディング画面を表示します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[loading_screen]" value="false">
      <input type="checkbox" name="blankblanc_config_values[loading_screen]" id="bb-config-loading-screen" value="true"<?php if ($config_values['loading_screen']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['loading_screen']); ?></div>
  </div>
</fieldset>
