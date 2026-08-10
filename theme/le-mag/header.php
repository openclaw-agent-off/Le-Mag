<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="reading-progress"><div class="reading-progress-bar"></div></div>
  <div class="header-inner">
    <div class="site-logo">
      <?php if (has_custom_logo()): ?>
        <?php the_custom_logo(); ?>
      <?php else: ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-link"><?php bloginfo('name'); ?></a>
      <?php endif; ?>
    </div>
    <button class="menu-toggle" aria-label="Menu" aria-expanded="false">
      <span class="menu-toggle-bar"></span><span class="menu-toggle-bar"></span><span class="menu-toggle-bar"></span>
    </button>
    <nav class="main-nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'menu_class'     => 'main-nav-list',
        'container'      => false,
        'fallback_cb'    => function () {
          echo '<ul class="main-nav-list">';
          wp_list_categories(['title_li' => false, 'depth' => 1]);
          echo '</ul>';
        },
        'depth'          => 3,
        'walker'         => new LeMag_Mega_Walker(),
      ]);
      ?>
    </nav>
  </div>
  <?php $lemag_hf = function_exists('lemag_hf_settings') ? lemag_hf_settings() : []; ?>
  <?php if (!empty($lemag_hf['secondary_nav'])): ?>
    <nav class="secondary-nav" aria-label="<?php esc_attr_e('Navigation secondaire', 'lemag'); ?>">
      <?php wp_nav_menu(['theme_location' => 'secondary', 'menu_class' => 'secondary-nav-list', 'container' => false, 'fallback_cb' => false]); ?>
    </nav>
  <?php endif; ?>
</header>

<div id="content" class="site-content">
