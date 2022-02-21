<?php if (is_child_theme()) : ?>
  <fieldset <?php $this->has_modified('with_parent_css'); ?>>
    <div class="col-left">
      <div class="label-title">親テーマのCSSを利用</div>
      <div class="note">子テーマを利用時に親テーマのCSSファイル（{theme}/assets/css/theme.css）を読み込みます。<br>
      ※指定しない場合は、親テーマのCSSを子テーマにコピー（{child-theme}/assets/css/theme.css）して利用してください。</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="hidden" name="blankblanc_config_values[with_parent_css]" value="false">
        <input type="checkbox" name="blankblanc_config_values[with_parent_css]" id="bb-config-use-parent-css" value="true"<?php if ($config_values['with_parent_css']) echo ' checked'; ?>>
      </div>
      <div class="default">初期値: <?php _echo($bb_theme_default['with_parent_css']); ?></div>
    </div>
  </fieldset>
<?php endif;
