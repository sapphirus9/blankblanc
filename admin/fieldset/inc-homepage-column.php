<?php // [extention] extensions/ex-page-layout.php ---
  bb_theme_check();
  if (function_exists('call_bb_page_layout_select')) : ?>
  <fieldset <?php $this->has_modified('homepage_layout.column'); ?>>
    <div class="col-left">
      <div class="label-title">カラムレイアウト</div>
      <div class="note">トップページを「<a href="<?php echo get_admin_url(); ?>options-reading.php">最新の投稿</a>」に設定した場合に有効です。</div>
    </div>
    <div class="col-right">
      <div class="group">
        <ul>
          <?php
          global $bb_theme_config;
          $_layouts = new bbPageLayoutSelectMeta();
          $layouts = $_layouts->column_layout();
          unset($layouts['default']);
          $meta_data = isset($config_values['homepage_layout']['column']) ? $config_values['homepage_layout']['column'] : 'twocolumn';
          foreach ($layouts as $select) :
            $checked = $select['value'] == $meta_data ? ' checked' : '';
          ?>
            <li>
              <div class="group">
                <input name="blankblanc_config_values[homepage_layout][column]" type="radio" class="post-format" id="bb-homepage-column-<?php echo $select['value']; ?>" value="<?php echo $select['value']; ?>"<?php echo $checked; ?>>
                <label for="bb-homepage-column-<?php echo $select['value']; ?>" class="post-format-icon"><?php echo $select['label']; ?></label>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="default">初期値: <?php _echo($bb_theme_default['homepage_layout']['column']); ?></div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
