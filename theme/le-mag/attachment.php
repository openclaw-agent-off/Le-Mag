<?php get_header(); ?>
<main class="container lemag-attachment">
  <?php echo lemag_breadcrumb(); ?>
  <article class="article-content">
    <h1><?php the_title(); ?></h1>
    <?php if (wp_attachment_is_image()): ?>
      <figure class="post-thumbnail">
        <?php echo wp_get_attachment_image(get_the_ID(), 'large'); ?>
      </figure>
      <?php if (has_excerpt()): ?>
        <figcaption class="wp-caption-text"><?php the_excerpt(); ?></figcaption>
      <?php endif; ?>
    <?php else: ?>
      <p><?php echo wp_get_attachment_link(get_the_ID(), 'large'); ?></p>
    <?php endif; ?>
  </article>
</main>
<?php get_footer(); ?>
