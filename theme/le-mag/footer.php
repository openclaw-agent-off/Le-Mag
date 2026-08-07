</div><!-- #content -->

<footer class="site-footer">
  <div class="footer-grid">
    <div>
      <h4><?php bloginfo('name'); ?></h4>
      <p><?php bloginfo('description'); ?></p>
    </div>
    <div>
      <h4><?php esc_html_e('Rubriques', 'prism'); ?></h4>
      <ul>
        <?php wp_list_categories(['title_li' => false, 'depth' => 1, 'number' => 5]); ?>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e('Pages', 'prism'); ?></h4>
      <ul>
        <?php wp_list_pages(['title_li' => false, 'depth' => 1]); ?>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e('Légal', 'prism'); ?></h4>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/mentions-legales')); ?>">Mentions légales</a></li>
        <li><a href="<?php echo esc_url(home_url('/confidentialite')); ?>">Confidentialité</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bar">
    <span>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></span>
    <span><?php echo esc_html(apply_filters('prism_copyright', 'WordPress + Le Mag')); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
