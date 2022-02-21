<fieldset <?php $this->has_modified('homepage_layout.articles'); ?>>
  <div class="col-left">
    <div class="label-title">投稿一覧レイアウトタイプ</div>
    <div class="note">トップページを「<a href="<?php echo get_admin_url(); ?>options-reading.php">最新の投稿</a>」に設定した場合に有効です。</div>
  </div>
  <div class="col-right">
    <div class="group bb-term-layout">
      <?php
      $articles_layout = isset($config_values['homepage_layout']['articles']) ? $config_values['homepage_layout']['articles'] : $bb_theme_default['homepage_layout']['articles'];
      ?>
      <ul>
        <li>
          <input type="radio" name="blankblanc_config_values[homepage_layout][articles]" value="list" id="bb-homepage-articles-1"<?php echo $articles_layout == 'list' ? ' checked' : ''; ?>>
          <label for="bb-homepage-articles-1" class="box"><span class="dashicons dashicons-list-view"></span><span class="title">リスト表示</span></label>
        </li>
        <li>
          <input type="radio" name="blankblanc_config_values[homepage_layout][articles]" value="tiles" id="bb-homepage-articles-2"<?php echo $articles_layout == 'tiles' ? ' checked' : ''; ?>>
          <label for="bb-homepage-articles-2" class="box"><span class="dashicons dashicons-grid-view"></span><span class="title">タイル表示</span></label>
        </li>
      </ul>
    </div>
    <div class="default">初期値: <?php _echo($bb_theme_default['homepage_layout']['articles']); ?></div>
  </div>
</fieldset>
