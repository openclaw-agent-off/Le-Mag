</div><!-- #content -->

<footer class="site-footer">
  <div class="footer-grid">
    <div>
      <h4><?php bloginfo('name'); ?></h4>
      <p><?php bloginfo('description'); ?></p>
      <?php do_action('lemag_footer_social'); ?>
    </div>
    <div>
      <h4><?php esc_html_e('Rubriques', 'lemag'); ?></h4>
      <ul>
        <?php wp_list_categories(['title_li' => false, 'depth' => 1, 'number' => 5]); ?>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e('Pages', 'lemag'); ?></h4>
      <ul>
        <?php wp_list_pages(['title_li' => false, 'depth' => 1]); ?>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e('Légal', 'lemag'); ?></h4>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/mentions-legales')); ?>">Mentions légales</a></li>
        <li><a href="<?php echo esc_url(home_url('/confidentialite')); ?>">Confidentialité</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
      </ul>
    </div>
    <?php
    $hf = function_exists('lemag_hf_settings') ? lemag_hf_settings() : [];
    if (!isset($hf['footer_newsletter']) || !empty($hf['footer_newsletter'])):
    ?>
    <div>
      <h4><?php esc_html_e('Newsletter', 'lemag'); ?></h4>
      <p><?php esc_html_e('Recevez nos meilleurs articles chaque semaine.', 'lemag'); ?></p>
      <form class="lemag-newsletter lemag-newsletter-footer" onsubmit="return false;">
        <input type="email" placeholder="<?php esc_attr_e('Votre email', 'lemag'); ?>" required>
        <button type="submit"><?php esc_html_e('S\'abonner', 'lemag'); ?></button>
      </form>
    </div>
    <?php endif; ?>
  </div>
  <?php if (is_active_sidebar('sidebar-footer')): ?>
    <div class="footer-widgets"><?php dynamic_sidebar('sidebar-footer'); ?></div>
  <?php endif; ?>
  <div class="footer-bar">
    <span>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></span>
    <span><?php echo esc_html(apply_filters('lemag_copyright', 'WordPress + Le Mag')); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>