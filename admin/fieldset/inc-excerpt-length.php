<fieldset class="excerpt-length">
    <div class="col-left">
      <div class="label-title">記事抜粋の文字数<em>※必須</em></div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="number" name="blankblanc_config_values[excerpt_length]" id="bb-config-excerpt-length" class="s-num" value="<?php echo esc_attr($config_values['excerpt_length']); ?>">
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['excerpt_length']); ?></div>
    </div>
</fieldset>
