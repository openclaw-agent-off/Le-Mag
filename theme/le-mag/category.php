<?php get_header(); ?>
<main class="container">
  <?php echo lemag_breadcrumb(); ?>
  <?php
  $cat = get_queried_object();
  $hero_query = new WP_Query(['posts_per_page' => 1, 'cat' => $cat->term_id]);
  $rest_query = new WP_Query(['posts_per_page' => 8, 'offset' => 1, 'cat' => $cat->term_id]);
  ?>
  <header class="archive-header lemag-cat-header">
    <?php if ($cat->description): ?>
      <p class="archive-desc"><?php echo esc_html($cat->description); ?></p>
    <?php endif; ?>
    <h1 class="section-title"><?php single_cat_title(); ?></h1>
    <p class="archive-count"><?php echo esc_html(sprintf(_n('%d article', '%d articles', $cat->count, 'lemag'), $cat->count)); ?></p>
  </header>

  <?php if ($hero_query->have_posts()): $hero_query->the_post(); ?>
    <section class="hero lemag-cat-hero">
      <?php if (has_post_thumbnail()): ?>
        <?php the_post_thumbnail('full', ['class' => 'hero-img']); ?>
      <?php else: ?>
        <div class="hero-img" style="background:var(--black)"></div>
      <?php endif; ?>
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <span class="hero-cat"><?php the_category(', '); ?></span>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
        <div class="hero-meta">
          <span><?php the_date(); ?></span>
          <span><?php echo esc_html(sprintf(__('%d min de lecture', 'lemag'), lemag_reading_time())); ?></span>
          <?php if (get_comments_number()): ?>
            <span><?php comments_number('0', '1', '%'); ?> commentaires</span>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <?php if ($rest_query->have_posts()): ?>
    <div class="grid">
      <?php while ($rest_query->have_posts()): $rest_query->the_post(); ?>
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
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <div class="pagination"><?php the_posts_pagination(); ?></div>
</main>
<?php get_footer(); ?>
