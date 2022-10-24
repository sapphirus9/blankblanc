<?php bb_theme_check(); ?>
<fieldset <?php $this->has_modified('excerpt_more'); ?>>
  <div class="col-left">
    <div class="label-title">記事抜粋時の省略表記</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="text" name="blankblanc_config_values[excerpt_more]" id="bb-config-excerpt-more" class="m-text" value="<?php echo esc_textarea($config_values['excerpt_more']); ?>">
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['excerpt_more']); ?></div>
  </div>
</fieldset>
