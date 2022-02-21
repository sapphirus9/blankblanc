<fieldset <?php $this->has_modified('more_text'); ?>>
  <div class="col-left">
    <div class="label-title">『続きを読む』の表記</div>
    <div class="note">投稿・固定ページの『続きを読む』の表記のしかたを設定します。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="text" name="blankblanc_config_values[more_text]" id="bb-config-more-text" class="m-text" value="<?php echo esc_textarea($config_values['more_text']); ?>">
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['more_text']); ?></div>
  </div>
</fieldset>
