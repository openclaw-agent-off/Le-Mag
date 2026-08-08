<?php get_header(); ?>

<div class="article-layout container">

  <article <?php post_class('article-content'); ?>>
    <?php while (have_posts()): the_post(); ?>
      <span class="hero-cat"><?php the_category(', '); ?></span>
      <h1><?php the_title(); ?></h1>
      <div class="hero-meta">
        <span><?php echo get_avatar(get_the_author_meta('ID'), 24, '', '', ['class' => 'avatar-img']); ?></span>
        <span><?php esc_html_e('Par', 'prism'); ?> <?php the_author(); ?></span>
        <span><?php echo get_the_date(); ?></span>
      </div>
      <?php if (has_post_thumbnail()): ?>
        <figure class="post-thumbnail"><?php the_post_thumbnail('full'); ?></figure>
      <?php endif; ?>
      <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages(['before' => '<div class="page-links">' . __('Pages :', 'prism'), 'after' => '</div>']); ?>
      </div>
      <div class="entry-tags"><?php the_tags('', ' ', ''); ?></div>

      <?php
      // Articles similaires (même catégorie)
      $cats = wp_get_post_categories(get_the_ID(), ['fields' => 'ids']);
      if ($cats):
        $related = new WP_Query([
          'category__in'   => $cats,
          'post__not_in'   => [get_the_ID()],
          'posts_per_page' => 3,
          'ignore_sticky_posts' => 1,
        ]);
        if ($related->have_posts()): ?>
          <div class="related-posts">
            <h3 class="related-title"><?php esc_html_e('Articles similaires', 'prism'); ?></h3>
            <div class="related-grid">
              <?php while ($related->have_posts()): $related->the_post(); ?>
                <article class="related-card">
                  <?php if (has_post_thumbnail()): ?>
                    <a href="<?php the_permalink(); ?>" class="related-thumb"><?php the_post_thumbnail('medium'); ?></a>
                  <?php endif; ?>
                  <div class="related-body">
                    <span class="related-cat"><?php the_category(', '); ?></span>
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <span class="related-date"><?php echo get_the_date(); ?></span>
                  </div>
                </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </div>
        <?php endif;
      endif; ?>

      <?php
      if (comments_open() || get_comments_number()):
        comments_template();
      endif;
      ?>
    <?php endwhile; ?>
  </article>

  <aside class="sidebar">
    <?php if (is_active_sidebar('sidebar-1')): ?>
      <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else: ?>
      <div class="sidebar-box">
        <h3><?php esc_html_e('Derniers articles', 'prism'); ?></h3>
        <ul>
          <?php
          $recent = new WP_Query(['posts_per_page' => 5]);
          while ($recent->have_posts()): $recent->the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>

</div>

<?php get_footer(); ?>
