<?php // [extention] extensions/ex-mainvisual.php ---
  bb_theme_check();
  if (function_exists('call_bb_mainvisual_term_meta')) : ?>
  <fieldset id="bb-mainvisual" <?php $this->has_modified('mv_image', 'bb-media-upload'); ?>>
    <div class="col-left">
      <div class="media-title">共通メインビジュアル画像</div>
      <div class="label-title">共通メインビジュアル</div>
      <div class="note">メインビジュアルの指定が無いページで使用されます。<br>
      画像が未設定の場合、個別での指定の無いページではメインビジュアルを表示しません。</div>
    </div>
    <div class="col-right">
      <div class="input-group">
        <?php
        $nouse = (empty($config_values['mv_image']) || $config_values['mv_image'] == -1) ? true : false;
        if ($mv_image = wp_get_attachment_image_src($config_values['mv_image'], 600)) {
          $img = $mv_image[0];
        } else {
          $img = $bb_theme_default['mv_image'];
          $config_values['mv_image'] = $bb_theme_default['mv_image'];
        }
        ?>
        <div class="image-view">
          <?php if ($img && !$nouse) : ?>
            <img src="<?php echo $img; ?>" alt="">
          <?php else : ?>
            <span class="no-image">選択された画像はありません</span>
          <?php endif; ?>
        </div>
        <input type="hidden" name="blankblanc_config_values[mv_image]" class="image-id" value="<?php echo $config_values['mv_image']; ?>">
        <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['mv_image']; ?>">
        <input type="button" name="select" value="画像を選択" class="button button-secondary">
        <input type="button" name="reset" value="キャンセル" class="button button-secondary">
        <?php if (!$nouse) : ?>
          <input type="button" name="delete" value="削除する" class="button button-secondary">
        <?php endif; ?>
        <?php if ((isset($mv_image[0]) || $nouse) && $bb_theme_default['mv_image']) : ?>
          <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
        <?php endif; ?>
      </div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
