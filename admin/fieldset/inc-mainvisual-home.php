<?php // [extention] extensions/ex-mainvisual.php ---
  if (function_exists('call_bb_mainvisual_term_meta')) : ?>
  <fieldset id="bb-mainvisual-home" class="bb-media-upload">
    <div class="col-left">
      <div class="label-title">メインビジュアル</div>
      <div class="note">HomeまたはFront Pageが対象です。</div>
    </div>
    <div class="col-right">
      <div class="input-group">
        <?php
        $nouse = (empty($config_values['mv_home_image']) || $config_values['mv_home_image'] == -1) ? true : false;
        if ($mv_home_image = wp_get_attachment_image_src($config_values['mv_home_image'], 600)) {
          $img = $mv_home_image[0];
        } else {
          $img = $bb_theme_default['mv_home_image'];
          $config_values['mv_home_image'] = $bb_theme_default['mv_home_image'];
        }
        ?>
        <div class="image-view">
          <?php if ($img && !$nouse) : ?>
            <img src="<?php echo $img; ?>" alt="">
          <?php else : ?>
            <span class="no-image">選択された画像はありません</span>
          <?php endif; ?>
        </div>
        <input type="hidden" name="blankblanc_config_values[mv_home_image]" class="image-id" value="<?php echo $config_values['mv_home_image']; ?>">
        <input type="hidden" name="default-image" value="<?php echo $bb_theme_default['mv_home_image']; ?>">
        <input type="button" name="select" value="画像を選択" class="button button-secondary">
        <input type="button" name="reset" value="キャンセル" class="button button-secondary">
        <?php if (!$nouse) : ?>
          <input type="button" name="delete" value="削除する" class="button button-secondary">
        <?php endif; ?>
        <?php if ((isset($mv_home_image[0]) || $nouse) && $bb_theme_default['mv_home_image']) : ?>
          <input type="button" name="default" value="初期設定画像に戻す" class="button button-secondary">
        <?php endif; ?>
      </div>
    </div>
  </fieldset>

  <fieldset class="bb-mainvisual-home-content">
    <div class="col-left">
      <div class="label-title">メインビジュアル内コンテンツ</div>
      <div class="note">HomeまたはFront Pageが対象です。<br>
      #main-visual .mv-title-content内でのCSS利用を前提としています。</div>
    </div>
    <div class="col-right">
      <?php wp_editor(
        wp_unslash($config_values['mv_home_content']),
        'bb_config_mv_home_content',
        array(
          'textarea_name' => 'blankblanc_config_values[mv_home_content]',
          'editor_height' => 200,
          'teeny'         => true,
        )
      ); ?>
    </div>
  </fieldset>
<?php endif; // --- [extention]
