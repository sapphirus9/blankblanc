<fieldset <?php $this->has_modified('taxonomy_layout'); ?>>
  <div class="col-left">
    <div class="label-title">共通の投稿一覧レイアウトタイプ</div>
  </div>
  <div class="col-right">
    <div class="group bb-term-layout">
      <?php
      $taxonomy_layout = !empty($config_values['taxonomy_layout']) ? $config_values['taxonomy_layout'] : $bb_theme_default['taxonomy_layout'];
      ?>
      <ul>
        <li>
          <input type="radio" name="blankblanc_config_values[taxonomy_layout]" value="list" id="bb-taxonomy-layout-1"<?php echo $taxonomy_layout == 'list' ? ' checked' : ''; ?>>
          <label for="bb-taxonomy-layout-1" class="box"><span class="dashicons dashicons-list-view"></span><span class="title">リスト表示</span></label>
        </li>
        <li>
          <input type="radio" name="blankblanc_config_values[taxonomy_layout]" value="tiles" id="bb-taxonomy-layout-2"<?php echo $taxonomy_layout == 'tiles' ? ' checked' : ''; ?>>
          <label for="bb-taxonomy-layout-2" class="box"><span class="dashicons dashicons-grid-view"></span><span class="title">タイル表示</span></label>
        </li>
      </ul>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['taxonomy_layout']); ?></div>
  </div>
</fieldset>
