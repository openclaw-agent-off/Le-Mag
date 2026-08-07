<?php
/**
 * Le Mag Blocks — Site Kits (templates magazine prêts à importer)
 */
defined('ABSPATH') || exit;

function prism_kits_page() {
    $kits = prism_get_kits();
    ?>
    <div class="prism-admin-wrap">
        <div class="prism-admin-hero">
            <div class="prism-admin-logo">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="10" fill="#E2003A"/><path d="M12 25V11l7 9 7-9v14" stroke="#fff" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div>
                    <h1>Le Mag</h1>
                    <p>Templates magazine — importez une page d'accueil complète en 1 clic</p>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['imported'])): ?>
        <div class="prism-notice prism-notice-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
            Page créée et définie comme page d'accueil.
        </div>
        <?php endif; ?>

        <div class="prism-kits-grid">
            <?php foreach ($kits as $slug => $kit): ?>
            <div class="prism-kit-card">
                <div class="prism-kit-preview" style="background:<?php echo esc_attr($kit['color']); ?>">
                    <div class="prism-kit-badge"><?php echo esc_html($kit['name']); ?></div>
                    <div class="prism-kit-preview-lines">
                        <span></span><span></span><span></span>
                    </div>
                </div>
                <div class="prism-kit-body">
                    <h3><?php echo esc_html($kit['name']); ?></h3>
                    <p><?php echo esc_html($kit['description']); ?></p>
                    <div class="prism-kit-actions">
                        <button class="prism-btn prism-btn-primary prism-import-kit" data-kit="<?php echo esc_attr($slug); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Importer
                        </button>
                        <a href="<?php echo esc_url($kit['preview']); ?>" class="prism-btn prism-btn-ghost" target="_blank" rel="noopener noreferrer">
                            Aperçu
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
    .prism-admin-wrap{max-width:1080px;margin:32px 0 0 0;font-family:Inter,-apple-system,BlinkMacSystemFont,sans-serif}
    .prism-admin-hero{margin-bottom:40px}
    .prism-admin-logo{display:flex;align-items:center;gap:20px}
    .prism-admin-logo h1{font-size:1.6rem;font-weight:800;margin:0;color:#1e1e1e;line-height:1.2}
    .prism-admin-logo p{font-size:.88rem;color:#6c6c6c;margin:4px 0 0}
    .prism-notice{display:flex;align-items:center;gap:10px;padding:14px 18px;border-radius:10px;margin-bottom:28px;font-size:.88rem;font-weight:500}
    .prism-notice-success{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
    .prism-kits-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px}
    .prism-kit-card{background:#fff;border:1px solid #e2e4e7;border-radius:14px;overflow:hidden;transition:transform .2s,box-shadow .2s}
    .prism-kit-card:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.08)}
    .prism-kit-preview{height:180px;display:flex;align-items:flex-end;justify-content:flex-start;padding:20px;position:relative;overflow:hidden}
    .prism-kit-badge{background:rgba(255,255,255,.2);backdrop-filter:blur(6px);color:#fff;padding:6px 14px;border-radius:6px;font-size:.8rem;font-weight:700;letter-spacing:.03em}
    .prism-kit-preview-lines{position:absolute;right:-20px;bottom:-20px;display:flex;flex-direction:column;gap:4px;opacity:.15}
    .prism-kit-preview-lines span{display:block;background:#fff;height:3px;border-radius:2px}
    .prism-kit-preview-lines span:nth-child(1){width:80px}
    .prism-kit-preview-lines span:nth-child(2){width:110px}
    .prism-kit-preview-lines span:nth-child(3){width:60px}
    .prism-kit-body{padding:24px}
    .prism-kit-body h3{font-size:1.05rem;font-weight:700;margin:0 0 6px;color:#1e1e1e}
    .prism-kit-body p{font-size:.82rem;color:#6c6c6c;margin:0 0 20px;line-height:1.5}
    .prism-kit-actions{display:flex;gap:8px}
    .prism-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .15s;border:none}
    .prism-btn-primary{background:#E2003A;color:#fff;box-shadow:0 1px 2px rgba(226,0,58,.3)}
    .prism-btn-primary:hover{background:#c80033;color:#fff}
    .prism-btn-primary:disabled{opacity:.6;cursor:not-allowed}
    .prism-btn-ghost{background:transparent;color:#50575e;border:1px solid #dcdcde}
    .prism-btn-ghost:hover{background:#f0f0f1;color:#1e1e1e}
    </style>

    <script>
    jQuery(function($){
        $('.prism-import-kit').on('click', function(){
            var btn = $(this), kit = btn.data('kit');
            btn.prop('disabled',true).html('<span style="display:inline-block;animation:spin .6s linear infinite">⟳</span> Import...');
            $.post(ajaxurl, {action:'prism_import_kit',kit:kit,nonce:'<?php echo wp_create_nonce("prism-kits"); ?>'}, function(r){
                if(r.success){ window.location = '?page=lemag-dashboard&tab=kits&imported=1'; }
                else { alert('Erreur : '+r.data); btn.prop('disabled',false).html('Réessayer'); }
            });
        });
    });
    </script>
    <?php
}

// Ajax import
add_action('wp_ajax_prism_import_kit', function () {
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'] ?? '', 'prism-kits')) {
        wp_send_json_error(__('Accès refusé.', 'prism-blocks'), 403);
    }
    $kit = sanitize_key(wp_unslash($_POST['kit'] ?? ''));
    $kits = prism_get_kits();
    if (!isset($kits[$kit])) {
        wp_send_json_error(__('Kit inconnu.', 'prism-blocks'), 400);
    }
    $page_id = wp_insert_post([
        'post_title'   => $kits[$kit]['name'],
        'post_content' => $kits[$kit]['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ]);
    if ($page_id) {
        update_option('page_on_front', $page_id);
        update_option('show_on_front', 'page');
        wp_send_json_success(['page_id' => $page_id]);
    } else {
        wp_send_json_error('Erreur création page');
    }
});

function prism_get_kits(): array {
    return [
        'magazine' => [
            'name'        => 'Magazine',
            'description' => 'Hero + catégories + grille. Layout classique pour site d\'actu et média.',
            'color'       => '#E2003A',
            'preview'     => 'https://l-investissement-locatif.fr/theme-preview.html',
            'content'     => '
<!-- wp:prism/post-hero {"postsPerPage":1} /-->
<!-- wp:prism/featured-posts {"title":"À la une","postsPerPage":5} /-->
<!-- wp:prism/category-section {"title":"VILLE","category":18,"postsPerPage":4,"accentColor":"#2563EB"} /-->
<!-- wp:prism/category-section {"title":"Fiscalité","category":15,"postsPerPage":4,"accentColor":"#059669"} /-->
<!-- wp:heading {"level":2,"className":"section-title"} -->
<h2 class="wp-block-heading section-title">Derniers articles</h2>
<!-- /wp:heading -->
<!-- wp:prism/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
        'tech' => [
            'name'        => 'Tech',
            'description' => 'Dark mode, grille dense, accents néon. Pour startups et crypto.',
            'color'       => '#0D1117',
            'preview'     => 'https://l-investissement-locatif.fr/kit-tech.html',
            'content'     => '
<!-- wp:prism/post-hero {"postsPerPage":1} /-->
<!-- wp:prism/post-grid {"postsPerPage":4,"columns":4} /-->
<!-- wp:prism/category-section {"postsPerPage":4,"accentColor":"#00FF41"} /-->
<!-- wp:prism/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
        'food' => [
            'name'        => 'Cuisine',
            'description' => 'Chaleureux, grille décalée, tons terre. Pour blogs culinaires et lifestyle.',
            'color'       => '#D35400',
            'preview'     => 'https://l-investissement-locatif.fr/kit-tech.html',
            'content'     => '
<!-- wp:prism/post-hero {"postsPerPage":1} /-->
<!-- wp:prism/post-grid {"postsPerPage":3,"columns":3} /-->
<!-- wp:prism/category-section {"postsPerPage":4,"accentColor":"#D35400"} /-->
<!-- wp:prism/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
    ];
}
