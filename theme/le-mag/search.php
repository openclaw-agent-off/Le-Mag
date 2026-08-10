<?php get_header(); ?>
<main class="container">
  <?php echo lemag_breadcrumb(); ?>
  <header class="archive-header">
    <h1 class="section-title"><?php printf(__('Résultats pour : %s', 'lemag'), '<span>' . esc_html(get_search_query()) . '</span>'); ?></h1>
    <p class="archive-count"><?php echo esc_html(sprintf(_n('%d article trouvé', '%d articles trouvés', $wp_query->found_posts, 'lemag'), $wp_query->found_posts)); ?></p>
  </header>
  <?php if (have_posts()): ?>
    <div class="grid">
      <?php while (have_posts()): the_post(); ?>
        <article <?php post_class('card'); ?>>
          <?php if (has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
          <?php endif; ?>
          <div class="card-body">
            <span class="card-cat"><?php the_category(', '); ?></span>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
            <div class="card-footer"><?php echo get_the_date(); ?> · <?php echo esc_html(sprintf(__('%d min', 'lemag'), lemag_reading_time())); ?></div>
          </div>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="pagination"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('Aucun résultat. Essayez d\'autres mots-clés.', 'lemag'); ?></p>
    <?php get_search_form(); ?>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
