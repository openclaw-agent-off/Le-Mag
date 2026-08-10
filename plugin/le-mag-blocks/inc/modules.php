<?php
/**
 * Le Mag — Modules hub and lightweight module settings.
 */
defined('ABSPATH') || exit;

function lemag_module_defaults(): array {
    return [
        'background' => ['active' => false, 'color' => '#ffffff', 'image' => ''],
        'blog' => ['active' => false, 'masonry' => false, 'infinite' => false],
        'copyright' => ['active' => false, 'text' => ''],
        'elements' => ['active' => false, 'hide_sidebar' => false, 'hide_footer' => false],
        'site-library' => ['active' => true],
        'spacing' => ['active' => false, 'content' => '1200px', 'gap' => '32px'],
        'woocommerce' => ['active' => false],
    ];
}

function lemag_modules(): array {
    $defaults = lemag_module_defaults();
    $saved = get_option('lemag_modules', []);
    return array_replace_recursive($defaults, is_array($saved) ? $saved : []);
}

function lemag_module_catalog(): array {
    return [
        'background' => ['name' => __('Arrière-plan', 'lemag-blocks'), 'description' => __('Modifiez la couleur et l\'image d\'arrière-plan du site.', 'lemag-blocks')],
        'blog' => ['name' => __('Blog', 'lemag-blocks'), 'description' => __('Activez une mise en page masonry pour les grilles d\'articles.', 'lemag-blocks')],
        'copyright' => ['name' => __('Copyright', 'lemag-blocks'), 'description' => __('Remplacez le texte affiché dans la barre inférieure du pied de page.', 'lemag-blocks')],
        'elements' => ['name' => __('Éléments', 'lemag-blocks'), 'description' => __('Masquez la sidebar ou le pied de page sur l\'ensemble du site.', 'lemag-blocks')],
        'site-library' => ['name' => __('Bibliothèque de Sites', 'lemag-blocks'), 'description' => __('Créez une page d\'accueil à partir d\'un kit Le Mag prédéfini.', 'lemag-blocks')],
        'spacing' => ['name' => __('Espacement', 'lemag-blocks'), 'description' => __('Définissez la largeur maximale et l\'espacement des conteneurs compatibles.', 'lemag-blocks')],
        'woocommerce' => ['name' => __('WooCommerce', 'lemag-blocks'), 'description' => __('Détectez WooCommerce et affichez son état de compatibilité avec Le Mag.', 'lemag-blocks')],
    ];
}

add_action('admin_menu', function (): void {
    // UNE seule page, tout est géré via onglets (?page=lemag-dashboard&tab=xxx)
    add_menu_page('Le Mag', 'Le Mag', 'manage_options', 'lemag-dashboard', 'lemag_dashboard_page', 'dashicons-admin-customizer', 30);
});

function lemag_admin_tabs(string $active): void {
    $tabs = [
        'dashboard'     => __('Tableau de bord', 'lemag-blocks'),
        'apparence'      => __('Apparence', 'lemag-blocks'),
        'header-footer'  => __('En-tête & Pied de page', 'lemag-blocks'),
        'modules'        => __('Modules', 'lemag-blocks'),
        'kits'           => __('Kits', 'lemag-blocks'),
    ];
    $base_url = admin_url('admin.php?page=lemag-dashboard');
    echo '<nav class="nav-tab-wrapper lemag-admin-tabs">';
    foreach ($tabs as $slug => $label) {
        $class = $active === $slug ? ' nav-tab-active' : '';
        echo '<a class="nav-tab' . esc_attr($class) . '" href="' . esc_url($base_url . '&tab=' . $slug) . '">' . esc_html($label) . '</a>';
    }
    // Onglet externe Personnaliser
    echo '<a class="nav-tab" href="' . esc_url(admin_url('customize.php')) . '">' . esc_html__('Personnaliser (live)', 'lemag-blocks') . '</a>';
    echo '</nav><style>.lemag-admin-tabs{margin:22px 0 24px}.lemag-admin-tabs .nav-tab-active{border-bottom-color:#fff;background:#fff}</style>';
}

function lemag_dashboard_page(): void {
    if (!current_user_can('manage_options')) return;
    $tab = sanitize_key($_GET['tab'] ?? 'dashboard');
    $valid = ['dashboard', 'apparence', 'header-footer', 'modules', 'kits'];
    if (!in_array($tab, $valid, true)) $tab = 'dashboard';
    ?>
    <div class="wrap lemag-dashboard">
      <h1>Le Mag</h1>
      <?php lemag_admin_tabs($tab); ?>
      <?php
      if ($tab === 'modules') {
          lemag_modules_page();
          return;
      }
      if ($tab === 'kits' && function_exists('lemag_kits_page')) {
          lemag_kits_page();
          return;
      }
      if ($tab === 'header-footer' && function_exists('lemag_header_footer_page')) {
          lemag_header_footer_page();
          return;
      }
      if ($tab === 'apparence') {
          lemag_apparence_page();
          return;
      }
      // Tableau de bord par défaut
      $modules = lemag_modules();
      $active = count(array_filter($modules, static function (array $module): bool { return !empty($module['active']); }));
      ?>
      <p class="description">Pilotez votre thème entièrement depuis cette interface unique.</p>
      <div class="lemag-dashboard-grid">
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-admin-appearance"></span>
          <h2>Apparence</h2>
          <p>Couleurs, typographie, mode sombre — ou ouvrez le personnalisateur pour la preview live.</p>
          <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=apparence')); ?>">Configurer</a>
          <a class="button" href="<?php echo esc_url(admin_url('customize.php')); ?>">Preview live</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-screenoptions"></span>
          <h2>En-tête & Pied de page</h2>
          <p>Header sticky, barre de lecture, newsletter, réseaux sociaux, partage social.</p>
          <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=header-footer')); ?>">Configurer</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-admin-plugins"></span>
          <h2>Modules</h2>
          <p><?php echo esc_html($active); ?> module(s) actif(s). Activez uniquement les fonctions utiles à votre site.</p>
          <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=modules')); ?>">Gérer les modules</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-layout"></span>
          <h2>Kits de site</h2>
          <p>Créez une page d'accueil à partir d'un kit Le Mag prédéfini en 1 clic.</p>
          <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=kits')); ?>">Ouvrir les kits</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-menu-alt"></span>
          <h2>Mega Menus</h2>
          <p>Configurez les modèles mega menu directement dans l'éditeur de menus WordPress.</p>
          <a class="button" href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">Ouvrir l'éditeur de menus</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-welcome-write-blog"></span>
          <h2>Articles</h2>
          <p>Rédigez et gérez vos articles.</p>
          <a class="button" href="<?php echo esc_url(admin_url('edit.php')); ?>">Voir les articles</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-category"></span>
          <h2>Catégories</h2>
          <p>Organisez votre contenu par rubriques.</p>
          <a class="button" href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=category')); ?>">Gérer les catégories</a>
        </div>
      </div>
      <style>
      .lemag-dashboard{max-width:1100px}.lemag-dashboard-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-top:28px}.lemag-dashboard-card{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:24px;box-shadow:0 1px 2px #0000000d}.lemag-dashboard-card .dashicons{color:#e2003a;font-size:30px;width:30px;height:30px}.lemag-dashboard-card h2{font-size:18px;margin:18px 0 8px}.lemag-dashboard-card p{min-height:48px;color:#646970;line-height:1.5}
      </style>
    </div>
    <?php
}

add_action('admin_init', function (): void {
    if (!isset($_POST['lemag_modules_save']) || !current_user_can('manage_options')) return;
    check_admin_referer('lemag_modules_save');
    $defaults = lemag_module_defaults();
    $posted = isset($_POST['lemag']) && is_array($_POST['lemag']) ? wp_unslash($_POST['lemag']) : [];
    $clean = $defaults;
    foreach ($defaults as $slug => $fields) {
        $clean[$slug]['active'] = !empty($posted[$slug]['active']);
        foreach ($fields as $key => $default) {
            if ($key === 'active') continue;
            $value = $posted[$slug][$key] ?? $default;
            if (is_bool($default)) {
                $clean[$slug][$key] = !empty($value);
            } elseif ($slug === 'background' && $key === 'color') {
                $clean[$slug][$key] = sanitize_hex_color($value) ?: $default;
            } elseif ($slug === 'background' && $key === 'image') {
                $clean[$slug][$key] = esc_url_raw($value);
            } elseif ($slug === 'spacing' && in_array($key, ['content', 'gap'], true)) {
                $clean[$slug][$key] = preg_match('/^\\d+(?:px|rem|em|%|vw|vh)$/', $value) ? $value : $default;
            } else {
                $clean[$slug][$key] = sanitize_text_field($value);
            }
        }
    }
    update_option('lemag_modules', $clean);
    wp_safe_redirect(add_query_arg(['page' => 'lemag-dashboard', 'tab' => 'modules', 'updated' => 1], admin_url('admin.php')));
    exit;
});

function lemag_modules_page(): void {
    if (!current_user_can('manage_options')) return;
    $modules = lemag_modules();
    $catalog = lemag_module_catalog();
    ?>
    <div class="wrap lemag-modules-wrap">
      <h1>Le Mag — Modules</h1>
      <?php lemag_admin_tabs('modules'); ?>
      <p class="description">Activez les fonctionnalités dont votre site a besoin. Les réglages sont conservés lors des mises à jour.</p>
      <?php if (isset($_GET['updated'])): ?><div class="notice notice-success is-dismissible"><p><?php esc_html_e('Modules enregistrés.', 'lemag-blocks'); ?></p></div><?php endif; ?>
      <form method="post">
        <?php wp_nonce_field('lemag_modules_save'); ?>
        <div class="lemag-module-grid">
        <?php foreach ($catalog as $slug => $module): $data = $modules[$slug]; ?>
          <section class="lemag-module-card <?php echo !empty($data['active']) ? 'is-active' : ''; ?>">
            <div class="lemag-module-head"><div><h2><?php echo esc_html($module['name']); ?></h2><p><?php echo esc_html($module['description']); ?></p></div>
              <label class="lemag-switch"><input type="checkbox" name="lemag[<?php echo esc_attr($slug); ?>][active]" value="1" <?php checked(!empty($data['active'])); ?>><span></span></label>
            </div>
            <div class="lemag-module-settings">
            <?php if ($slug === 'background'): ?>
              <label>Couleur <input type="text" name="lemag[background][color]" value="<?php echo esc_attr($data['color']); ?>" class="regular-text"></label>
              <label>Image URL <input type="url" name="lemag[background][image]" value="<?php echo esc_attr($data['image']); ?>" class="large-text"></label>
            <?php elseif ($slug === 'blog'): ?>
              <label><input type="checkbox" name="lemag[blog][masonry]" value="1" <?php checked(!empty($data['masonry'])); ?>> Mise en page masonry</label>
              <label><input type="checkbox" name="lemag[blog][infinite]" value="1" <?php checked(!empty($data['infinite'])); ?>> Chargement continu</label>
            <?php elseif ($slug === 'copyright'): ?>
              <label>Message <input type="text" name="lemag[copyright][text]" value="<?php echo esc_attr($data['text']); ?>" class="large-text"></label>
            <?php elseif ($slug === 'elements'): ?>
              <label><input type="checkbox" name="lemag[elements][hide_sidebar]" value="1" <?php checked(!empty($data['hide_sidebar'])); ?>> Masquer la sidebar</label>
              <label><input type="checkbox" name="lemag[elements][hide_footer]" value="1" <?php checked(!empty($data['hide_footer'])); ?>> Masquer le footer</label>
            <?php elseif ($slug === 'site-library'): ?>
              <p>Les kits sont disponibles dans <a href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=kits')); ?>">Le Mag → Kits</a>.</p>
            <?php elseif ($slug === 'spacing'): ?>
              <label>Largeur contenu <input type="text" name="lemag[spacing][content]" value="<?php echo esc_attr($data['content']); ?>"></label>
              <label>Espacement <input type="text" name="lemag[spacing][gap]" value="<?php echo esc_attr($data['gap']); ?>"></label>
            <?php elseif ($slug === 'woocommerce'): ?>
              <p><?php echo class_exists('WooCommerce') ? esc_html__('WooCommerce détecté : les styles Le Mag sont actifs.', 'lemag-blocks') : esc_html__('Installez WooCommerce pour activer les réglages de boutique.', 'lemag-blocks'); ?></p>
            <?php endif; ?>
            </div>
          </section>
        <?php endforeach; ?>
        </div>
        <p><button type="submit" name="lemag_modules_save" value="1" class="button button-primary">Enregistrer les modules</button></p>
      </form>
    </div>
    <style>
    .lemag-modules-wrap{max-width:1200px}.lemag-module-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(330px,1fr));gap:18px;margin-top:24px}.lemag-module-card{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:20px;box-shadow:0 1px 2px #0000000d}.lemag-module-card.is-active{border-color:#e2003a}.lemag-module-head{display:flex;justify-content:space-between;gap:15px}.lemag-module-head h2{font-size:17px;margin:0 0 5px}.lemag-module-head p{margin:0;color:#646970}.lemag-module-settings{border-top:1px solid #eee;margin-top:18px;padding-top:15px;display:grid;gap:10px}.lemag-module-settings label{display:grid;gap:5px}.lemag-module-settings input[type=checkbox]{margin-right:6px}.lemag-switch input{display:none}.lemag-switch span{display:block;width:38px;height:22px;background:#8c8f94;border-radius:20px;position:relative}.lemag-switch span:after{content:'';position:absolute;width:18px;height:18px;top:2px;left:2px;background:#fff;border-radius:50%;transition:.2s}.lemag-switch input:checked+span{background:#e2003a}.lemag-switch input:checked+span:after{left:18px}
    </style>
    <?php
}

// === CSS dynamique des modules — injecté en front, priorité 98 (avant le CSS du Customizer à 99) ===
add_action('wp_head', function (): void {
    $m = lemag_modules();
    $css = '';
    if (!empty($m['background']['active'])) $css .= 'body{background-color:' . esc_attr($m['background']['color']) . ';' . (!empty($m['background']['image']) ? 'background-image:url(' . esc_url($m['background']['image']) . ');background-size:cover;background-attachment:fixed;' : '') . '}';
    if (!empty($m['spacing']['active'])) $css .= ':root{--lemag-content:' . esc_attr($m['spacing']['content']) . ';--lemag-gap:' . esc_attr($m['spacing']['gap']) . '} .container{max-width:var(--lemag-content);gap:var(--lemag-gap)}';
    if (!empty($m['blog']['active']) && !empty($m['blog']['masonry'])) $css .= '.grid{columns:3 280px;display:block}.grid .card{break-inside:avoid;margin-bottom:24px}';
    if (!empty($m['elements']['active']) && !empty($m['elements']['hide_sidebar'])) $css .= '.sidebar{display:none!important}.article-layout{grid-template-columns:1fr!important}';
    if (!empty($m['elements']['active']) && !empty($m['elements']['hide_footer'])) $css .= '.site-footer{display:none!important}';
    if ($css) echo '<style id="lemag-modules-css">' . $css . '</style>';
}, 98);

add_filter('lemag_copyright', function (string $default): string {
    $m = lemag_modules();
    return !empty($m['copyright']['active']) && !empty($m['copyright']['text']) ? $m['copyright']['text'] : $default;
});

// Note : l'emplacement de menu "secondary" est enregistré par le thème (functions.php).
// Aucun duplicate ici pour éviter l'écrasement silencieux.

// === Onglet Apparence (couleurs, typo, dark mode — synchro avec Customizer via theme_mod) ===
function lemag_apparence_page(): void {
    if (!current_user_can('manage_options')) return;
    // Sauvegarde
    if (isset($_POST['lemag_apparence_save'])) {
        check_admin_referer('lemag_apparence_save');
        set_theme_mod('lemag_primary_color', sanitize_hex_color($_POST['lemag_primary_color'] ?? '#E2003A'));
        set_theme_mod('lemag_bg_color', sanitize_hex_color($_POST['lemag_bg_color'] ?? '#FFFFFF'));
        set_theme_mod('lemag_text_color', sanitize_hex_color($_POST['lemag_text_color'] ?? '#111111'));
        set_theme_mod('lemag_font_body', sanitize_key($_POST['lemag_font_body'] ?? 'inter'));
        set_theme_mod('lemag_font_heading', sanitize_key($_POST['lemag_font_heading'] ?? 'playfair'));
        set_theme_mod('lemag_dark_mode', !empty($_POST['lemag_dark_mode']));
        set_theme_mod('lemag_sticky_header', !empty($_POST['lemag_sticky_header']));
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Apparence enregistrée.', 'lemag-blocks') . '</p></div>';
    }
    $primary = get_theme_mod('lemag_primary_color', '#E2003A');
    $bg = get_theme_mod('lemag_bg_color', '#FFFFFF');
    $text = get_theme_mod('lemag_text_color', '#111111');
    $font_body = get_theme_mod('lemag_font_body', 'inter');
    $font_head = get_theme_mod('lemag_font_heading', 'playfair');
    $dark = get_theme_mod('lemag_dark_mode', false);
    $sticky = get_theme_mod('lemag_sticky_header', true);
    ?>
    <div class="lemag-apparence-wrap">
      <p class="description"><?php esc_html_e('Modifiez l\'apparence de votre site. Pour une preview live, utilisez le personnalisateur.', 'lemag-blocks'); ?>
        <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-small"><?php esc_html_e('Ouvrir le personnalisateur', 'lemag-blocks'); ?></a>
      </p>
      <form method="post">
        <?php wp_nonce_field('lemag_apparence_save'); ?>
        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-art"></span> <?php esc_html_e('Couleurs', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label><?php esc_html_e('Couleur principale', 'lemag-blocks'); ?> <input type="color" name="lemag_primary_color" value="<?php echo esc_attr($primary); ?>" class="lemag-color-picker"></label>
            <label><?php esc_html_e('Fond', 'lemag-blocks'); ?> <input type="color" name="lemag_bg_color" value="<?php echo esc_attr($bg); ?>" class="lemag-color-picker"></label>
            <label><?php esc_html_e('Texte', 'lemag-blocks'); ?> <input type="color" name="lemag_text_color" value="<?php echo esc_attr($text); ?>" class="lemag-color-picker"></label>
          </div>
        </div>
        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-editor-textcolor"></span> <?php esc_html_e('Typographie', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label><?php esc_html_e('Police du texte', 'lemag-blocks'); ?>
              <select name="lemag_font_body">
                <option value="inter" <?php selected($font_body, 'inter'); ?>>Inter (moderne)</option>
                <option value="system" <?php selected($font_body, 'system'); ?>>Système</option>
                <option value="georgia" <?php selected($font_body, 'georgia'); ?>>Georgia (serif)</option>
                <option value="roboto" <?php selected($font_body, 'roboto'); ?>>Roboto</option>
              </select>
            </label>
            <label><?php esc_html_e('Police des titres', 'lemag-blocks'); ?>
              <select name="lemag_font_heading">
                <option value="playfair" <?php selected($font_head, 'playfair'); ?>>Playfair Display</option>
                <option value="inter" <?php selected($font_head, 'inter'); ?>>Inter</option>
                <option value="system" <?php selected($font_head, 'system'); ?>>Système</option>
                <option value="georgia" <?php selected($font_head, 'georgia'); ?>>Georgia</option>
              </select>
            </label>
          </div>
        </div>
        <div class="lemag-hf-section">
          <h2><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Options', 'lemag-blocks'); ?></h2>
          <div class="lemag-hf-grid">
            <label class="lemag-toggle"><input type="checkbox" name="lemag_dark_mode" value="1" <?php checked($dark); ?>><span></span> <?php esc_html_e('Mode sombre', 'lemag-blocks'); ?></label>
            <label class="lemag-toggle"><input type="checkbox" name="lemag_sticky_header" value="1" <?php checked($sticky); ?>><span></span> <?php esc_html_e('Header fixe (sticky)', 'lemag-blocks'); ?></label>
          </div>
        </div>
        <p><button type="submit" name="lemag_apparence_save" value="1" class="button button-primary"><?php esc_html_e('Enregistrer', 'lemag-blocks'); ?></button></p>
      </form>
    </div>
    <style>
    .lemag-apparence-wrap{max-width:800px}
    .lemag-color-picker{width:60px;height:40px;border:1px solid #dcdcde;border-radius:6px;cursor:pointer}
    </style>
    <?php
}
