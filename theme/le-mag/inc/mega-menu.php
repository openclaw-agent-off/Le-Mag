<?php
/**
 * Mega Menu — Walker frontend + affichage du contenu Mega Menu.
 * Intégration du CPT lemag_mega_menu.
 */
defined('ABSPATH') || exit;

// Case à cocher "Mega Menu" dans l'éditeur de menus (mode simplifié)
add_action('wp_nav_menu_item_custom_fields', function ($id, $item, $depth, $args) {
    if ($depth !== 0) return;
    $mega = get_post_meta($item->ID, '_prism_mega', true);
    $item_id = absint($item->ID);
    echo '<p class="description description-wide"><label><input type="checkbox" name="prism-mega[' . $item_id . ']" value="1" ' . checked($mega, '1', false) . ' /> ' . esc_html__('Mega Menu (colonnes)', 'prism') . '</label></p>';
}, 10, 4);

add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (!current_user_can('edit_theme_options')) return;
    $menu_item_db_id = absint($menu_item_db_id);
    if (!$menu_item_db_id) return;
    if (!isset($_POST['menu-item']) || !is_array($_POST['menu-item']) || !in_array($menu_item_db_id, array_map('absint', array_keys($_POST['menu-item'])), true)) return;
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

// === Affichage du Mega Menu (CPT lemag_mega_menu) ===
add_filter('walker_nav_menu_start_el', function ($output, $item, $depth, $args) {
    $args = (array) $args;
    if ($depth !== 0) return $output;
    if (!empty($args['hide_mega_menu'])) return $output;

    $mega_menu_id = get_post_meta($item->ID, '_lemag_mega_menu_id', true);
    if (empty($mega_menu_id)) return $output;

    $mega_menu = get_post($mega_menu_id);
    if (!$mega_menu || is_wp_error($mega_menu)) return $output;

    // Remplacer la classe mega-menu si pas déjà présente
    if (strpos($output, 'mega-menu') === false) {
        $output = str_replace('menu-item', 'menu-item mega-menu', $output);
    }

    // Récupérer le contenu du mega menu
    $content = apply_filters('the_content', $mega_menu->post_content);
    if (empty(trim(strip_tags($content)))) return $output;

    $output .= '<div class="mega-menu-panel">' . $content . '</div>';
    return $output;
}, 100, 4);
