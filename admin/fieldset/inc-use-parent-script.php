<?php if (is_child_theme()) : ?>
  <fieldset class="use-parent-script">
    <div class="col-left">
      <div class="label-title">親テーマのJavaScriptを利用</div>
      <div class="note">子テーマを利用時に親テーマの {theme}/assets/js/functions.js, {theme}/assets/js/mobile-nav.js ファイルの読み込みます。<br>
      指定しない場合は、親テーマの 各スクリプトファイルを子テーマの {child-theme}/assets/js ディレクトリーにコピーして利用してください。</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="hidden" name="blankblanc_config_values[with_parent_script]" value="false">
        <input type="checkbox" name="blankblanc_config_values[with_parent_script]" id="bb-config-use-parent-script" value="true"<?php if ($config_values['with_parent_script']) echo ' checked'; ?>>
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['with_parent_script']); ?></div>
    </div>
  </fieldset>
<?php endif;
