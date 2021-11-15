<fieldset class="use-auto-slug">
  <div class="col-left">
    <div class="label-title">日本語タイトル時のスラッグ設定</div>
    <div class="note">投稿時に自動で設定される日本語スラッグを「接頭辞-ポストID（Ex. post-99）」に置き換えます。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[use_auto_slug]" value="false">
      <input type="checkbox" name="blankblanc_config_values[use_auto_slug]" id="bb-config-use-auto-slug" value="true"<?php if ($config_values['use_auto_slug']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo(@$bb_theme_default['use_auto_slug']); ?></div>
  </div>
</fieldset>

<fieldset class="auto-post-slug sub-field">
  <div class="col-left">
    <div class="label-title">設定するスラッグの接頭辞</div>
    <div class="note">無指定の時は {post_type} が接頭辞として使用されます。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="text" name="blankblanc_config_values[auto_post_slug]" id="bb-config-auto-post-slug" class="m-text" value="<?php echo esc_textarea($config_values['auto_post_slug']); ?>">
    </div>
    <div class="default">初期値: <?php _echo(@$bb_theme_default['auto_post_slug']); ?></div>
  </div>
</fieldset>
