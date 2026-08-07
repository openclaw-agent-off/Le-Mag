<?php
/**
 * Mega Menu — Alecaddd-style
 */
defined('ABSPATH') || exit;

add_action('wp_nav_menu_item_custom_fields', function ($id, $item, $depth, $args) {
    if ($depth !== 0) return;
    $mega = get_post_meta($item->ID, '_prism_mega', true);
    $item_id = absint($item->ID);
    echo '<p class="description description-wide"><label><input type="checkbox" name="prism-mega[' . $item_id . ']" value="1" ' . checked($mega, '1', false) . ' /> ' . esc_html__('Mega Menu', 'prism') . '</label></p>';
}, 10, 4);

add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (!current_user_can('edit_theme_options')) return;

    $menu_item_db_id = absint($menu_item_db_id);
    if (!$menu_item_db_id) return;

    if (!isset($_POST['menu-item']) || !is_array($_POST['menu-item']) || !in_array($menu_item_db_id, array_map('absint', array_keys($_POST['menu-item'])), true)) {
        return;
    }

    if (isset($_POST['prism-mega'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_prism_mega', '1');
    } else {
        delete_post_meta($menu_item_db_id, '_prism_mega');
    }
}, 10, 2);

class Prism_Mega_Walker extends Walker_Nav_Menu {

    private $megaMenuID = 0;
    private $columnCount = 0;

    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul class=\"sub-menu depth_$depth\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        // Reset mega menu au prochain item niveau 0
        if ($this->megaMenuID && $depth === 0 && $item->menu_item_parent != $this->megaMenuID) {
            $this->megaMenuID = 0;
        }

        if ($depth === 0 && get_post_meta($item->ID, '_prism_mega', true)) {
            $item->classes[] = 'mega-menu';
            $this->megaMenuID = $item->ID;
            $this->columnCount = 0;
        }

        // Chaque enfant direct = nouvelle colonne
        if ($this->megaMenuID && $depth === 1) {
            // Fermer colonne précédente
            if ($this->columnCount > 0) $output .= "</ul></li>";
            $this->columnCount++;
            $output .= "<li class=\"mega-col\"><ul>\n";
        }

        parent::start_el($output, $item, $depth, $args, $id);
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // Fermer dernière colonne
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
