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
      // Navigation article précédent / suivant
      $prev = get_previous_post();
      $next = get_next_post();
      if ($prev || $next): ?>
        <nav class="post-nav">
          <?php if ($prev): ?>
            <a href="<?php echo get_permalink($prev); ?>" class="post-nav-link post-nav-prev">
              <span class="post-nav-label"><?php esc_html_e('← Article précédent', 'prism'); ?></span>
              <span class="post-nav-title"><?php echo get_the_title($prev); ?></span>
            </a>
          <?php else: ?>
            <span class="post-nav-link post-nav-empty"></span>
          <?php endif; ?>
          <?php if ($next): ?>
            <a href="<?php echo get_permalink($next); ?>" class="post-nav-link post-nav-next">
              <span class="post-nav-label"><?php esc_html_e('Article suivant →', 'prism'); ?></span>
              <span class="post-nav-title"><?php echo get_the_title($next); ?></span>
            </a>
          <?php else: ?>
            <span class="post-nav-link post-nav-empty"></span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>

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
