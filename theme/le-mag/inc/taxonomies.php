<?php
/**
 * Le Mag — Taxonomies personnalisées (Série, Format) + template loader.
 */
defined('ABSPATH') || exit;

// === Taxonomy : Série (pour articles liés en série) ===
add_action('init', function (): void {
    register_taxonomy('lemag_serie', 'post', [
        'labels' => [
            'name'              => __('Séries', 'lemag'),
            'singular_name'     => __('Série', 'lemag'),
            'menu_name'         => __('Séries', 'lemag'),
            'all_items'         => __('Toutes les séries', 'lemag'),
            'add_new_item'      => __('Ajouter une série', 'lemag'),
            'edit_item'         => __('Modifier la série', 'lemag'),
            'new_item'          => __('Nouvelle série', 'lemag'),
            'view_item'         => __('Voir la série', 'lemag'),
            'search_items'      => __('Rechercher', 'lemag'),
            'not_found'         => __('Aucune série trouvée', 'lemag'),
            'not_found_in_trash'=> __('Aucune série dans la corbeille', 'lemag'),
        ],
        'description'       => __('Série d\'articles liés (feuilleton, dossier spécial).', 'lemag'),
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'hierarchical'      => false,
        'rewrite'           => ['slug' => 'serie'],
    ]);
});

// === Taxonomy : Format (vidéo, galerie, audio, standard) ===
add_action('init', function (): void {
    register_taxonomy('lemag_format', 'post', [
        'labels' => [
            'name'              => __('Formats', 'lemag'),
            'singular_name'     => __('Format', 'lemag'),
            'menu_name'         => __('Formats', 'lemag'),
            'all_items'         => __('Tous les formats', 'lemag'),
            'add_new_item'      => __('Ajouter un format', 'lemag'),
            'edit_item'         => __('Modifier le format', 'lemag'),
            'new_item'          => __('Nouveau format', 'lemag'),
            'view_item'         => __('Voir le format', 'lemag'),
            'search_items'      => __('Rechercher', 'lemag'),
            'not_found'         => __('Aucun format trouvé', 'lemag'),
        ],
        'description'       => __('Format de contenu (vidéo, galerie, audio, standard).', 'lemag'),
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'hierarchical'      => false,
        'meta_box_cb'       => 'lemag_format_meta_box',
    ]);
});

// Pré-remplit les formats par défaut.
add_action('init', function (): void {
    $defaults = ['video' => 'Vidéo', 'gallery' => 'Galerie', 'audio' => 'Audio', 'standard' => 'Standard'];
    foreach ($defaults as $slug => $name) {
        if (!term_exists($slug, 'lemag_format')) {
            wp_insert_term($name, 'lemag_format', ['slug' => $slug]);
        }
    }
}, 20);

// Meta box custom pour les formats (liste déroulante simple).
function lemag_format_meta_box(WP_Post $post): void {
    $current = wp_get_object_terms($post->ID, 'lemag_format', ['fields' => 'slugs']);
    $current = !empty($current) ? $current[0] : 'standard';
    wp_nonce_field('lemag_format_save', 'lemag_format_nonce');
    echo '<select name="lemag_format" id="lemag_format">';
    foreach (['standard' => 'Standard', 'video' => 'Vidéo', 'gallery' => 'Galerie', 'audio' => 'Audio'] as $slug => $label) {
        printf('<option value="%1$s"%3$s>%2$s</option>', esc_attr($slug), esc_html($label), selected($current, $slug, false));
    }
    echo '</select>';
}

// Sauvegarde du format.
add_action('save_post_post', function (int $post_id): void {
    if (!isset($_POST['lemag_format_nonce']) || !wp_verify_nonce($_POST['lemag_format_nonce'], 'lemag_format_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $format = sanitize_key($_POST['lemag_format'] ?? 'standard');
    $valid = ['standard', 'video', 'gallery', 'audio'];
    if (!in_array($format, $valid, true)) $format = 'standard';
    wp_set_object_terms($post_id, [$format], 'lemag_format');
});

// === Template loader : redirige vers single-video.php, single-gallery.php, single-audio.php ===
add_filter('single_template', function (string $template): string {
    if (!is_singular('post')) return $template;
    $formats = wp_get_object_terms(get_the_ID(), 'lemag_format', ['fields' => 'slugs']);
    if (is_wp_error($formats) || empty($formats)) {
        // Auto-détection si aucun format défini.
        $type = lemag_get_post_format_type();
        if ($type !== 'standard') {
            $custom = get_template_directory() . '/single-' . $type . '.php';
            if (file_exists($custom)) return $custom;
        }
        return $template;
    }
    $format = $formats[0];
    if (in_array($format, ['video', 'gallery', 'audio'], true)) {
        $custom = get_template_directory() . '/single-' . $format . '.php';
        if (file_exists($custom)) return $custom;
    }
    return $template;
});
