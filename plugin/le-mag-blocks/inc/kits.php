<?php
/**
 * Le Mag Blocks — Site Kits (templates magazine prêts à importer)
 */
defined('ABSPATH') || exit;

function lemag_kits_page() {
    $kits = lemag_get_kits();
    ?>
    <div class="wrap">
    <h1>Le Mag — Kits de site</h1>
    <?php lemag_admin_tabs('kits'); ?>
    <div class="lemag-admin-wrap">
        <div class="lemag-admin-hero">
            <div class="lemag-admin-logo">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="10" fill="#E2003A"/><path d="M12 25V11l7 9 7-9v14" stroke="#fff" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div>
                    <h1>Le Mag</h1>
                    <p>Templates magazine — importez une page d'accueil complète en 1 clic</p>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['imported'])): ?>
        <div class="lemag-notice lemag-notice-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
            Page créée et définie comme page d'accueil.
        </div>
        <?php endif; ?>

        <div class="lemag-kits-grid">
            <?php foreach ($kits as $slug => $kit): ?>
            <div class="lemag-kit-card">
                <div class="lemag-kit-preview" style="background:<?php echo esc_attr($kit['color']); ?>">
                    <div class="lemag-kit-badge"><?php echo esc_html($kit['name']); ?></div>
                    <div class="lemag-kit-preview-lines">
                        <span></span><span></span><span></span>
                    </div>
                </div>
                <div class="lemag-kit-body">
                    <h3><?php echo esc_html($kit['name']); ?></h3>
                    <p><?php echo esc_html($kit['description']); ?></p>
                    <div class="lemag-kit-actions">
                        <button class="lemag-btn lemag-btn-primary lemag-import-kit" data-kit="<?php echo esc_attr($slug); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Importer
                        </button>
                        <a href="<?php echo esc_url($kit['preview']); ?>" class="lemag-btn lemag-btn-ghost" target="_blank" rel="noopener noreferrer">
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
    .lemag-admin-wrap{max-width:1080px;margin:32px 0 0 0;font-family:Inter,-apple-system,BlinkMacSystemFont,sans-serif}
    .lemag-admin-hero{margin-bottom:40px}
    .lemag-admin-logo{display:flex;align-items:center;gap:20px}
    .lemag-admin-logo h1{font-size:1.6rem;font-weight:800;margin:0;color:#1e1e1e;line-height:1.2}
    .lemag-admin-logo p{font-size:.88rem;color:#6c6c6c;margin:4px 0 0}
    .lemag-notice{display:flex;align-items:center;gap:10px;padding:14px 18px;border-radius:10px;margin-bottom:28px;font-size:.88rem;font-weight:500}
    .lemag-notice-success{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
    .lemag-kits-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px}
    .lemag-kit-card{background:#fff;border:1px solid #e2e4e7;border-radius:14px;overflow:hidden;transition:transform .2s,box-shadow .2s}
    .lemag-kit-card:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.08)}
    .lemag-kit-preview{height:180px;display:flex;align-items:flex-end;justify-content:flex-start;padding:20px;position:relative;overflow:hidden}
    .lemag-kit-badge{background:rgba(255,255,255,.2);backdrop-filter:blur(6px);color:#fff;padding:6px 14px;border-radius:6px;font-size:.8rem;font-weight:700;letter-spacing:.03em}
    .lemag-kit-preview-lines{position:absolute;right:-20px;bottom:-20px;display:flex;flex-direction:column;gap:4px;opacity:.15}
    .lemag-kit-preview-lines span{display:block;background:#fff;height:3px;border-radius:2px}
    .lemag-kit-preview-lines span:nth-child(1){width:80px}
    .lemag-kit-preview-lines span:nth-child(2){width:110px}
    .lemag-kit-preview-lines span:nth-child(3){width:60px}
    .lemag-kit-body{padding:24px}
    .lemag-kit-body h3{font-size:1.05rem;font-weight:700;margin:0 0 6px;color:#1e1e1e}
    .lemag-kit-body p{font-size:.82rem;color:#6c6c6c;margin:0 0 20px;line-height:1.5}
    .lemag-kit-actions{display:flex;gap:8px}
    .lemag-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .15s;border:none}
    .lemag-btn-primary{background:#E2003A;color:#fff;box-shadow:0 1px 2px rgba(226,0,58,.3)}
    .lemag-btn-primary:hover{background:#c80033;color:#fff}
    .lemag-btn-primary:disabled{opacity:.6;cursor:not-allowed}
    .lemag-btn-ghost{background:transparent;color:#50575e;border:1px solid #dcdcde}
    .lemag-btn-ghost:hover{background:#f0f0f1;color:#1e1e1e}
    </style>

    <script>
    jQuery(function($){
        $('.lemag-import-kit').on('click', function(){
            var btn = $(this), kit = btn.data('kit');
            btn.prop('disabled',true).html('<span style="display:inline-block;animation:spin .6s linear infinite">⟳</span> Import...');
            $.post(ajaxurl, {action:'lemag_import_kit',kit:kit,nonce:'<?php echo wp_create_nonce("lemag-kits"); ?>'}, function(r){
                if(r.success){ window.location = '?page=lemag-dashboard&tab=kits&imported=1'; }
                else { alert('Erreur : '+r.data); btn.prop('disabled',false).html('Réessayer'); }
            });
        });
    });
    </script>
    </div><!-- .wrap -->
    <?php
}

// Ajax import
add_action('wp_ajax_lemag_import_kit', function () {
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'] ?? '', 'lemag-kits')) {
        wp_send_json_error(__('Accès refusé.', 'lemag-blocks'), 403);
    }
    $kit = sanitize_key(wp_unslash($_POST['kit'] ?? ''));
    $kits = lemag_get_kits();
    if (!isset($kits[$kit])) {
        wp_send_json_error(__('Kit inconnu.', 'lemag-blocks'), 400);
    }
    $page_id = wp_insert_post([
        'post_title'   => $kits[$kit]['name'],
        'post_content' => wp_slash($kits[$kit]['content']),
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

function lemag_get_kits(): array {
    return [
        'magazine' => [
            'name'        => 'Magazine',
            'description' => 'Headline + catégories alternées + grilles variées.',
            'color'       => '#E2003A',
            'preview'     => 'https://l-investissement-locatif.fr/theme-preview.html',
            'content'     => '
<!-- wp:lemag/magazine-headline /-->

<!-- wp:lemag/category-section {"title":"VILLE","category":18,"postsPerPage":4,"accentColor":"#2563EB"} /-->

<!-- wp:lemag/post-grid {"postsPerPage":4,"columns":4} /-->

<!-- wp:lemag/featured-posts {"title":"À la une","postsPerPage":5} /-->

<!-- wp:lemag/post-hero {"postsPerPage":1,"offset":2} /-->

<!-- wp:lemag/category-section {"title":"Fiscalité","category":15,"postsPerPage":4,"accentColor":"#059669"} /-->

<!-- wp:lemag/post-grid {"postsPerPage":6,"columns":3,"offset":3} /-->
',
        ],
        'tech' => [
            'name'        => 'Tech',
            'description' => 'Dark mode, grille dense, accents néon. Pour startups et crypto.',
            'color'       => '#0D1117',
            'preview'     => 'https://l-investissement-locatif.fr/kit-tech.html',
            'content'     => '
<!-- wp:lemag/post-hero {"postsPerPage":1} /-->
<!-- wp:lemag/post-grid {"postsPerPage":4,"columns":4} /-->
<!-- wp:lemag/category-section {"postsPerPage":4,"accentColor":"#00FF41"} /-->
<!-- wp:lemag/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
        'food' => [
            'name'        => 'Cuisine',
            'description' => 'Chaleureux, grille décalée, tons terre. Pour blogs culinaires et lifestyle.',
            'color'       => '#D35400',
            'preview'     => 'https://l-investissement-locatif.fr/kit-tech.html',
            'content'     => '
<!-- wp:lemag/post-hero {"postsPerPage":1} /-->
<!-- wp:lemag/post-grid {"postsPerPage":3,"columns":3} /-->
<!-- wp:lemag/category-section {"postsPerPage":4,"accentColor":"#D35400"} /-->
<!-- wp:lemag/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
        'magazine-2' => [
            'name'        => 'Magazine 2',
            'description' => 'Multi-sections : headline, global, travel, most popular, + grilles.',
            'color'       => '#1A1A2E',
            'preview'     => 'https://l-investissement-locatif.fr/theme-preview.html',
            'content'     => '
<!-- wp:lemag/magazine-headline /-->

<!-- wp:lemag/featured-posts {"title":"Global info","postsPerPage":4} /-->

<!-- wp:lemag/category-section {"title":"Travel Tips","postsPerPage":3,"accentColor":"#E2003A"} /-->

<!-- wp:lemag/post-grid {"postsPerPage":3,"columns":3} /-->

<!-- wp:lemag/featured-posts {"title":"Most Popular","postsPerPage":5} /-->

<!-- wp:lemag/category-section {"title":"Health","postsPerPage":4,"accentColor":"#059669"} /-->

<!-- wp:heading {"level":2,"className":"section-title"} -->
<h2 class="wp-block-heading section-title">Derniers articles</h2>
<!-- /wp:heading -->

<!-- wp:lemag/post-grid {"postsPerPage":6,"columns":3,"offset":1} /-->
',
        ],
    ];
}
