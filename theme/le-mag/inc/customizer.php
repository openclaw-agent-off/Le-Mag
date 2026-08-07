<?php
/**
 * Prism — Customizer
 */
defined('ABSPATH') || exit;

add_action('customize_register', function (WP_Customize_Manager $wp_customize): void {

    // === COULEURS ===
    $wp_customize->add_section('prism_colors', [
        'title'    => __('Couleurs', 'prism'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('prism_primary_color', [
        'default'           => '#E2003A',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'prism_primary_color', [
        'label'    => __('Couleur principale', 'prism'),
        'section'  => 'prism_colors',
    ]));

    $wp_customize->add_setting('prism_bg_color', [
        'default'           => '#FFFFFF',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'prism_bg_color', [
        'label'    => __('Fond', 'prism'),
        'section'  => 'prism_colors',
    ]));

    $wp_customize->add_setting('prism_text_color', [
        'default'           => '#111111',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'prism_text_color', [
        'label'    => __('Texte', 'prism'),
        'section'  => 'prism_colors',
    ]));

    // === TYPOGRAPHIE ===
    $wp_customize->add_section('prism_typo', [
        'title'    => __('Typographie', 'prism'),
        'priority' => 40,
    ]);

    $wp_customize->add_setting('prism_font_body', [
        'default'           => 'inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('prism_font_body', [
        'label'   => __('Police du texte', 'prism'),
        'section' => 'prism_typo',
        'type'    => 'select',
        'choices' => [
            'inter'    => 'Inter (moderne)',
            'system'   => 'Système (par défaut)',
            'georgia'  => 'Georgia (serif)',
            'roboto'   => 'Roboto',
        ],
    ]);

    $wp_customize->add_setting('prism_font_heading', [
        'default'           => 'playfair',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('prism_font_heading', [
        'label'   => __('Police des titres', 'prism'),
        'section' => 'prism_typo',
        'type'    => 'select',
        'choices' => [
            'playfair' => 'Playfair Display',
            'inter'    => 'Inter',
            'system'   => 'Système',
            'georgia'  => 'Georgia',
        ],
    ]);

    // === OPTIONS ===
    $wp_customize->add_section('prism_options', [
        'title'    => __('Options', 'prism'),
        'priority' => 50,
    ]);

    $wp_customize->add_setting('prism_dark_mode', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('prism_dark_mode', [
        'label'   => __('Mode sombre', 'prism'),
        'section' => 'prism_options',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('prism_sticky_header', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('prism_sticky_header', [
        'label'   => __('Header fixe (sticky)', 'prism'),
        'section' => 'prism_options',
        'type'    => 'checkbox',
    ]);
});

// === CSS DYNAMIQUE ===
add_action('wp_head', function (): void {
    $primary = get_theme_mod('prism_primary_color', '#E2003A');
    $bg      = get_theme_mod('prism_bg_color', '#FFFFFF');
    $text    = get_theme_mod('prism_text_color', '#111111');
    $dark    = get_theme_mod('prism_dark_mode', false);
    $sticky  = get_theme_mod('prism_sticky_header', true);

    $body_font = get_theme_mod('prism_font_body', 'inter');
    $head_font = get_theme_mod('prism_font_heading', 'playfair');

    $body_map = [
        'inter'   => 'Inter, sans-serif',
        'system'  => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        'georgia' => 'Georgia, "Times New Roman", serif',
        'roboto'  => 'Roboto, sans-serif',
    ];
    $head_map = [
        'playfair' => '"Playfair Display", Georgia, serif',
        'inter'    => 'Inter, sans-serif',
        'system'   => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        'georgia'  => 'Georgia, "Times New Roman", serif',
    ];

    $body_family = $body_map[$body_font] ?? 'Inter, sans-serif';
    $head_family = $head_map[$head_font] ?? '"Playfair Display", Georgia, serif';

    echo '<style id="prism-dynamic-css">';
    echo ":root{--red:{$primary};--black:{$text};--white:{$bg}}";
    echo "body{font-family:{$body_family};color:{$text};background:{$bg}}";
    echo ".section-title,.hero h2,.article-content h1,.article-content h2,.sidebar-box h3,.site-footer h4{font-family:{$head_family}}";
    if ($dark) {
        echo ":root{--red:{$primary};--black:#eee;--white:#111;--gray:#999;--gray-light:#1a1a1a;--border:#333}";
    }
    if (!$sticky) {
        echo ".site-header{position:static}";
    }
    echo '</style>';
}, 99);

// === PREVIEW LIVE JS ===
add_action('customize_preview_init', function (): void {
    wp_enqueue_script('prism-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        ['customize-preview'], PRISM_VERSION, true);
});
