<fieldset class="taxonomy-layout">
  <div class="col-left">
    <div class="label-title">タクソノミー（カテゴリー・タグ等）の共通レイアウトタイプ</div>
  </div>
  <div class="col-right">
    <div class="group">
      <ul id="bb-taxonomy-layout">
        <li>
          <input type="radio" name="blankblanc_config_values[taxonomy_layout]" value="list" id="bb-taxonomy-layout-1"<?php echo $config_values['taxonomy_layout'] == 'list' ? ' checked' : ''; ?>>
          <label for="bb-taxonomy-layout-1" class="box"><span class="dashicons dashicons-list-view"></span><span class="title">リスト表示</span></label>
        </li>
        <li>
          <input type="radio" name="blankblanc_config_values[taxonomy_layout]" value="tiles" id="bb-taxonomy-layout-2"<?php echo $config_values['taxonomy_layout'] == 'tiles' ? ' checked' : ''; ?>>
          <label for="bb-taxonomy-layout-2" class="box"><span class="dashicons dashicons-grid-view"></span><span class="title">タイル表示</span></label>
        </li>
      </ul>
    </div>
    <div class="default">初期値: <?php _echo(@$bb_theme_default['taxonomy_layout']); ?></div>
  </div>
</fieldset>
