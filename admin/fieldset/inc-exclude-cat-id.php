<?php // [extention] ex-exclude-categories.php ---
  bb_theme_check();
  if (class_exists('bbExcludeCategories')) : ?>
  <fieldset <?php $this->has_modified('exclude_cat_id'); ?>>
    <div class="col-left">
      <div class="label-title">除外対象のカテゴリーID</div>
      <div class="note">特定のカテゴリーをカテゴリーウィジェットやフィード等の対象から除外します。（カンマ区切り）</div>
    </div>
    <div class="col-right">
      <div class="group group-full">
        <input type="text" name="blankblanc_config_values[exclude_cat_id]" id="bb-config-exclude-cat-id" class="l-text" value="<?php echo esc_attr($config_values['exclude_cat_id']); ?>">
      </div>
      <div class="default">初期値: <?php _echo($bb_theme_default['exclude_cat_id']); ?></div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
