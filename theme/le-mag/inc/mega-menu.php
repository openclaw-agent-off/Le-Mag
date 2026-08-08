<?php
/**
 * Mega Menu — automatique par catégorie + CPT manuel.
 */
defined('ABSPATH') || exit;

class Prism_Mega_Walker extends Walker_Nav_Menu {

    private $megaMenuID = 0;
    private $columnCount = 0;

    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul class=\"sub-menu depth_$depth\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($this->megaMenuID && $depth === 0 && $item->menu_item_parent != $this->megaMenuID) {
            $this->megaMenuID = 0;
        }

        // Mega menu auto : catégorie avec enfants
        $is_cat = ($item->object === 'category' && !empty($item->object_id));
        $has_mega_cpt = get_post_meta($item->ID, '_lemag_mega_menu_id', true);

        if ($depth === 0 && ($is_cat || !empty($has_mega_cpt))) {
            $item->classes[] = 'mega-menu';
            $this->megaMenuID = $item->ID;
            $this->columnCount = 0;
        }

        if ($this->megaMenuID && $depth === 1) {
            if ($this->columnCount > 0) $output .= "</ul></li>";
            $this->columnCount++;
            $output .= "<li class=\"mega-col\"><ul>\n";
        }

        parent::start_el($output, $item, $depth, $args, $id);
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        if ($this->megaMenuID && $depth === 1) {
            $output .= "</ul></li>";
        }
        $output .= "</ul>\n";
        if ($depth === 0) {
            $this->megaMenuID = 0;
            $this->columnCount = 0;
        }
    }
}

// === Panneau mega menu : articles récents de la catégorie ===
add_filter('walker_nav_menu_start_el', function ($output, $item, $depth, $args) {
    $args = (array) $args;
    if ($depth !== 0) return $output;
    if (!empty($args['hide_mega_menu'])) return $output;

    // Priorité 1 : CPT mega menu manuel
    $mega_id = get_post_meta($item->ID, '_lemag_mega_menu_id', true);
    if (!empty($mega_id)) {
        $mega = get_post($mega_id);
        if ($mega && !is_wp_error($mega)) {
            $content = apply_filters('the_content', $mega->post_content);
            if (!empty(trim(strip_tags($content)))) {
                if (strpos($output, 'mega-menu') === false) {
                    $output = str_replace('menu-item', 'menu-item mega-menu', $output);
                }
                $output .= '<div class="mega-menu-panel">' . $content . '</div>';
                return $output;
            }
        }
    }

    // Priorité 2 : automatique par catégorie
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
        <a href="<?php echo $cat_link; ?>" class="mega-posts-more"><?php echo esc_html(sprintf(__('Tout %s →', 'prism'), $cat_name)); ?></a>
    </div>
    <?php
    $output .= ob_get_clean();
    return $output;
}, 100, 4);
