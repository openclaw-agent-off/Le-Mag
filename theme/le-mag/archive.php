<?php get_header(); ?>

<main class="container">
  <h1 class="section-title"><?php the_archive_title(); ?></h1>

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
          <div class="card-footer"><?php echo get_the_date(); ?></div>
        </div>
      </article>
    <?php endwhile; ?>
    </div>
    <div class="pagination"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('Aucun article.', 'lemag'); ?></p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
