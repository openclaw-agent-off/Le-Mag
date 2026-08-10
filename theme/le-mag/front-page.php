<?php get_header(); ?>
<?php
// FIX: on rend d'abord le contenu Gutenberg de la page statique d'accueil (page 1346 « Magazine »),
// FIX: avant les sections magazine custom (hero, à la une, tendances, catégories).
if (have_posts()) {
    while (have_posts()) {
        the_post();
        the_content();
    }
    wp_reset_postdata();
}

$hero = new WP_Query(['posts_per_page' => 1]);
$secondary = new WP_Query(['posts_per_page' => 4, 'offset' => 1]);
$trending = lemag_trending_posts(5);
$categories = get_categories(['parent' => 0, 'hide_empty' => true, 'number' => 4]);
?>
<?php if ($hero->have_posts()): $hero->the_post(); ?>
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
        <span><?php esc_html_e('par', 'lemag'); ?> <?php the_author(); ?></span>
        <span><?php echo esc_html(sprintf(__('%d min de lecture', 'lemag'), lemag_reading_time())); ?></span>
      </div>
    </div>
  </section>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>

<main class="container">
  <?php if ($secondary->have_posts()): ?>
    <section class="lemag-front-secondary">
      <h2 class="section-title"><?php esc_html_e('À la une', 'lemag'); ?></h2>
      <div class="grid grid-4">
        <?php while ($secondary->have_posts()): $secondary->the_post(); ?>
          <article <?php post_class('card'); ?>>
            <?php if (has_post_thumbnail()): ?>
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
            <?php endif; ?>
            <div class="card-body">
              <span class="card-cat"><?php the_category(', '); ?></span>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 12)); ?></p>
              <div class="card-footer"><?php echo get_the_date(); ?> · <?php echo esc_html(sprintf(__('%d min', 'lemag'), lemag_reading_time())); ?></div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <?php if ($trending): ?>
    <section class="lemag-front-trending">
      <h2 class="section-title"><?php esc_html_e('Tendances', 'lemag'); ?></h2>
      <ol class="lemag-trending-list">
        <?php $n = 1; foreach ($trending as $p): setup_postdata($p); ?>
          <li class="lemag-trending-item">
            <span class="lemag-trending-num"><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></span>
            <div class="lemag-trending-body">
              <span class="card-cat"><?php echo get_the_category_list(', ', '', $p); ?></span>
              <h3><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h3>
              <span class="card-footer"><?php echo esc_html(get_the_date('', $p)); ?></span>
            </div>
          </li>
        <?php endforeach; wp_reset_postdata(); ?>
      </ol>
    </section>
  <?php endif; ?>

  <?php foreach ($categories as $cat): ?>
    <?php
    $cat_query = new WP_Query(['posts_per_page' => 3, 'cat' => $cat->term_id]);
    if (!$cat_query->have_posts()) continue;
    ?>
    <section class="lemag-front-cat-section" style="--accent:var(--red)">
      <div class="lemag-cat-header">
        <h2 class="section-title"><?php echo esc_html($cat->name); ?></h2>
        <a href="<?php echo esc_url(get_category_link($cat)); ?>" class="lemag-cat-more"><?php esc_html_e('Tout voir', 'lemag'); ?> →</a>
      </div>
      <div class="grid grid-3">
        <?php while ($cat_query->have_posts()): $cat_query->the_post(); ?>
          <article <?php post_class('card'); ?>>
            <?php if (has_post_thumbnail()): ?>
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
            <?php endif; ?>
            <div class="card-body">
              <span class="card-cat"><?php the_category(', '); ?></span>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 12)); ?></p>
              <div class="card-footer"><?php echo get_the_date(); ?> · <?php echo esc_html(sprintf(__('%d min', 'lemag'), lemag_reading_time())); ?></div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endforeach; ?>
</main>
<?php get_footer(); ?>
