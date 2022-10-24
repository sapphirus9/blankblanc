<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('archive_thumbnail'); ?>>
  <div class="col-left">
    <div class="label-title">一覧ページのサムネイル画像<em>※必須</em></div>
    <div class="note">サムネイル画像の使用サイズと画像の切り出し方法を設定します。<br>
    ※値を変更した場合、既存画像については新たにファイルが生成されません。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <label for="bb-config-archive-thumbnail-1" class="prefix">幅</label>
      <input type="number" name="blankblanc_config_values[archive_thumbnail][0]" id="bb-config-archive-thumbnail-1" class="s-num" value="<?php echo $config_values['archive_thumbnail'][0]; ?>">
      <label for="bb-config-archive-thumbnail-1">px</label>
    </div>
    <div class="group">
      <label for="bb-config-archive-thumbnail-2" class="prefix">高さ</label>
      <input type="number" name="blankblanc_config_values[archive_thumbnail][1]" id="bb-config-archive-thumbnail-2" class="s-num" value="<?php echo $config_values['archive_thumbnail'][1]; ?>">
      <label for="bb-config-archive-thumbnail-2">px</label>
    </div>
    <div class="group">
      <label for="bb-config-archive-thumbnail-3" class="prefix">画像切り出し</label>
      <?php
      $select = $config_values['archive_thumbnail'][2];
      if (is_bool($select)) {
        $select = var_export($select, true);
      }
      ?>
      <select name="blankblanc_config_values[archive_thumbnail][2]" id="bb-config-archive-thumbnail-3">
        <option value="false"<?php if ($select === 'false') echo ' selected'; ?>>縮小 (false)</option>
        <option value="true"<?php if ($select === 'true') echo ' selected'; ?>>切り出し (true)</option>
        <option value="left,top"<?php if ($select === 'left,top') echo ' selected'; ?>>左／上 (left,top)</option>
        <option value="center,top"<?php if ($select === 'center,top') echo ' selected'; ?>>中／上 (center,top)</option>
        <option value="right,top"<?php if ($select === 'right,top') echo ' selected'; ?>>右／上 (right,top)</option>
        <option value="left,center"<?php if ($select === 'left,center') echo ' selected'; ?>>左／中 (left,center)</option>
        <option value="right,center"<?php if ($select === 'right,center') echo ' selected'; ?>>右／中 (right,center)</option>
        <option value="left,bottom"<?php if ($select === 'left,bottom') echo ' selected'; ?>>左／下 (left,bottom)</option>
        <option value="center,bottom"<?php if ($select === 'center,bottom') echo ' selected'; ?>>中／下 (center,bottom)</option>
        <option value="right,bottom"<?php if ($select === 'right,bottom') echo ' selected'; ?>>右／下 (right,bottom)</option>
      </select>
    </div>
    <div class="default">初期値: 幅 <?php _echo($bb_theme_default['archive_thumbnail'][0], '', 'px'); ?> ／高さ <?php _echo($bb_theme_default['archive_thumbnail'][1], '', 'px'); ?> ／画像切り出し <?php _echo($bb_theme_default['archive_thumbnail'][2]); ?></div>
  </div>
</fieldset>
