<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('ja_auto_post_slug.rewrite'); ?>>
  <div class="col-left">
    <div class="label-title">日本語タイトル時のスラッグ設定</div>
    <div class="note">投稿時に自動で設定される日本語スラッグを「接頭辞-ポストID（e.g. post-99）」に置き換えます。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="hidden" name="blankblanc_config_values[ja_auto_post_slug][rewrite]" value="false">
      <input type="checkbox" name="blankblanc_config_values[ja_auto_post_slug][rewrite]" id="bb-config-ja-auto-post-slug" value="true"<?php if ($config_values['ja_auto_post_slug']['rewrite']) echo ' checked'; ?>>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['ja_auto_post_slug']['rewrite']); ?></div>
  </div>
</fieldset>

<div id="bb-config-ja-auto-post-slug_sub-field" class="sub-field-block">
  <div class="sub-field-block-inner">
    <fieldset <?php $this->has_modified('ja_auto_post_slug.prefix', 'sub-field'); ?>>
      <div class="col-left">
        <div class="label-title">設定するスラッグの接頭辞</div>
        <div class="note">無指定の時は {post_type} を接頭辞として使用します。</div>
      </div>
      <div class="col-right">
        <div class="group">
          <input type="text" name="blankblanc_config_values[ja_auto_post_slug][prefix]" class="m-text" value="<?php echo esc_textarea($config_values['ja_auto_post_slug']['prefix']); ?>">
        </div>
        <div class="default">初期値: <?php _echo($bb_theme_default['ja_auto_post_slug']['prefix']); ?></div>
      </div>
    </fieldset>
  </div>
</div>
