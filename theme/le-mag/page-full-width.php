<?php
/**
 * Template Name: Page pleine largeur
 * Description: Affiche la page sans sidebar, en pleine largeur.
 */
get_header();
?>
<main class="container lemag-page-fullwidth">
  <?php echo lemag_breadcrumb(); ?>
  <?php while (have_posts()): the_post(); ?>
    <article class="article-content">
      <h1><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()): ?>
        <figure class="post-thumbnail"><?php the_post_thumbnail('full'); ?></figure>
      <?php endif; ?>
      <div class="entry-content"><?php the_content(); ?></div>
      <?php wp_link_pages(['before' => '<div class="page-links">' . __('Pages :', 'lemag'), 'after' => '</div>']); ?>
    </article>
    <?php if (comments_open() || get_comments_number()) comments_template(); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
