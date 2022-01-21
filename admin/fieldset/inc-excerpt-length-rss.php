<fieldset class="excerpt-length-rss">
  <div class="col-left">
    <div class="label-title">RSSの記事出力文字数</div>
    <div class="note">無指定の場合、記事抜粋の文字数が適用されます。</div>
  </div>
  <div class="col-right">
    <div class="group">
      <input type="number" name="blankblanc_config_values[excerpt_length_rss]" id="bb-config-excerpt-length-rss" class="s-num" value="<?php echo esc_attr($config_values['excerpt_length_rss']); ?>">
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['excerpt_length_rss']); ?></div>
  </div>
</fieldset>
