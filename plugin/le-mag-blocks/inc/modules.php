<?php
/**
 * Le Mag — Modules hub and lightweight module settings.
 */
defined('ABSPATH') || exit;

function prism_module_defaults(): array {
    return [
        'background' => ['active' => false, 'color' => '#ffffff', 'image' => ''],
        'blog' => ['active' => false, 'masonry' => false, 'infinite' => false],
        'copyright' => ['active' => false, 'text' => ''],
        'elements' => ['active' => false, 'hide_sidebar' => false, 'hide_footer' => false],
        'font-library' => ['active' => false, 'body' => 'Inter', 'heading' => 'Playfair Display'],
        'menu-plus' => ['active' => true, 'sticky' => true, 'offcanvas' => false],
        'secondary-nav' => ['active' => false],
        'site-library' => ['active' => true],
        'spacing' => ['active' => false, 'content' => '1200px', 'gap' => '32px'],
        'woocommerce' => ['active' => false],
    ];
}

function prism_modules(): array {
    $defaults = prism_module_defaults();
    $saved = get_option('prism_modules', []);
    return array_replace_recursive($defaults, is_array($saved) ? $saved : []);
}

function prism_module_catalog(): array {
    return [
        'background' => ['name' => __('Arrière-plan', 'prism-blocks'), 'description' => __('Modifiez la couleur et l’image d’arrière-plan du site.', 'prism-blocks')],
        'blog' => ['name' => __('Blog', 'prism-blocks'), 'description' => __('Activez une mise en page masonry pour les grilles d’articles.', 'prism-blocks')],
        'copyright' => ['name' => __('Copyright', 'prism-blocks'), 'description' => __('Remplacez le texte affiché dans la barre inférieure du pied de page.', 'prism-blocks')],
        'elements' => ['name' => __('Éléments', 'prism-blocks'), 'description' => __('Masquez la sidebar ou le pied de page sur l’ensemble du site.', 'prism-blocks')],
        'font-library' => ['name' => __('Font Library', 'prism-blocks'), 'description' => __('Choisissez les familles de polices utilisées par le texte et les titres.', 'prism-blocks')],
        'menu-plus' => ['name' => __('Menu Plus', 'prism-blocks'), 'description' => __('Gérez le comportement sticky du header. Le menu off-canvas mobile n’est pas disponible.', 'prism-blocks')],
        'secondary-nav' => ['name' => __('Secondary Nav', 'prism-blocks'), 'description' => __('Activez un emplacement de navigation secondaire dans le header.', 'prism-blocks')],
        'site-library' => ['name' => __('Bibliothèque de Sites', 'prism-blocks'), 'description' => __('Créez une page d’accueil à partir d’un kit Le Mag prédéfini.', 'prism-blocks')],
        'spacing' => ['name' => __('Espacement', 'prism-blocks'), 'description' => __('Définissez la largeur maximale et l’espacement des conteneurs compatibles.', 'prism-blocks')],
        'woocommerce' => ['name' => __('WooCommerce', 'prism-blocks'), 'description' => __('Détectez WooCommerce et affichez son état de compatibilité avec Le Mag.', 'prism-blocks')],
    ];
}

add_action('admin_menu', function (): void {
    add_menu_page('Le Mag', 'Le Mag', 'manage_options', 'lemag-dashboard', 'lemag_dashboard_page', 'dashicons-admin-customizer', 30);
});

add_action('admin_menu', function (): void {
    remove_submenu_page('lemag-dashboard', 'lemag-dashboard');
}, 99);

function lemag_admin_tabs(string $active, array $tabs, string $base_url): void {
    echo '<nav class="nav-tab-wrapper lemag-admin-tabs">';
    foreach ($tabs as $slug => $label) {
        $class = $active === $slug ? ' nav-tab-active' : '';
        echo '<a class="nav-tab' . esc_attr($class) . '" href="' . esc_url($base_url . '&tab=' . $slug) . '">' . esc_html($label) . '</a>';
    }
    echo '</nav><style>.lemag-admin-tabs{margin:22px 0 24px}.lemag-admin-tabs .nav-tab-active{border-bottom-color:#fff;background:#fff}</style>';
}

function lemag_dashboard_page(): void {
    if (!current_user_can('manage_options')) return;
    $tab = sanitize_key($_GET['tab'] ?? 'dashboard');
    $tabs = ['dashboard' => __('Tableau de bord', 'prism-blocks'), 'modules' => __('Modules', 'prism-blocks'), 'kits' => __('Kits', 'prism-blocks'), 'customize' => __('Personnaliser', 'prism-blocks')];
    if (!isset($tabs[$tab])) $tab = 'dashboard';
    $base_url = admin_url('admin.php?page=lemag-dashboard');
    if ($tab === 'modules') {
        lemag_admin_tabs($tab, $tabs, $base_url);
        prism_modules_page();
        return;
    }
    if ($tab === 'kits' && function_exists('prism_kits_page')) {
        lemag_admin_tabs($tab, $tabs, $base_url);
        prism_kits_page();
        return;
    }
    if ($tab === 'customize') {
        wp_safe_redirect(admin_url('customize.php'));
        exit;
    }
    $modules = prism_modules();
    $active = count(array_filter($modules, static function (array $module): bool { return !empty($module['active']); }));
    ?>
    <div class="wrap lemag-dashboard">
      <h1>Le Mag</h1>
      <p class="description">Pilotez votre thème, ses modules, ses kits et sa personnalisation depuis cette interface.</p>
      <?php lemag_admin_tabs($tab, $tabs, $base_url); ?>
      <div class="lemag-dashboard-grid">
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-admin-appearance"></span>
          <h2>Personnaliser le site</h2>
          <p>Modifiez le logo, les couleurs, la typographie et les options générales.</p>
          <a class="button button-primary" href="<?php echo esc_url(admin_url('customize.php')); ?>">Ouvrir le personnalisateur</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-admin-plugins"></span>
          <h2>Modules</h2>
          <p><?php echo esc_html($active); ?> module(s) actif(s). Activez uniquement les fonctions utiles à votre site.</p>
          <a class="button" href="<?php echo esc_url($base_url . '&tab=modules'); ?>">Gérer les modules</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-layout"></span>
          <h2>Kits de site</h2>
          <p>Créez une page d’accueil à partir d’un kit Le Mag prédéfini.</p>
          <a class="button" href="<?php echo esc_url($base_url . '&tab=kits'); ?>">Ouvrir les kits</a>
        </div>
        <div class="lemag-dashboard-card">
          <span class="dashicons dashicons-menu-alt"></span>
          <h2>Mega Menus</h2>
          <p>Créez des menus enrichis avec articles et catégories.</p>
          <a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=lemag_mega_menu')); ?>">Gérer les mega menus</a>
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
    if (!isset($_POST['prism_modules_save']) || !current_user_can('manage_options')) return;
    check_admin_referer('prism_modules_save');
    $defaults = prism_module_defaults();
    $posted = isset($_POST['prism']) && is_array($_POST['prism']) ? wp_unslash($_POST['prism']) : [];
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
    update_option('prism_modules', $clean);
    wp_safe_redirect(add_query_arg(['page' => 'lemag-dashboard', 'tab' => 'modules', 'updated' => 1], admin_url('admin.php')));
    exit;
});

function prism_modules_page(): void {
    if (!current_user_can('manage_options')) return;
    $modules = prism_modules();
    $catalog = prism_module_catalog();
    ?>
    <div class="wrap prism-modules-wrap">
      <h1>Le Mag — Modules</h1>
      <p class="description">Activez les fonctionnalités dont votre site a besoin. Les réglages sont conservés lors des mises à jour.</p>
      <?php if (isset($_GET['updated'])): ?><div class="notice notice-success is-dismissible"><p><?php esc_html_e('Modules enregistrés.', 'prism-blocks'); ?></p></div><?php endif; ?>
      <form method="post">
        <?php wp_nonce_field('prism_modules_save'); ?>
        <div class="prism-module-grid">
        <?php foreach ($catalog as $slug => $module): $data = $modules[$slug]; ?>
          <section class="prism-module-card <?php echo !empty($data['active']) ? 'is-active' : ''; ?>">
            <div class="prism-module-head"><div><h2><?php echo esc_html($module['name']); ?></h2><p><?php echo esc_html($module['description']); ?></p></div>
              <label class="prism-switch"><input type="checkbox" name="prism[<?php echo esc_attr($slug); ?>][active]" value="1" <?php checked(!empty($data['active'])); ?>><span></span></label>
            </div>
            <div class="prism-module-settings">
            <?php if ($slug === 'background'): ?>
              <label>Couleur <input type="text" name="prism[background][color]" value="<?php echo esc_attr($data['color']); ?>" class="regular-text"></label>
              <label>Image URL <input type="url" name="prism[background][image]" value="<?php echo esc_attr($data['image']); ?>" class="large-text"></label>
            <?php elseif ($slug === 'blog'): ?>
              <label><input type="checkbox" name="prism[blog][masonry]" value="1" <?php checked(!empty($data['masonry'])); ?>> Mise en page masonry</label>
              <label><input type="checkbox" name="prism[blog][infinite]" value="1" <?php checked(!empty($data['infinite'])); ?>> Chargement continu</label>
            <?php elseif ($slug === 'copyright'): ?>
              <label>Message <input type="text" name="prism[copyright][text]" value="<?php echo esc_attr($data['text']); ?>" class="large-text"></label>
            <?php elseif ($slug === 'elements'): ?>
              <label><input type="checkbox" name="prism[elements][hide_sidebar]" value="1" <?php checked(!empty($data['hide_sidebar'])); ?>> Masquer la sidebar</label>
              <label><input type="checkbox" name="prism[elements][hide_footer]" value="1" <?php checked(!empty($data['hide_footer'])); ?>> Masquer le footer</label>
            <?php elseif ($slug === 'font-library'): ?>
              <label>Police texte <select name="prism[font-library][body]"><?php foreach (['Inter','Roboto','Open Sans','System'] as $font): ?><option <?php selected($data['body'], $font); ?>><?php echo esc_html($font); ?></option><?php endforeach; ?></select></label>
              <label>Police titres <select name="prism[font-library][heading]"><?php foreach (['Playfair Display','Inter','Roboto','System'] as $font): ?><option <?php selected($data['heading'], $font); ?>><?php echo esc_html($font); ?></option><?php endforeach; ?></select></label>
            <?php elseif ($slug === 'menu-plus'): ?>
              <label><input type="checkbox" name="prism[menu-plus][sticky]" value="1" <?php checked(!empty($data['sticky'])); ?>> Header sticky</label>
              <label><input type="checkbox" name="prism[menu-plus][offcanvas]" value="1" <?php checked(!empty($data['offcanvas'])); ?>> Menu mobile off-canvas</label>
            <?php elseif ($slug === 'secondary-nav'): ?>
              <p>Créez un menu dans <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">Apparence → Menus</a>, puis assignez-le à l’emplacement Secondary Nav.</p>
            <?php elseif ($slug === 'site-library'): ?>
              <p>Les kits sont disponibles dans <a href="<?php echo esc_url(admin_url('admin.php?page=lemag-dashboard&tab=kits')); ?>">Le Mag → Kits</a>.</p>
            <?php elseif ($slug === 'spacing'): ?>
              <label>Largeur contenu <input type="text" name="prism[spacing][content]" value="<?php echo esc_attr($data['content']); ?>"></label>
              <label>Espacement <input type="text" name="prism[spacing][gap]" value="<?php echo esc_attr($data['gap']); ?>"></label>
            <?php elseif ($slug === 'woocommerce'): ?>
              <p><?php echo class_exists('WooCommerce') ? esc_html__('WooCommerce détecté : les styles Le Mag sont actifs.', 'prism-blocks') : esc_html__('Installez WooCommerce pour activer les réglages de boutique.', 'prism-blocks'); ?></p>
            <?php endif; ?>
            </div>
          </section>
        <?php endforeach; ?>
        </div>
        <p><button type="submit" name="prism_modules_save" value="1" class="button button-primary">Enregistrer les modules</button></p>
      </form>
    </div>
    <style>
    .prism-modules-wrap{max-width:1200px}.prism-module-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(330px,1fr));gap:18px;margin-top:24px}.prism-module-card{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:20px;box-shadow:0 1px 2px #0000000d}.prism-module-card.is-active{border-color:#e2003a}.prism-module-head{display:flex;justify-content:space-between;gap:15px}.prism-module-head h2{font-size:17px;margin:0 0 5px}.prism-module-head p{margin:0;color:#646970}.prism-module-settings{border-top:1px solid #eee;margin-top:18px;padding-top:15px;display:grid;gap:10px}.prism-module-settings label{display:grid;gap:5px}.prism-module-settings input[type=checkbox]{margin-right:6px}.prism-switch input{display:none}.prism-switch span{display:block;width:38px;height:22px;background:#8c8f94;border-radius:20px;position:relative}.prism-switch span:after{content:'';position:absolute;width:18px;height:18px;top:2px;left:2px;background:#fff;border-radius:50%;transition:.2s}.prism-switch input:checked+span{background:#e2003a}.prism-switch input:checked+span:after{left:18px}
    </style>
    <?php
}

add_action('wp_head', function (): void {
    $m = prism_modules();
    $css = '';
    if (!empty($m['background']['active'])) $css .= 'body{background-color:' . esc_attr($m['background']['color']) . ';' . (!empty($m['background']['image']) ? 'background-image:url(' . esc_url($m['background']['image']) . ');background-size:cover;background-attachment:fixed;' : '') . '}';
    if (!empty($m['spacing']['active'])) $css .= ':root{--prism-content:' . esc_attr($m['spacing']['content']) . ';--prism-gap:' . esc_attr($m['spacing']['gap']) . '} .container{max-width:var(--prism-content);gap:var(--prism-gap)}';
    if (!empty($m['blog']['active']) && !empty($m['blog']['masonry'])) $css .= '.grid{columns:3 280px;display:block}.grid .card{break-inside:avoid;margin-bottom:24px}';
    if (!empty($m['menu-plus']['active']) && empty($m['menu-plus']['sticky'])) $css .= '.site-header{position:static}';
    if (!empty($m['elements']['active']) && !empty($m['elements']['hide_sidebar'])) $css .= '.sidebar{display:none!important}.article-layout{grid-template-columns:1fr!important}';
    if (!empty($m['elements']['active']) && !empty($m['elements']['hide_footer'])) $css .= '.site-footer{display:none!important}';
    if (!empty($m['font-library']['active'])) {
        $body_fonts = ['Inter', 'Roboto', 'Open Sans', 'System'];
        $heading_fonts = ['Playfair Display', 'Inter', 'Roboto', 'System'];
        $body_font = in_array($m['font-library']['body'], $body_fonts, true) ? $m['font-library']['body'] : 'Inter';
        $heading_font = in_array($m['font-library']['heading'], $heading_fonts, true) ? $m['font-library']['heading'] : 'Playfair Display';
        $css .= 'body{font-family:' . esc_attr($body_font) . ',sans-serif}.section-title,.hero h2,.article-content h1,.article-content h2{font-family:' . esc_attr($heading_font) . ',serif}';
    }
    if ($css) echo '<style id="prism-modules-css">' . $css . '</style>';
}, 98);

add_filter('prism_copyright', function (string $default): string {
    $m = prism_modules();
    return !empty($m['copyright']['active']) && !empty($m['copyright']['text']) ? $m['copyright']['text'] : $default;
});

add_action('after_setup_theme', function (): void {
    register_nav_menus(['secondary' => __('Secondary Nav', 'prism')]);
});
