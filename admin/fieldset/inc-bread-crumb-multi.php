<?php // [extention] ex-breadcrumb.php ---
  if (class_exists('bbBreadCrumb')) : ?>
  <fieldset class="bread-crumb-multi">
    <div class="col-left">
      <div class="label-title">カテゴリー毎（複数）のパンくず表示</div>
      <div class="note">投稿ページにおいて、属するカテゴリーが複数指定の場合、カテゴリー毎（複数）のパンくずリストを表示します。</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="hidden" name="blankblanc_config_values[bread_crumb_multi]" value="false">
        <input type="checkbox" name="blankblanc_config_values[bread_crumb_multi]" id="bb-config-bread-crumb-multi" value="true"<?php if ($config_values['bread_crumb_multi']) echo ' checked'; ?>>
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['bread_crumb_multi']); ?></div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
