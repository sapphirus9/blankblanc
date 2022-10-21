<div id="nav-window-area" class="is-mobile">
  <div id="nav-window-scroll">
    <div id="nav-window-container">
      <?php if (is_active_sidebar('mobile-widget-top')) : ?>
        <div id="mobile-widget-top" class="nav-block">
          <ol>
            <?php dynamic_sidebar('mobile-widget-top'); ?>
          </ol>
        </div>
      <?php endif; ?>
      <div id="nav-window-widgets" class="nav-block"></div>
      <?php if (is_active_sidebar('mobile-widget-bottom')) : ?>
        <div id="mobile-widget-bottom" class="nav-block">
          <ol>
            <?php dynamic_sidebar('mobile-widget-bottom'); ?>
          </ol>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
