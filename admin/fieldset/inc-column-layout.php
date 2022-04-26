<?php // [extention] extensions/ex-page-layout.php ---
  if (function_exists('call_bb_page_layout_select')) : ?>
  <fieldset <?php $this->has_modified('column_layout'); ?>>
    <div class="col-left">
      <div class="label-title">共通のデフォルトカラムレイアウト</div>
      <div class="note">※新規投稿や作成時のみ有効で、すでに個別で設定されているページは適用されません。</div>
    </div>
    <div class="col-right">
      <div class="group">
        <ul>
          <?php
          global $bb_theme_config;
          $_layouts = new bbPageLayoutSelectMeta();
          $layouts = $_layouts->column_layout();
          unset($layouts['default']);
          $meta_data = isset($config_values['column_layout']) ? $config_values['column_layout'] : 'twocolumn';
          foreach ($layouts as $select) :
            $checked = $select['value'] == $meta_data ? ' checked' : '';
          ?>
            <li>
              <div class="group">
                <input name="blankblanc_config_values[column_layout]" type="radio" class="post-format" id="bb-column-layout-<?php echo $select['value']; ?>" value="<?php echo $select['value']; ?>"<?php echo $checked; ?>>
                <label for="bb-column-layout-<?php echo $select['value']; ?>" class="post-format-icon"><?php echo $select['label']; ?></label>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="default">初期値: <?php _echo($bb_theme_default['column_layout']); ?></div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
