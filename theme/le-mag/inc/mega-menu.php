<?php
/**
 * Mega Menu — Walker frontend + affichage du contenu Mega Menu (CPT lemag_mega_menu).
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

        // CPT mega menu : ajouter la classe mega-menu
        $mega_id = get_post_meta($item->ID, '_lemag_mega_menu_id', true);
        if ($depth === 0 && !empty($mega_id)) {
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

// === Affichage du contenu CPT dans le mega menu ===
add_filter('walker_nav_menu_start_el', function ($output, $item, $depth, $args) {
    $args = (array) $args;
    if ($depth !== 0) return $output;
    if (!empty($args['hide_mega_menu'])) return $output;

    $mega_menu_id = get_post_meta($item->ID, '_lemag_mega_menu_id', true);
    if (empty($mega_menu_id)) return $output;

    $mega_menu = get_post($mega_menu_id);
    if (!$mega_menu || is_wp_error($mega_menu)) return $output;

    if (strpos($output, 'mega-menu') === false) {
        $output = str_replace('menu-item', 'menu-item mega-menu', $output);
    }

    $content = apply_filters('the_content', $mega_menu->post_content);
    if (empty(trim(strip_tags($content)))) return $output;

    $output .= '<div class="mega-menu-panel">' . $content . '</div>';
    return $output;
}, 100, 4);
