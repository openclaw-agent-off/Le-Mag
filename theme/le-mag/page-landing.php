<?php
/**
 * Template Name: Landing Page
 * Description: Page d'atterrissage sans sidebar ni footer, avec sections hero et CTA.
 */
get_header();
?>
<main class="lemag-landing">
  <?php while (have_posts()): the_post(); ?>
    <section class="lemag-landing-hero">
      <div class="container">
        <h1><?php the_title(); ?></h1>
        <?php if (has_excerpt()): ?>
          <p class="lemag-landing-subtitle"><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>
        <a href="#content" class="lemag-landing-cta"><?php esc_html_e('En savoir plus', 'lemag'); ?></a>
      </div>
    </section>
    <section class="container lemag-landing-content" id="content">
      <div class="entry-content"><?php the_content(); ?></div>
      <?php wp_link_pages(['before' => '<div class="page-links">' . __('Pages :', 'lemag'), 'after' => '</div>']); ?>
    </section>
  <?php endwhile; ?>
</main>
<?php
// Landing page : on omet le footer standard pour garder un focus conversion.
wp_footer();
?>
</body>
</html>
