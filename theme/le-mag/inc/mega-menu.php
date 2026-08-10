<?php
/**
 * Mega Menu — 3 modèles prédéfinis : Articles récents, Sous-catégories, Aucun.
 */
defined('ABSPATH') || exit;

class LeMag_Mega_Walker extends Walker_Nav_Menu {

    private $megaItemID = 0;
    private $columnCount = 0;

    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul class=\"sub-menu depth_$depth\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($this->megaItemID && $depth === 0 && $item->menu_item_parent != $this->megaItemID) {
            $this->megaItemID = 0;
        }

        $model = get_post_meta($item->ID, '_lemag_mega_model', true);
        $is_cat = ($item->object === 'category' && !empty($item->object_id));

        if ($depth === 0 && $model && $model !== 'none') {
            $item->classes[] = 'mega-menu';
            $this->megaItemID = $item->ID;
            $this->columnCount = 0;
        }

        if ($this->megaItemID && $depth === 1) {
            if ($this->columnCount > 0) $output .= "</ul></li>";
            $this->columnCount++;
            $output .= "<li class=\"mega-col\"><ul>\n";
        }

        parent::start_el($output, $item, $depth, $args, $id);
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        if ($this->megaItemID && $depth === 1) {
            $output .= "</ul></li>";
        }
        $output .= "</ul>\n";
        if ($depth === 0) {
            $this->megaItemID = 0;
            $this->columnCount = 0;
        }
    }
}

// === Panneau mega menu : rendu selon le modèle ===
add_filter('walker_nav_menu_start_el', function ($output, $item, $depth, $args) {
    $args = (array) $args;
    if ($depth !== 0) return $output;
    if (!empty($args['hide_mega_menu'])) return $output;

    $model = get_post_meta($item->ID, '_lemag_mega_model', true);
    if (!$model || $model === 'none') return $output;

    // Modèle 1 : Articles récents (auto par catégorie)
    if ($model === 'posts') {
        if ($item->object !== 'category' || empty($item->object_id)) return $output;
        $posts = get_posts([
            'cat'            => $item->object_id,
            'posts_per_page' => 4,
            'ignore_sticky_posts' => 1,
        ]);
        if (empty($posts)) return $output;

        if (strpos($output, 'mega-menu') === false) {
            $output = str_replace('menu-item', 'menu-item mega-menu', $output);
        }
        $cat_name = esc_html($item->title);
        $cat_link = esc_url(get_category_link($item->object_id));
        ob_start(); ?>
        <div class="mega-menu-panel mega-menu-posts">
            <div class="mega-posts-grid">
                <?php foreach ($posts as $p): $img = get_the_post_thumbnail_url($p, 'medium') ?: ''; ?>
                    <a href="<?php echo esc_url(get_permalink($p)); ?>" class="mega-post-card">
                        <?php if ($img): ?>
                            <div class="mega-post-img" style="background-image:url(<?php echo esc_url($img); ?>)"></div>
                        <?php endif; ?>
                        <div class="mega-post-body">
                            <span class="mega-post-title"><?php echo esc_html(get_the_title($p)); ?></span>
                            <span class="mega-post-date"><?php echo get_the_date('', $p); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo $cat_link; ?>" class="mega-posts-more"><?php echo esc_html(sprintf(__('Tout %s →', 'lemag'), $cat_name)); ?></a>
        </div>
        <?php
        return $output . ob_get_clean();
    }

    // Modèle 2 : Sous-catégories en colonnes (rendu automatique via le Walker)
    if ($model === 'categories') {
        if ($item->object !== 'category' || empty($item->object_id)) return $output;
        $children = get_terms([
            'taxonomy'   => 'category',
            'parent'     => $item->object_id,
            'hide_empty' => false,
            'number'     => 6,
        ]);
        if (empty($children) || is_wp_error($children)) return $output;

        if (strpos($output, 'mega-menu') === false) {
            $output = str_replace('menu-item', 'menu-item mega-menu', $output);
        }
        ob_start(); ?>
        <div class="mega-menu-panel mega-menu-categories">
            <div class="mega-cats-grid">
                <?php foreach ($children as $child): ?>
                    <div class="mega-cat-col">
                        <a href="<?php echo esc_url(get_category_link($child)); ?>" class="mega-cat-title"><?php echo esc_html($child->name); ?></a>
                        <span class="mega-cat-count"><?php echo esc_html(sprintf(_n('%d article', '%d articles', $child->count, 'lemag'), $child->count)); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return $output . ob_get_clean();
    }

    return $output;
}, 100, 4);