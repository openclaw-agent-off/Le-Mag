<?php
/**
 * Le Mag — Mega Menu CPT + Admin integration.
 * Adapted from crowdfavorite/wp-mega-menus.
 */
defined('ABSPATH') || exit;

// === CPT Mega Menu ===
add_action('init', function (): void {
    register_post_type('lemag_mega_menu', [
        'labels' => [
            'name'               => __('Mega Menus', 'prism-blocks'),
            'singular_name'      => __('Mega Menu', 'prism-blocks'),
            'menu_name'          => __('Mega Menus', 'prism-blocks'),
            'all_items'          => __('Tous les Mega Menus', 'prism-blocks'),
            'add_new_item'       => __('Ajouter un Mega Menu', 'prism-blocks'),
            'edit_item'          => __('Modifier le Mega Menu', 'prism-blocks'),
            'new_item'           => __('Nouveau Mega Menu', 'prism-blocks'),
            'view_item'          => __('Voir le Mega Menu', 'prism-blocks'),
            'search_items'       => __('Rechercher', 'prism-blocks'),
            'not_found'          => __('Aucun mega menu trouvé', 'prism-blocks'),
            'not_found_in_trash' => __('Aucun mega menu dans la corbeille', 'prism-blocks'),
        ],
        'description'  => __('Contenu affiché dans les menus déroulants mega.', 'prism-blocks'),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_position'=> 31,
        'hierarchical' => true,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'capability_type' => 'post',
    ]);
});

// === Admin Walker: ajouter le sélecteur de Mega Menu dans l'éditeur de menus ===
add_filter('wp_edit_nav_menu_walker', function (): string {
    require_once __DIR__ . '/class-lemag-mega-menu-edit-walker.php';
    return 'LeMag_Mega_Menu_Walker_Nav_Menu_Edit';
});

// === Sauvegarde du lien menu ↔ mega menu ===
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_id): void {
    $mega_menu_id = false;
    if (!empty($_REQUEST['menu-item-mega-menu']) && !empty($_REQUEST['menu-item-mega-menu'][$menu_item_id])) {
        $mega_menu_id = intval($_REQUEST['menu-item-mega-menu'][$menu_item_id]);
    }
    if (!empty($mega_menu_id)) {
        update_post_meta($menu_item_id, '_lemag_mega_menu_id', $mega_menu_id);
    } else {
        delete_post_meta($menu_item_id, '_lemag_mega_menu_id');
    }
}, 10, 2);
