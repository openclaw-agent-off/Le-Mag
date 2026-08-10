<?php get_header(); ?>
<main class="container">
  <?php echo lemag_breadcrumb(); ?>
  <?php
  $author = get_queried_object();
  $author_id = $author->ID;
  $social = ['twitter' => get_the_author_meta('twitter', $author_id), 'facebook' => get_the_author_meta('facebook', $author_id), 'instagram' => get_the_author_meta('instagram', $author_id), 'linkedin' => get_the_author_meta('linkedin', $author_id)];
  $social = array_filter($social);
  $post_count = count_user_posts($author_id, 'post');
  ?>
  <header class="lemag-author-header">
    <div class="lemag-author-avatar"><?php echo get_avatar($author_id, 120); ?></div>
    <div class="lemag-author-info">
      <h1 class="lemag-author-name"><?php echo esc_html($author->display_name); ?></h1>
      <?php if ($author->description): ?>
        <p class="lemag-author-bio"><?php echo esc_html($author->description); ?></p>
      <?php endif; ?>
      <p class="lemag-author-stats"><?php echo esc_html(sprintf(_n('%d article publié', '%d articles publiés', $post_count, 'lemag'), $post_count)); ?></p>
      <?php if ($social): ?>
        <div class="lemag-author-social">
          <?php foreach ($social as $network => $handle): ?>
            <a href="<?php echo esc_url($handle); ?>" target="_blank" rel="noopener noreferrer" class="lemag-author-social-<?php echo esc_attr($network); ?>"><?php echo esc_html(ucfirst($network)); ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </header>
  <h2 class="section-title"><?php esc_html_e('Articles de cet auteur', 'lemag'); ?></h2>
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
    <p><?php esc_html_e('Aucun article pour cet auteur.', 'lemag'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
