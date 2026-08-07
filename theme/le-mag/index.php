<?php get_header(); ?>

<?php if (have_posts()): ?>
  <?php
  // Hero : premier article (sticky ou dernier publié)
  $hero_id = 0;
  $hero = new WP_Query(['posts_per_page' => 1, 'post__in' => get_option('sticky_posts') ?: [], 'ignore_sticky_posts' => empty(get_option('sticky_posts'))]);
  if (!$hero->have_posts()) { $hero = new WP_Query(['posts_per_page' => 1]); }
  if ($hero->have_posts()): $hero->the_post();
  $hero_id = get_the_ID();
  ?>
  <section class="hero">
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
        <?php if (get_comments_number()): ?>
          <span><?php comments_number('0', '1', '%'); ?> commentaires</span>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php wp_reset_postdata(); endif; ?>

<main class="container">

  <div class="section-header">
    <h2 class="section-title"><?php esc_html_e('Derniers articles', 'prism'); ?></h2>
  </div>

  <?php
  // Exclure l'article hero sans casser la pagination.
  $paged = get_query_var('paged') ?: 1;
  $query = new WP_Query([
    'posts_per_page' => 9,
    'paged'          => $paged,
    'post__not_in'   => (is_front_page() && $hero_id) ? [$hero_id] : [],
  ]);
  ?>

  <?php if ($query->have_posts()): ?>
    <div class="grid">
    <?php while ($query->have_posts()): $query->the_post(); ?>
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
    <div class="pagination">
      <?php
      echo paginate_links([
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'type'    => 'list',
      ]);
      ?>
    </div>
  <?php else: ?>
    <p><?php esc_html_e('Aucun article.', 'prism'); ?></p>
  <?php endif; wp_reset_postdata(); ?>

</main>

<?php else: ?>
  <main class="container">
    <p><?php esc_html_e('Aucun article.', 'prism'); ?></p>
  </main>
<?php endif; ?>

<?php get_footer(); ?>
