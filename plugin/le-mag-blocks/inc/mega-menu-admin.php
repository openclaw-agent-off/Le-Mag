<?php
/**
 * Le Mag — Mega Menu admin (simplifié : 3 modèles prédéfinis, sans CPT).
 */
defined('ABSPATH') || exit;

/**
 * Modèles disponibles.
 */
function lemag_mega_models(): array {
    return [
        'posts'     => __('Articles récents', 'lemag-blocks'),
        'categories'=> __('Sous-catégories en colonnes', 'lemag-blocks'),
        'none'      => __('— Aucun (menu normal) —', 'lemag-blocks'),
    ];
}

/**
 * Ajoute un champ « Mega Menu » dans l'éditeur de menus WP (nav-menus.php).
 */
add_action('wp_nav_menu_item_custom_fields', function (int $item_id, WP_Post $item): void {
    $current = get_post_meta($item_id, '_lemag_mega_model', true);
    if (!$current) $current = 'none';
    wp_nonce_field('lemag_mega_save', 'lemag_mega_nonce');
    ?>
    <p class="field-mega-model description description-wide">
        <label for="lemag-mega-model-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Mega Menu :', 'lemag-blocks'); ?><br>
            <select id="lemag-mega-model-<?php echo esc_attr($item_id); ?>" name="lemag_mega_model[<?php echo esc_attr($item_id); ?>]">
                <?php foreach (lemag_mega_models() as $slug => $label): ?>
                    <option value="<?php echo esc_attr($slug); ?>" <?php selected($current, $slug); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <span class="description"><?php esc_html_e('Modèle de panneau déroulant au survol de cet élément.', 'lemag-blocks'); ?></span>
        </label>
    </p>
    <?php
}, 10, 2);

/**
 * Sauvegarde du modèle choisi pour chaque item de menu.
 */
add_action('wp_update_nav_menu_item', function (int $menu_id, int $item_id): void {
    if (!current_user_can('edit_theme_options')) return;
    if (!isset($_POST['lemag_mega_nonce']) || !wp_verify_nonce($_POST['lemag_mega_nonce'], 'lemag_mega_save')) return;
    $model = sanitize_key($_POST['lemag_mega_model'][$item_id] ?? 'none');
    $valid = array_keys(lemag_mega_models());
    if (!in_array($model, $valid, true)) $model = 'none';
    if ($model === 'none') {
        delete_post_meta($item_id, '_lemag_mega_model');
    } else {
        update_post_meta($item_id, '_lemag_mega_model', $model);
    }
}, 10, 2);

/**
 * Petite CSS pour aligner le champ dans l'admin menus.
 */
add_action('admin_enqueue_scripts', function (string $hook): void {
    if ('nav-menus.php' !== $hook) return;
    wp_add_inline_style('lemag-blocks-editor', '.field-mega-model{padding:8px 0;border-top:1px solid #eee;margin-top:8px}.field-mega-model select{max-width:280px}');
});