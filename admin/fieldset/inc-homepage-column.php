<?php // [extention] extensions/ex-page-layout.php ---
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
          $layouts = array(
            array(
              'id'    => 'twocolumn',
              'value' => 'default',
              'label' => '2カラム（デフォルト）',
            ),
            array(
              'id'    => 'onecolumn',
              'value' => 'onecolumn',
              'label' => '1カラム幅固定',
            ),
            array(
              'id'    => 'fullwidth',
              'value' => 'fullwidth',
              'label' => '1カラム全幅',
            ),
            array(
              'id'    => 'nowrapwidth',
              'value' => 'nowrapwidth',
              'label' => '画面全幅',
            ),
          );
          $meta_data = isset($config_values['homepage_layout']['column']) ? $config_values['homepage_layout']['column'] : '';
          foreach ($layouts as $select) :
            $checked = ((empty($meta_data) && $select['value'] == 'default') || $meta_data == $select['value']) ? ' checked' : '';
          ?>
            <li>
              <div class="group">
                <input name="blankblanc_config_values[homepage_layout][column]" type="radio" class="post-format" id="bb-homepage-column-<?php echo $select['id']; ?>" value="<?php echo $select['value']; ?>"<?php echo $checked; ?>>
                <label for="bb-homepage-column-<?php echo $select['id']; ?>" class="post-format-icon"><?php echo $select['label']; ?></label>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="default">初期値: <?php _echo($bb_theme_default['homepage_layout']['column']); ?></div>
    </div>
  </fieldset>
<?php endif; // --- [extention]
