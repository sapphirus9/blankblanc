<fieldset id="bb-logo-image" class="bb-media-upload">
  <div class="col-left">
    <div class="media-title">ロゴ画像</div>
    <div class="label-title">ロゴ画像の設定</div>
  </div>
  <div class="col-right">
    <div class="input-group">
      <?php
      $nouse = (empty($config_values['logo_image']) || $config_values['logo_image'] == -1) ? true : false;
      if ($logo_image = wp_get_attachment_image_src($config_values['logo_image'], 300)) {
        $img = $logo_image[0];
      } else {
        $img = $bb_theme_default['logo_image'];
        $config_values['logo_image'] = $bb_theme_default['logo_image'];
      }
      ?>
      <div class="image-view">
        <?php if ($img && !$nouse) : ?>
          <img src="<?php echo $img; ?>" alt="">
        <?php else : ?>
          <span class="no-image">選択された画像はありません</span>
        <?php endif; ?>
      </div>
      <input type="hidden" name="blankblanc_config_values[logo_image]" class="image-id" value="<?php echo $config_values['logo_image']; ?>">
      <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['logo_image']; ?>">
      <input type="button" name="select" value="ロゴ画像を選択" class="button button-secondary">
      <input type="button" name="reset" value="キャンセル" class="button button-secondary">
      <?php if (!$nouse) : ?>
        <input type="button" name="delete" value="削除する" class="button button-secondary">
      <?php endif; ?>
      <?php if ((isset($logo_image[0]) || $nouse) && $bb_theme_default['logo_image']) : ?>
        <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
      <?php endif; ?>
    </div>
  </div>
</fieldset>

<fieldset class="logo-alt">
  <div class="col-left">
    <div class="label-title">ロゴイメージの alt</div>
    <div class="note">画像を設定していない場合、ロゴの代わりにこのテキストが適用されます。</div>
  </div>
  <div class="col-right">
    <div class="group group-full">
      <input type="text" name="blankblanc_config_values[logo_alt]" id="bb-config-logo-alt" class="l-text" value="<?php echo esc_textarea($config_values['logo_alt']); ?>">
      <div class="default">初期値: <?php _echo(@$bb_theme_default['logo_alt']); ?></div>
    </div>
  </div>
</fieldset>

<fieldset class="logo-size">
  <div class="col-left">
    <div class="label-title">ロゴイメージサイズ</div>
    <div class="note">img に width, height を設定できますが、CSS の指定が優先されます。</div>
  </div>
  <div class="col-right">
    <?php
    if (empty($config_values['logo_size'])) {
      $config_values['logo_size'] = array('', '');
    }
    ?>
    <div class="group">
      <label for="bb-config-logo-size-1" class="prefix">幅</label>
      <input type="number" name="blankblanc_config_values[logo_size][0]" id="bb-config-logo-size-1" class="s-num" value="<?php echo $config_values['logo_size'][0]; ?>">
      <label for="bb-config-logo-size-1">px</label>
    </div>
    <div class="group">
      <label for="bb-config-logo-size-2" class="prefix">高さ</label>
      <input type="number" name="blankblanc_config_values[logo_size][1]" id="bb-config-logo-size-2" class="s-num" value="<?php echo $config_values['logo_size'][1]; ?>">
      <label for="bb-config-logo-size-2">px</label>
    </div>
    <div class="default">初期値: 幅 <?php _echo(@$bb_theme_default['logo_size'][0], '', 'px'); ?> ／高さ <?php _echo(@$bb_theme_default['logo_size'][1], '', 'px'); ?></div>
  </div>
</fieldset>
