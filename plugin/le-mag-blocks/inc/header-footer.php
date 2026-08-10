<?php
/**
 * Le Mag — En-tête, Pied de page & Partage social (réglages simplifiés).
 */
defined('ABSPATH') || exit;

function lemag_hf_defaults(): array {
    return [
        // En-tête
        'sticky_header'      => true,
        'reading_progress'   => true,
        'secondary_nav'      => false,
        'header_layout'      => 'logo-left',
        // Pied de page
        'footer_newsletter'  => true,
        'footer_columns'     => '4',
        'footer_text'        => '',
        'footer_social'      => true,
        // Réseaux sociaux (footer)
        'social_twitter'     => '',
        'social_facebook'    => '',
        'social_instagram'   => '',
        'social_linkedin'   => '',
        'social_youtube'    => '',
        // Partage social (articles)
        'share_enabled'      => true,
        'share_facebook'     => true,
        'share_twitter'      => true,
        'share_linkedin'     => true,
        'share_whatsapp'     => true,
        'share_telegram'     => false,
        'share_email'        => true,
    ];
}

function lemag_hf_settings(): array {
    $saved = get_option('lemag_hf_settings', []);
    $defaults = lemag_hf_defaults();
    return array_replace_recursive($defaults, is_array($saved) ? $saved : []);
}

// === Page admin (rendu dans la page unifiée via onglet) ===
function lemag_header_footer_page(): void {
    if (!current_user_can('manage_options')) return;
    $s = lemag_hf_settings();
    ?>
    <div class="lemag-hf-wrap">
      <?php if (isset($_GET['updated'])): ?><div class="notice notice-success is-dismissible"><p><?php esc_html_e('Réglages enregistrés.', 'lemag-blocks'); ?></p></div><?php endif; ?>
      <form method="post">
        <?php wp_nonce_field('lemag_hf_save'); ?>

        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e('En-tête', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[sticky_header]" value="1" <?php checked(!empty($s['sticky_header'])); ?>><span></span> <?php esc_html_e('Header sticky (fixe en haut)', 'lemag-blocks'); ?></label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[reading_progress]" value="1" <?php checked(!empty($s['reading_progress'])); ?>><span></span> <?php esc_html_e('Barre de progression de lecture', 'lemag-blocks'); ?></label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[secondary_nav]" value="1" <?php checked(!empty($s['secondary_nav'])); ?>><span></span> <?php esc_html_e('Navigation secondaire', 'lemag-blocks'); ?></label>
            <label><?php esc_html_e('Disposition du logo', 'lemag-blocks'); ?>
              <select name="lemag_hf[header_layout]">
                <option value="logo-left" <?php selected($s['header_layout'], 'logo-left'); ?>><?php esc_html_e('Logo à gauche + menu à droite', 'lemag-blocks'); ?></option>
                <option value="logo-center" <?php selected($s['header_layout'], 'logo-center'); ?>><?php esc_html_e('Logo centré + menu en dessous', 'lemag-blocks'); ?></option>
              </select>
            </label>
          </div>
        </div>

        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-screenoptions"></span> <?php esc_html_e('Pied de page', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[footer_newsletter]" value="1" <?php checked(!empty($s['footer_newsletter'])); ?>><span></span> <?php esc_html_e('Bloc newsletter', 'lemag-blocks'); ?></label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[footer_social]" value="1" <?php checked(!empty($s['footer_social'])); ?>><span></span> <?php esc_html_e('Icônes réseaux sociaux', 'lemag-blocks'); ?></label>
            <label><?php esc_html_e('Nombre de colonnes', 'lemag-blocks'); ?>
              <select name="lemag_hf[footer_columns]">
                <option value="3" <?php selected($s['footer_columns'], '3'); ?>>3</option>
                <option value="4" <?php selected($s['footer_columns'], '4'); ?>>4</option>
                <option value="5" <?php selected($s['footer_columns'], '5'); ?>>5</option>
              </select>
            </label>
            <label class="lemag-full"><?php esc_html_e('Texte du footer (remplace « WordPress + Le Mag »)', 'lemag-blocks'); ?>
              <input type="text" name="lemag_hf[footer_text]" value="<?php echo esc_attr($s['footer_text']); ?>" class="large-text" placeholder="© 2026 Mon Site">
            </label>
          </div>
        </div>

        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-share"></span> <?php esc_html_e('Réseaux sociaux (footer)', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label>Twitter <input type="url" name="lemag_hf[social_twitter]" value="<?php echo esc_attr($s['social_twitter']); ?>" class="regular-text" placeholder="https://twitter.com/..."></label>
            <label>Facebook <input type="url" name="lemag_hf[social_facebook]" value="<?php echo esc_attr($s['social_facebook']); ?>" class="regular-text" placeholder="https://facebook.com/..."></label>
            <label>Instagram <input type="url" name="lemag_hf[social_instagram]" value="<?php echo esc_attr($s['social_instagram']); ?>" class="regular-text" placeholder="https://instagram.com/..."></label>
            <label>LinkedIn <input type="url" name="lemag_hf[social_linkedin]" value="<?php echo esc_attr($s['social_linkedin']); ?>" class="regular-text" placeholder="https://linkedin.com/..."></label>
            <label>YouTube <input type="url" name="lemag_hf[social_youtube]" value="<?php echo esc_attr($s['social_youtube']); ?>" class="regular-text" placeholder="https://youtube.com/..."></label>
          </div>
        </div>

        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-format-status"></span> <?php esc_html_e('Partage social (articles)', 'lemag-blocks'); ?></h2>
          <p class="description"><?php esc_html_e('Activez les boutons de partage affichés sous chaque article.', 'lemag-blocks'); ?></p>
          <div class="lemag-hf-grid">
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_enabled]" value="1" <?php checked(!empty($s['share_enabled'])); ?>><span></span> <?php esc_html_e('Afficher les boutons de partage', 'lemag-blocks'); ?></label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_facebook]" value="1" <?php checked(!empty($s['share_facebook'])); ?>><span></span> Facebook</label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_twitter]" value="1" <?php checked(!empty($s['share_twitter'])); ?>><span></span> Twitter / X</label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_linkedin]" value="1" <?php checked(!empty($s['share_linkedin'])); ?>><span></span> LinkedIn</label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_whatsapp]" value="1" <?php checked(!empty($s['share_whatsapp'])); ?>><span></span> WhatsApp</label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_telegram]" value="1" <?php checked(!empty($s['share_telegram'])); ?>><span></span> Telegram</label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_hf[share_email]" value="1" <?php checked(!empty($s['share_email'])); ?>><span></span> <?php esc_html_e('Email', 'lemag-blocks'); ?></label>
          </div>
        </div>

        <p><button type="submit" name="lemag_hf_save" value="1" class="button button-primary"><?php esc_html_e('Enregistrer', 'lemag-blocks'); ?></button></p>
      </form>
    </div>
    <style>
    .lemag-hf-wrap{max-width:800px}.lemag-hf-section{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:24px;margin:20px 0}.lemag-hf-section h2{font-size:18px;margin:0 0 16px;display:flex;align-items:center;gap:8px}.lemag-hf-section h2 .dashicons{color:#e2003a}.lemag-hf-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px}.lemag-hf-grid label{display:flex;flex-direction:column;gap:5px;font-size:.88rem;font-weight:600}.lemag-hf-grid .lemag-full{grid-column:1/-1}.lemag-hf-grid select{max-width:280px}.lemag-toggle{flex-direction:row!important;align-items:center;gap:10px!important}
    </style>
    <?php
}

// === Sauvegarde ===
add_action('admin_init', function (): void {
    if (!isset($_POST['lemag_hf_save']) || !current_user_can('manage_options')) return;
    check_admin_referer('lemag_hf_save');
    $defaults = lemag_hf_defaults();
    $posted = isset($_POST['lemag_hf']) && is_array($_POST['lemag_hf']) ? wp_unslash($_POST['lemag_hf']) : [];
    $clean = $defaults;
    // Booléens
    foreach (['sticky_header', 'reading_progress', 'secondary_nav', 'footer_newsletter', 'footer_social', 'share_enabled', 'share_facebook', 'share_twitter', 'share_linkedin', 'share_whatsapp', 'share_telegram', 'share_email'] as $key) {
        $clean[$key] = !empty($posted[$key]);
    }
    // Selects
    $clean['header_layout'] = in_array($posted['header_layout'] ?? '', ['logo-left', 'logo-center'], true) ? $posted['header_layout'] : 'logo-left';
    $clean['footer_columns'] = in_array($posted['footer_columns'] ?? '', ['3', '4', '5'], true) ? $posted['footer_columns'] : '4';
    // Text
    $clean['footer_text'] = sanitize_text_field($posted['footer_text'] ?? '');
    // URLs
    foreach (['social_twitter', 'social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube'] as $key) {
        $clean[$key] = esc_url_raw($posted[$key] ?? '');
    }
    update_option('lemag_hf_settings', $clean);
    wp_safe_redirect(add_query_arg(['page' => 'lemag-dashboard', 'tab' => 'header-footer', 'updated' => 1], admin_url('admin.php')));
    exit;
});

// === Application : CSS + filtres ===
add_action('wp_head', function (): void {
    $s = lemag_hf_settings();
    $css = '';
    if (empty($s['sticky_header'])) $css .= '.site-header{position:static}';
    if (empty($s['reading_progress'])) $css .= '.reading-progress{display:none}';
    if ($s['header_layout'] === 'logo-center') $css .= '.header-inner{flex-direction:column;height:auto;padding:16px 24px;gap:12px}.site-logo{margin:0 auto}';
    if (in_array($s['footer_columns'], ['3', '4', '5'], true)) {
        $map = ['3' => '2fr 1fr 1fr', '4' => '2fr 1fr 1fr 1fr', '5' => '1.5fr 1fr 1fr 1fr 1.5fr'];
        $css .= '.footer-grid{grid-template-columns:' . $map[$s['footer_columns']] . '}';
    }
    if ($css) echo '<style id="lemag-hf-css">' . $css . '</style>';
}, 97);

// Filtre copyright du footer
add_filter('lemag_copyright', function (string $default): string {
    $s = lemag_hf_settings();
    return !empty($s['footer_text']) ? $s['footer_text'] : $default;
});

// Affichage des réseaux sociaux dans le footer
add_action('lemag_footer_social', function (): void {
    $s = lemag_hf_settings();
    if (empty($s['footer_social'])) return;
    $networks = [
        'twitter'   => $s['social_twitter'],
        'facebook'  => $s['social_facebook'],
        'instagram'=> $s['social_instagram'],
        'linkedin' => $s['social_linkedin'],
        'youtube'  => $s['social_youtube'],
    ];
    $networks = array_filter($networks);
    if (empty($networks)) return;
    echo '<div class="lemag-footer-social">';
    foreach ($networks as $net => $url) {
        printf('<a href="%1$s" target="_blank" rel="noopener noreferrer" class="lemag-footer-social-%2$s" aria-label="%3$s">%4$s</a>',
            esc_url($url), esc_attr($net), esc_attr(ucfirst($net)), esc_html(ucfirst($net[0]))
        );
    }
    echo '</div>';
});