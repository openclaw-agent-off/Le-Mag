<?php
/**
 * Le Mag — Classic Theme
 *
 * @package LeMag
 * @copyright 2026 Skills Vault
 */
defined('ABSPATH') || exit;

define('LEMAG_VERSION', '1.8.1');

// CSS + Fonts + Menu JS
add_action('wp_enqueue_scripts', function (): void {
    $uri = get_template_directory_uri();
    $ver = LEMAG_VERSION;

    wp_enqueue_style('lemag-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap',
        [], null);

    // FIX: on enquee aussi style.css (en-tête du thème) pour conformité WordPress + diagnostics.
    wp_enqueue_style('lemag-style',
        "$uri/style.css",
        [], $ver);

    wp_enqueue_style('lemag-main',
        "$uri/assets/css/main.css",
        ['lemag-fonts', 'lemag-style'], $ver);

    wp_enqueue_script('lemag-menu',
        "$uri/assets/js/menu.js",
        [], $ver, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
});

// Theme supports
add_action('after_setup_theme', function (): void {
    load_theme_textdomain('lemag', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('custom-logo', ['height' => 80, 'width' => 320, 'flex-height' => true, 'flex-width' => true]);
    add_theme_support('custom-header', ['default-image' => '', 'width' => 1920, 'height' => 400, 'flex-height' => true]);
    add_theme_support('custom-background', ['default-color' => 'ffffff']);

    add_editor_style('assets/css/main.css');

    register_nav_menus([
        'primary' => __('Menu principal', 'lemag'),
        'secondary' => __('Secondary Nav', 'lemag'),
    ]);
});

// Sidebar
add_action('widgets_init', function (): void {
    register_sidebar([
        'name'          => __('Sidebar', 'lemag'),
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="sidebar-box">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => __('Sidebar Footer', 'lemag'),
        'id'            => 'sidebar-footer',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);
});

// Données structurées JSON-LD pour SEO
add_action('wp_head', function (): void {
    echo lemag_json_ld();
}, 1);

// Support des formats de post natifs (compatibilité avec lemag_format taxonomy)
add_action('after_setup_theme', function (): void {
    add_theme_support('post-formats', ['video', 'gallery', 'audio', 'standard']);
}, 11);

// Patterns
add_action('init', function (): void {
    if (!function_exists('register_block_pattern_category')) return;
    register_block_pattern_category('lemag-magazine', [
        'label' => __('Le Mag — Kits', 'lemag'),
    ]);
    if (function_exists('register_block_style')) {
        register_block_style('core/post-title', ['name' => 'hero', 'label' => __('Hero', 'lemag')]);
        register_block_style('core/query', ['name' => 'grid', 'label' => __('Grid', 'lemag')]);
    }
});

// Mega Menu
require_once get_template_directory() . '/inc/mega-menu.php';

// Customizer
require_once get_template_directory() . '/inc/customizer.php';

// Helpers magazine (breadcrumb, related, lecture time, share, trending)
require_once get_template_directory() . '/inc/helpers.php';

// Taxonomies personnalisées (Série, Format) + template loader
require_once get_template_directory() . '/inc/taxonomies.php';
