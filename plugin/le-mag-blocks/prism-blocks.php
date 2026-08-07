<?php
/**
 * Plugin Name: Prism Blocks
 * Description: Blocs dynamiques pour le thème Prism — Hero, Grille, Cartes.
 * Version: 1.1.0
 * Author: Pressly
 * Text Domain: prism-blocks
 */

defined('ABSPATH') || exit;

define('PRISM_BLOCKS_VERSION', '1.1.0');

add_action('init', function () {

    // === POST HERO ===
    register_block_type('prism/post-hero', [
        'render_callback' => function ($attrs) {
            $posts_per_page = intval($attrs['postsPerPage'] ?? 1);
            $offset = intval($attrs['offset'] ?? 0);
            $posts_per_page = min(20, max(1, $posts_per_page));
            $offset = max(0, $offset);
            $category = intval($attrs['category'] ?? 0);
            $args = ['posts_per_page' => $posts_per_page, 'offset' => $offset];
            if ($category > 0) $args['cat'] = $category;

            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';

            ob_start();
            echo '<div class="prism-post-hero">';
            while ($query->have_posts()): $query->the_post();
                $img = get_the_post_thumbnail_url(null, 'large') ?: '';
                $cats = get_the_category_list(', ');
                ?>
                <div class="prism-hero-item" style="background-image:url(<?php echo esc_url($img); ?>)">
                    <div class="prism-hero-overlay"></div>
                    <div class="prism-hero-content">
                        <span class="prism-hero-cat"><?php echo wp_kses_post($cats); ?></span>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                        <span class="prism-hero-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
            return ob_get_clean();
        },
        'attributes' => [
            'postsPerPage' => ['type' => 'number', 'default' => 1],
            'offset' => ['type' => 'number', 'default' => 0],
            'category' => ['type' => 'number', 'default' => 0],
        ],
    ]);

    // === POST GRID ===
    register_block_type('prism/post-grid', [
        'render_callback' => function ($attrs) {
            $per_page = $attrs['postsPerPage'] ?? 6;
            $offset = $attrs['offset'] ?? 0;
            $columns = $attrs['columns'] ?? 3;
            $category = $attrs['category'] ?? 0;

            $per_page = min(20, max(1, intval($per_page)));
            $offset = max(0, intval($offset));
            $columns = min(6, max(1, intval($columns)));

            $args = ['posts_per_page' => $per_page, 'offset' => $offset];
            if ($category > 0) $args['cat'] = $category;

            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';

            ob_start();
            echo '<div class="prism-post-grid" style="--columns:' . intval($columns) . '">';
            while ($query->have_posts()): $query->the_post(); ?>
                <article class="prism-card">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                    <?php endif; ?>
                    <div class="prism-card-body">
                        <span class="prism-card-cat"><?php the_category(', '); ?></span>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                        <span class="prism-card-date"><?php echo get_the_date(); ?></span>
                    </div>
                </article>
            <?php endwhile;
            echo '</div>';
            wp_reset_postdata();
            return ob_get_clean();
        },
        'attributes' => [
            'postsPerPage' => ['type' => 'number', 'default' => 6],
            'offset' => ['type' => 'number', 'default' => 0],
            'columns' => ['type' => 'number', 'default' => 3],
            'category' => ['type' => 'number', 'default' => 0],
        ],
    ]);

    // === POST CARD (single) ===
    register_block_type('prism/post-card', [
        'render_callback' => function ($attrs) {
            $post_id = absint($attrs['postId'] ?? 0);
            if (!$post_id) return '';
            $post = get_post($post_id);
            if (!$post) return '';
            if (!is_post_publicly_viewable($post)) return '';
            setup_postdata($post);

            ob_start(); ?>
            <article class="prism-card">
                <?php if (has_post_thumbnail()): ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                <?php endif; ?>
                <div class="prism-card-body">
                    <span class="prism-card-cat"><?php the_category(', '); ?></span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                    <span class="prism-card-date"><?php echo get_the_date(); ?></span>
                </div>
            </article>
            <?php
            wp_reset_postdata();
            return ob_get_clean();
        },
        'attributes' => [
            'postId' => ['type' => 'number', 'default' => 0],
        ],
    ]);

    // === CATEGORY SECTION ===
    register_block_type('prism/category-section', [
        'render_callback' => function ($attrs) {
            $category = intval($attrs['category'] ?? 0);
            $title = $attrs['title'] ?? '';
            $accent = $attrs['accentColor'] ?? 'var(--prism-red)';
            if (!preg_match('/^(#[0-9a-fA-F]{3,8}|var\\(--[a-z0-9-]+\\))$/', $accent)) {
                $accent = 'var(--prism-red)';
            }
            $posts_per_page = min(20, max(1, intval($attrs['postsPerPage'] ?? 5)));

            $args = ['posts_per_page' => $posts_per_page];
            if ($category > 0) $args['cat'] = $category;

            $cat = $category ? get_category($category) : null;
            $title = $title ?: ($cat ? $cat->name : __('Articles', 'prism-blocks'));
            $link = $cat ? get_category_link($cat) : '#';

            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';
            ob_start(); ?>
            <div class="prism-cat-section" style="--accent:<?php echo esc_attr($accent); ?>">
                <div class="prism-cat-header">
                    <h4 class="prism-cat-title"><?php echo esc_html($title); ?></h4>
                    <a href="<?php echo esc_url($link); ?>" class="prism-cat-more">Plus
                        <svg aria-hidden="true" width="12" height="12" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"/></svg>
                    </a>
                </div>
                <div class="prism-cat-grid">
                    <?php $i = 0; while ($query->have_posts()): $query->the_post(); $i++; ?>
                        <?php if ($i === 1): ?>
                            <article class="prism-cat-featured prism-card">
                                <?php the_post_thumbnail('medium_large'); ?>
                                <div class="prism-card-body">
                                    <span class="prism-card-cat"><?php the_category(', '); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                    <span class="prism-card-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </article>
                        <?php else: ?>
                            <article class="prism-cat-mini">
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>" class="prism-cat-thumb"><?php the_post_thumbnail('thumbnail'); ?></a>
                                <?php endif; ?>
                                <div class="prism-cat-mini-text">
                                    <span class="prism-card-cat"><?php the_category(', '); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="prism-card-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            return ob_get_clean();
        },
        'attributes' => [
            'category'    => ['type' => 'number', 'default' => 0],
            'title'       => ['type' => 'string', 'default' => ''],
            'accentColor' => ['type' => 'string', 'default' => ''],
            'postsPerPage'=> ['type' => 'number', 'default' => 5],
        ],
    ]);

    // === FEATURED POSTS ===
    register_block_type('prism/featured-posts', [
        'render_callback' => function ($attrs) {
            $title = $attrs['title'] ?? __('À la une', 'prism-blocks');
            $per_page = min(20, max(1, intval($attrs['postsPerPage'] ?? 5)));
            $category = $attrs['category'] ?? 0;
            $args = ['posts_per_page' => $per_page];
            if ($category > 0) $args['cat'] = $category;
            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';
            ob_start(); ?>
            <div class="prism-featured">
                <div class="prism-featured-header">
                    <h2 class="prism-featured-title"><?php echo esc_html($title); ?></h2>
                </div>
                <div class="prism-featured-grid">
                    <?php $i = 0; while ($query->have_posts()): $query->the_post(); $i++; ?>
                        <article class="prism-featured-item">
                            <div class="prism-featured-number"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></div>
                            <?php if (has_post_thumbnail()): ?>
                                <a href="<?php the_permalink(); ?>" class="prism-featured-img"><?php the_post_thumbnail('medium'); ?></a>
                            <?php endif; ?>
                            <div class="prism-featured-text">
                                <span class="prism-card-cat"><?php the_category(', '); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                <span class="prism-card-date"><?php echo get_the_date(); ?> · <?php echo get_the_author(); ?></span>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php wp_reset_postdata();
            return ob_get_clean();
        },
        'attributes' => [
            'title'       => ['type' => 'string', 'default' => ''],
            'postsPerPage'=> ['type' => 'number', 'default' => 5],
            'category'    => ['type' => 'number', 'default' => 0],
        ],
    ]);

});

// CSS front-end
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('prism-blocks', plugin_dir_url(__FILE__) . 'blocks.css', [], PRISM_BLOCKS_VERSION);
});

// JS editor
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('prism-blocks-editor',
        plugin_dir_url(__FILE__) . 'build/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        PRISM_BLOCKS_VERSION, true
    );
    wp_enqueue_style('prism-blocks-editor',
        plugin_dir_url(__FILE__) . 'blocks.css', [], PRISM_BLOCKS_VERSION
    );
    wp_add_inline_style('prism-blocks-editor', '.prism-block-preview{background:#f0f0f0;border:2px dashed #ccc;padding:16px;border-radius:8px;text-align:center;font-size:14px}');
});

// Catégorie de blocs
add_filter('block_categories_all', function ($cats) {
    return array_merge([['slug' => 'prism', 'title' => 'Prism']], $cats);
});

// Site Kits (import 1 clic)
require_once plugin_dir_path(__FILE__) . 'inc/kits.php';
require_once plugin_dir_path(__FILE__) . 'inc/modules.php';
