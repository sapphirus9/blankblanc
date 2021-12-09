<?php // [extention] extensions/ex-table-of-contents.php ---
  if (function_exists('call_bb_table_of_contents')) : ?>
  <fieldset class="use-toc">
    <div class="col-left">
      <div class="label-title">目次機能の有効化</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="hidden" name="blankblanc_config_values[use_toc]" value="false">
        <input type="checkbox" name="blankblanc_config_values[use_toc]" id="bb-config-use-toc" value="true"<?php if ($config_values['use_toc']) echo ' checked'; ?>>
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['use_toc']); ?></div>
    </div>
  </fieldset>

  <fieldset class="toc-active">
    <div class="col-left">
      <div class="label-title">デフォルトの目次表示</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="hidden" name="blankblanc_config_values[toc_config][toc_active]" value="false">
        <input type="checkbox" name="blankblanc_config_values[toc_config][toc_active]" id="bb-config-toc-active" value="true"<?php if ($config_values['toc_config']['toc_active']) echo ' checked'; ?>>
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['toc_config']['toc_active']); ?></div>
    </div>
  </fieldset>

  <fieldset class="toc-title">
    <div class="col-left">
      <div class="label-title">目次ブロックのタイトル</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="text" name="blankblanc_config_values[toc_config][toc_title]" id="bb-config-toc-title" class="m-text" value="<?php echo esc_textarea($config_values['toc_config']['toc_title']); ?>">
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['toc_config']['toc_title']); ?></div>
    </div>
  </fieldset>

  <fieldset class="toc-hidden">
    <div class="col-left">
      <div class="label-title">目次から除外する見出し</div>
    </div>
    <div class="col-right">
      <input type="hidden" name="blankblanc_config_values[toc_config][toc_hidden]" value="false">
      <?php $headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        foreach ($headings as $heading) :
          $toc_hidden = $config_values['toc_config']['toc_hidden']; ?>
        <div class="group">
          <input type="checkbox" name="blankblanc_config_values[toc_config][toc_hidden][]" id="bb-config-toc-hidden-<?php echo $heading; ?>" value="<?php echo $heading; ?>"<?php if (is_array($toc_hidden) && array_search($heading, $toc_hidden) !== false) echo ' checked'; ?>>
          <label for="bb-config-toc-hidden-h1" class="suffix"><?php echo $heading; ?></label>
        </div>
      <?php endforeach; ?>
      <div class="default">初期値: <?php _echo(@implode(', ', $bb_theme_default['toc_config']['toc_hidden'])); ?></div>
    </div>
  </fieldset>

  <fieldset class="toc-prefix">
    <div class="col-left">
      <div class="label-title">目次アンカーIDに付加する文字列</div>
    </div>
    <div class="col-right">
      <div class="group">
        <input type="text" name="blankblanc_config_values[toc_config][toc_prefix]" id="bb-config-toc-prefix" class="m-text" value="<?php echo esc_textarea($config_values['toc_config']['toc_prefix']); ?>">
      </div>
      <div class="default">初期値: <?php _echo(@$bb_theme_default['toc_config']['toc_prefix']); ?></div>
    </div>
  </fieldset>

  <fieldset class="toc-position">
      <div class="col-left">
        <div class="label-title">目次の挿入場所</div>
        <div class="note">ボディ最上部:0／ボディ最下部:-1／x番目の見出し前:1~</div>
      </div>
      <div class="col-right">
        <div class="group">
          <input type="number" name="blankblanc_config_values[toc_config][toc_position]" id="bb-config-toc-position" class="s-num" value="<?php echo esc_attr($config_values['toc_config']['toc_position']); ?>">
        </div>
        <div class="default">初期値: <?php _echo(@$bb_theme_default['toc_config']['toc_position']); ?></div>
      </div>
  </fieldset>
<?php endif; // --- [extention]
