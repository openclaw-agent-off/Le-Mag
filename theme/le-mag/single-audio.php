<?php
/**
 * Template pour articles avec audio (format audio / podcast).
 */
get_header();
?>
<div class="article-layout container">
  <article <?php post_class('article-content lemag-single-audio'); ?>>
    <?php while (have_posts()): the_post(); ?>
      <?php echo lemag_breadcrumb(); ?>
      <span class="hero-cat"><?php the_category(', '); ?></span>
      <h1><?php the_title(); ?></h1>
      <div class="hero-meta">
        <span><?php echo get_avatar(get_the_author_meta('ID'), 24, '', '', ['class' => 'avatar-img']); ?></span>
        <span><?php esc_html_e('Par', 'lemag'); ?> <?php the_author_posts_link(); ?></span>
        <span><?php echo get_the_date(); ?></span>
        <span><?php echo esc_html(sprintf(__('%d min de lecture', 'lemag'), lemag_reading_time())); ?></span>
        <span><?php esc_html_e('Podcast', 'lemag'); ?></span>
      </div>
      <div class="lemag-audio-player">
        <?php
        $blocks = parse_blocks(get_the_content());
        foreach ($blocks as $block) {
            if ($block['blockName'] === 'core/audio' || $block['blockName'] === 'core/file') {
                echo render_block($block);
                break;
            }
        }
        ?>
      </div>
      <?php if (has_post_thumbnail()): ?>
        <figure class="post-thumbnail"><?php the_post_thumbnail('full'); ?></figure>
      <?php endif; ?>
      <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages(['before' => '<div class="page-links">' . __('Pages :', 'lemag'), 'after' => '</div>']); ?>
      </div>
      <div class="entry-tags"><?php the_tags('', ' ', ''); ?></div>
      <div class="lemag-article-share">
        <span class="lemag-share-label"><?php esc_html_e('Partager :', 'lemag'); ?></span>
        <?php echo lemag_share_buttons(); ?>
      </div>
      <?php
      $related = lemag_related_posts(3);
      if ($related): ?>
        <section class="lemag-related">
          <h2 class="section-title"><?php esc_html_e('À lire aussi', 'lemag'); ?></h2>
          <div class="grid grid-3">
            <?php foreach ($related as $p): setup_postdata($p); ?>
              <article <?php post_class('card'); ?>>
                <?php if (has_post_thumbnail($p)): ?>
                  <a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo get_the_post_thumbnail($p, 'medium_large'); ?></a>
                <?php endif; ?>
                <div class="card-body">
                  <span class="card-cat"><?php echo get_the_category_list(', ', '', $p); ?></span>
                  <h3><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h3>
                  <span class="card-footer"><?php echo esc_html(get_the_date('', $p)); ?> · <?php echo esc_html(sprintf(__('%d min', 'lemag'), lemag_reading_time($p->ID))); ?></span>
                </div>
              </article>
            <?php endforeach; wp_reset_postdata(); ?>
          </div>
        </section>
      <?php endif; ?>
      <section class="lemag-author-box">
        <div class="lemag-author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 80); ?></div>
        <div class="lemag-author-box-info">
          <span class="lemag-author-box-label"><?php esc_html_e('Écrit par', 'lemag'); ?></span>
          <h3 class="lemag-author-box-name"><?php the_author_posts_link(); ?></h3>
          <?php $author_bio = get_the_author_meta('description'); ?>
          <?php if ($author_bio): ?>
            <p class="lemag-author-box-bio"><?php echo esc_html($author_bio); ?></p>
          <?php endif; ?>
        </div>
      </section>
      <?php
      $prev = get_previous_post();
      $next = get_next_post();
      if ($prev || $next): ?>
        <nav class="post-nav">
          <?php if ($prev): ?>
            <a href="<?php echo get_permalink($prev); ?>" class="post-nav-link">
              <span class="post-nav-arrow">←</span>
              <span class="post-nav-body">
                <span class="post-nav-label"><?php esc_html_e('Article précédent', 'lemag'); ?></span>
                <span class="post-nav-title"><?php echo get_the_title($prev); ?></span>
              </span>
            </a>
          <?php else: ?>
            <span class="post-nav-empty"></span>
          <?php endif; ?>
          <?php if ($next): ?>
            <a href="<?php echo get_permalink($next); ?>" class="post-nav-link post-nav-next">
              <span class="post-nav-body">
                <span class="post-nav-label"><?php esc_html_e('Article suivant', 'lemag'); ?></span>
                <span class="post-nav-title"><?php echo get_the_title($next); ?></span>
              </span>
              <span class="post-nav-arrow">→</span>
            </a>
          <?php else: ?>
            <span class="post-nav-empty"></span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
      <?php if (comments_open() || get_comments_number()) comments_template(); ?>
    <?php endwhile; ?>
  </article>
  <aside class="sidebar lemag-sticky-sidebar">
    <div class="sidebar-inner">
      <?php if (is_active_sidebar('sidebar-1')): ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
      <?php else: ?>
        <div class="sidebar-box">
          <h3><?php esc_html_e('Derniers articles', 'lemag'); ?></h3>
          <ul>
            <?php
            $recent = new WP_Query(['posts_per_page' => 5]);
            while ($recent->have_posts()): $recent->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; wp_reset_postdata(); ?>
          </ul>
        </div>
        <div class="sidebar-box">
          <h3><?php esc_html_e('Tendances', 'lemag'); ?></h3>
          <ol class="lemag-sidebar-trending">
            <?php $n = 1; foreach (lemag_trending_posts(5) as $p): ?>
              <li><span class="num"><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></span><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></li>
            <?php endforeach; ?>
          </ol>
        </div>
        <div class="sidebar-box lemag-newsletter-box">
          <h3><?php esc_html_e('Newsletter', 'lemag'); ?></h3>
          <p><?php esc_html_e('Recevez nos meilleurs articles chaque semaine.', 'lemag'); ?></p>
          <form class="lemag-newsletter" onsubmit="return false;">
            <input type="email" placeholder="<?php esc_attr_e('Votre email', 'lemag'); ?>">
            <button type="submit"><?php esc_html_e('S\'abonner', 'lemag'); ?></button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </aside>
</div>
<?php get_footer(); ?>
