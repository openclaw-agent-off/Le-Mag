<?php get_header(); ?>
<main class="container lemag-404">
  <?php echo lemag_breadcrumb(); ?>
  <div class="lemag-404-content">
    <span class="lemag-404-code">404</span>
    <h1><?php esc_html_e('Page introuvable', 'lemag'); ?></h1>
    <p><?php esc_html_e('La page que vous recherchez n\'existe plus, a été déplacée, ou n\'a jamais existé.', 'lemag'); ?></p>
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="lemag-404-search">
      <input type="search" name="s" placeholder="<?php esc_attr_e('Rechercher un article...', 'lemag'); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
      <button type="submit"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></button>
    </form>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="lemag-404-back"><?php esc_html_e('← Retour à l\'accueil', 'lemag'); ?></a>
  </div>
  <section class="lemag-404-popular">
    <h2 class="section-title"><?php esc_html_e('Articles populaires', 'lemag'); ?></h2>
    <div class="grid">
      <?php foreach (lemag_trending_posts(3) as $p): setup_postdata($p); ?>
        <article <?php post_class('card'); ?>>
          <?php if (has_post_thumbnail($p)): ?>
            <a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo get_the_post_thumbnail($p, 'medium_large'); ?></a>
          <?php endif; ?>
          <div class="card-body">
            <span class="card-cat"><?php echo get_the_category_list(', ', '', $p); ?></span>
            <h3><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h3>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt($p), 15)); ?></p>
            <div class="card-footer"><?php echo esc_html(get_the_date('', $p)); ?></div>
          </div>
        </article>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
