<?php
/**
 * Plugin Name: Le Mag Blocks
 * Description: Blocs dynamiques pour le thème Le Mag — Hero, Grille, Cartes.
 * Version: 1.8.1
 * Author: Skills Vault
 * Text Domain: lemag-blocks
 */

defined('ABSPATH') || exit;

define('LEMAG_BLOCKS_VERSION', '1.8.1');

add_action('init', function () {

    // === POST HERO ===
    register_block_type('lemag/post-hero', [
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
            echo '<div class="lemag-post-hero">';
            while ($query->have_posts()): $query->the_post();
                $img = get_the_post_thumbnail_url(null, 'large') ?: '';
                $cats = get_the_category_list(', ');
                ?>
                <div class="lemag-hero-item" style="background-image:url(<?php echo esc_url($img); ?>)">
                    <div class="lemag-hero-overlay"></div>
                    <div class="lemag-hero-content">
                        <span class="lemag-hero-cat"><?php echo wp_kses_post($cats); ?></span>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                        <span class="lemag-hero-date"><?php echo get_the_date(); ?></span>
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
    register_block_type('lemag/post-grid', [
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
            echo '<div class="lemag-post-grid" style="--columns:' . intval($columns) . '">';
            while ($query->have_posts()): $query->the_post(); ?>
                <article class="lemag-card">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                    <?php endif; ?>
                    <div class="lemag-card-body">
                        <span class="lemag-card-cat"><?php the_category(', '); ?></span>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                        <span class="lemag-card-date"><?php echo get_the_date(); ?></span>
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
    register_block_type('lemag/post-card', [
        'render_callback' => function ($attrs) {
            $post_id = absint($attrs['postId'] ?? 0);
            if (!$post_id) return '';
            $post = get_post($post_id);
            if (!$post) return '';
            // is_post_publicly_viewable() existe depuis WP 5.7 ; fallback sur post_status + post_type.
            if (function_exists('is_post_publicly_viewable')) {
                if (!is_post_publicly_viewable($post)) return '';
            } elseif ($post->post_status !== 'publish' || !get_post_type_object($post->post_type)->public) {
                return '';
            }
            setup_postdata($post);

            ob_start(); ?>
            <article class="lemag-card">
                <?php if (has_post_thumbnail()): ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                <?php endif; ?>
                <div class="lemag-card-body">
                    <span class="lemag-card-cat"><?php the_category(', '); ?></span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                    <span class="lemag-card-date"><?php echo get_the_date(); ?></span>
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
    register_block_type('lemag/category-section', [
        'render_callback' => function ($attrs) {
            $category = intval($attrs['category'] ?? 0);
            $title = $attrs['title'] ?? '';
            $accent = $attrs['accentColor'] ?? 'var(--lemag-red)';
            if (!preg_match('/^(#[0-9a-fA-F]{3,8}|var\\(--[a-z0-9-]+\\))$/', $accent)) {
                $accent = 'var(--lemag-red)';
            }
            $posts_per_page = min(20, max(1, intval($attrs['postsPerPage'] ?? 5)));

            $args = ['posts_per_page' => $posts_per_page];
            if ($category > 0) $args['cat'] = $category;

            $cat = $category ? get_category($category) : null;
            $title = $title ?: ($cat ? $cat->name : __('Articles', 'lemag-blocks'));
            $link = $cat ? get_category_link($cat) : '#';

            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';
            ob_start(); ?>
            <div class="lemag-cat-section" style="--accent:<?php echo esc_attr($accent); ?>">
                <div class="lemag-cat-header">
                    <h4 class="lemag-cat-title"><?php echo esc_html($title); ?></h4>
                    <a href="<?php echo esc_url($link); ?>" class="lemag-cat-more">Plus
                        <svg aria-hidden="true" width="12" height="12" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"/></svg>
                    </a>
                </div>
                <div class="lemag-cat-grid">
                    <?php $i = 0; while ($query->have_posts()): $query->the_post(); $i++; ?>
                        <?php if ($i === 1): ?>
                            <article class="lemag-cat-featured lemag-card">
                                <?php the_post_thumbnail('medium_large'); ?>
                                <div class="lemag-card-body">
                                    <span class="lemag-card-cat"><?php the_category(', '); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                    <span class="lemag-card-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </article>
                        <?php else: ?>
                            <article class="lemag-cat-mini">
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>" class="lemag-cat-thumb"><?php the_post_thumbnail('thumbnail'); ?></a>
                                <?php endif; ?>
                                <div class="lemag-cat-mini-text">
                                    <span class="lemag-card-cat"><?php the_category(', '); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="lemag-card-date"><?php echo get_the_date(); ?></span>
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
    register_block_type('lemag/featured-posts', [
        'render_callback' => function ($attrs) {
            $title = $attrs['title'] ?? __('À la une', 'lemag-blocks');
            $per_page = min(20, max(1, intval($attrs['postsPerPage'] ?? 5)));
            $category = $attrs['category'] ?? 0;
            $args = ['posts_per_page' => $per_page];
            if ($category > 0) $args['cat'] = $category;
            $query = new WP_Query($args);
            if (!$query->have_posts()) return '';
            ob_start(); ?>
            <div class="lemag-featured">
                <div class="lemag-featured-header">
                    <h2 class="lemag-featured-title"><?php echo esc_html($title); ?></h2>
                </div>
                <div class="lemag-featured-grid">
                    <?php $i = 0; while ($query->have_posts()): $query->the_post(); $i++; ?>
                        <article class="lemag-featured-item">
                            <div class="lemag-featured-number"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></div>
                            <?php if (has_post_thumbnail()): ?>
                                <a href="<?php the_permalink(); ?>" class="lemag-featured-img"><?php the_post_thumbnail('medium'); ?></a>
                            <?php endif; ?>
                            <div class="lemag-featured-text">
                                <span class="lemag-card-cat"><?php the_category(', '); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                <span class="lemag-card-date"><?php echo get_the_date(); ?> · <?php echo get_the_author(); ?></span>
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

    // === MAGAZINE HEADLINE ===
    register_block_type('lemag/magazine-headline', [
        'render_callback' => function () {
            $hero = new WP_Query(['posts_per_page' => 1]);
            if (!$hero->have_posts()) return '';
            ob_start(); ?>
            <div class="lemag-magazine-headline">
                <div class="lemag-mh-hero">
                    <?php $hero->the_post(); $img = get_the_post_thumbnail_url(null, 'medium_large') ?: ''; $cats = get_the_category_list(' '); ?>
                    <div class="lemag-mh-hero-bg" style="background-image:url(<?php echo esc_url($img); ?>)"></div>
                    <div class="lemag-mh-hero-overlay"></div>
                    <div class="lemag-mh-hero-content">
                        <span class="lemag-mh-cat"><?php echo wp_kses_post($cats); ?></span>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="lemag-mh-meta"><span><?php echo get_the_date(); ?></span><span><?php echo esc_html__('par', 'lemag-blocks') . ' ' . get_the_author(); ?></span></div>
                    </div>
                </div>
                <div class="lemag-mh-grid">
                    <?php wp_reset_postdata(); $grid = new WP_Query(['posts_per_page' => 4, 'offset' => 1]); $i = 0;
                    while ($grid->have_posts()): $grid->the_post(); $i++; $img = get_the_post_thumbnail_url(null, 'medium') ?: ''; $cats = get_the_category_list(' '); ?>
                        <div class="lemag-mh-item">
                            <div class="lemag-mh-item-bg" style="background-image:url(<?php echo esc_url($img); ?>)"></div>
                            <div class="lemag-mh-item-overlay"></div>
                            <div class="lemag-mh-item-content">
                                <span class="lemag-mh-cat"><?php echo wp_kses_post($cats); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            <?php return ob_get_clean();
        },
    ]);

    // === POPULAR LIST ===
    register_block_type('lemag/popular-list', [
        'render_callback' => function ($attrs) {
            $per_page = min(20, max(1, intval($attrs['postsPerPage'] ?? 9)));
            $query = new WP_Query(['posts_per_page' => $per_page, 'ignore_sticky_posts' => 1]);
            if (!$query->have_posts()) return '';
            ob_start(); ?>
            <ol class="lemag-popular-list">
                <?php $n = 1; while ($query->have_posts()): $query->the_post(); ?>
                    <li class="lemag-popular-item">
                        <span class="lemag-popular-num"><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></span>
                        <div class="lemag-popular-body">
                            <span class="lemag-popular-cat"><?php the_category(', '); ?></span>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <span class="lemag-popular-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ol>
            <?php return ob_get_clean();
        },
        'attributes' => [
            'postsPerPage' => ['type' => 'number', 'default' => 9],
        ],
    ]);

});

// === Front-end : styles des blocs (source unique, découplée du thème) ===
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('lemag-blocks-front',
        plugin_dir_url(__FILE__) . 'blocks.css',
        [], LEMAG_BLOCKS_VERSION
    );
});

// JS editor
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('lemag-blocks-editor',
        plugin_dir_url(__FILE__) . 'build/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n'],
        filemtime(plugin_dir_path(__FILE__) . 'build/blocks.js'), true
    );
    wp_enqueue_style('lemag-blocks-editor',
        plugin_dir_url(__FILE__) . 'blocks.css', [], LEMAG_BLOCKS_VERSION
    );
    wp_add_inline_style('lemag-blocks-editor', '.lemag-block-editor-render{pointer-events:none}.lemag-block-preview{background:#f0f0f0;border:2px dashed #ccc;padding:16px;border-radius:8px;text-align:center;font-size:14px}');
});

// Catégorie de blocs
add_filter('block_categories_all', function ($cats) {
    return array_merge([['slug' => 'lemag', 'title' => 'Le Mag']], $cats);
});

// === Compatibilité rétroactive : alias prism/* → lemag/* ===
// Les anciens blocs prism/* du contenu existant sont reconnus et redirigés
// vers les nouveaux blocs lemag/* (mêmes render_callback, mêmes attributs).
add_action('init', function (): void {
    $aliases = [
        'prism/post-hero'         => 'lemag/post-hero',
        'prism/post-grid'         => 'lemag/post-grid',
        'prism/post-card'         => 'lemag/post-card',
        'prism/category-section'  => 'lemag/category-section',
        'prism/featured-posts'    => 'lemag/featured-posts',
        'prism/magazine-headline' => 'lemag/magazine-headline',
        'prism/popular-list'      => 'lemag/popular-list',
    ];
    foreach ($aliases as $old => $new) {
        $type = WP_Block_Type_Registry::get_instance()->get_registered($new);
        if ($type) {
            register_block_type($old, [
                'render_callback' => $type->render_callback,
                'attributes'       => $type->attributes,
                'editor_script'   => $type->editor_script,
                'editor_style'     => $type->editor_style,
                'style'            => $type->style,
                'category'         => 'lemag',
            ]);
        }
    }
}, 20);

// Au chargement du contenu : convertit les anciens blocs prism/* en lemag/* en base.
add_filter('the_content', function (string $content): string {
    if (strpos($content, 'wp:prism/') === false) return $content;
    $content = str_replace('wp:prism/', 'wp:lemag/', $content);
    $content = str_replace('<!-- /wp:prism/', '<!-- /wp:lemag/', $content);
    return $content;
}, 1);

// Site Kits (import 1 clic) + Modules + Mega Menu
require_once plugin_dir_path(__FILE__) . 'inc/kits.php';
require_once plugin_dir_path(__FILE__) . 'inc/modules.php';
require_once plugin_dir_path(__FILE__) . 'inc/mega-menu-admin.php';
require_once plugin_dir_path(__FILE__) . 'inc/header-footer.php';
