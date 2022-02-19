<fieldset class="favicon">
  <div class="col-left">
    <div class="label-title">favicon画像のURL</div>
    <div class="note">通常はico画像またはpng画像を用意してください。<br>
    （16px＋32px＋48px等マルチアイコン化のico画像が望ましい）<br>
    ※テーマカスタマイザでサイトアイコンが設定されている場合、これは使用されません。</div>
  </div>
  <div class="col-right">
    <div class="group group-full">
      <input type="url" name="blankblanc_config_values[favicon]" id="bb-config-favicon" class="l-text" value="<?php echo esc_textarea($config_values['favicon']); ?>">
      <div class="default">初期値: <?php _echo($bb_theme_default['favicon']); ?></div>
    </div>
  </div>
</fieldset>

<fieldset class="siteicon">
  <div class="col-left">
    <div class="label-title">サイトアイコン画像のURL</div>
    <div class="note">通常はpng画像を用意してください。<br>
    （192×192pxくらいで正方形のpng画像が望ましい）<br>
    ※テーマカスタマイザでサイトアイコンが設定されている場合、これは使用されません。</div>
  </div>
  <div class="col-right">
    <div class="group group-full">
      <input type="url" name="blankblanc_config_values[siteicon]" id="bb-config-siteicon" class="l-text" value="<?php echo esc_textarea($config_values['siteicon']); ?>">
      <div class="default">初期値: <?php _echo($bb_theme_default['siteicon']); ?></div>
    </div>
  </div>
</fieldset>
