<?php
/**
 * Le Mag — Helpers magazine (breadcrumb, related posts, lecture time, share, trending).
 */
defined('ABSPATH') || exit;

/**
 * Temps de lecture estimé en minutes.
 */
// FIX: accepte int|WP_Post pour éviter le TypeError quand un objet WP_Post est passé (single.php, single-video/audio/gallery.php)
function lemag_reading_time(int|WP_Post $post = 0): int {
    $post_id  = ($post instanceof WP_Post) ? $post->ID : $post;
    $post_obj = get_post($post_id ?: get_the_ID());
    if (!$post_obj) return 1;
    $words = str_word_count(wp_strip_all_tags($post_obj->post_content));
    return max(1, (int) ceil($words / 200));
}

/**
 * Fil d'Ariane structuré (données JSON pour SEO + affichage HTML).
 */
function lemag_breadcrumb(): string {
    if (is_front_page()) return '';
    $items = [['name' => __('Accueil', 'lemag'), 'url' => home_url('/')]];
    if (is_singular()) {
        $cats = get_the_category();
        if ($cats) {
            $cat = $cats[0];
            $items[] = ['name' => $cat->name, 'url' => get_category_link($cat)];
        }
        $items[] = ['name' => get_the_title(), 'url' => get_permalink()];
    } elseif (is_category()) {
        $items[] = ['name' => single_cat_title('', false), 'url' => ''];
    } elseif (is_tag()) {
        $items[] = ['name' => single_tag_title('', false), 'url' => ''];
    } elseif (is_author()) {
        $items[] = ['name' => get_the_author(), 'url' => ''];
    } elseif (is_date()) {
        $items[] = ['name' => get_the_date('F Y'), 'url' => ''];
    } elseif (is_search()) {
        $items[] = ['name' => sprintf(__('Recherche : %s', 'lemag'), get_search_query()), 'url' => ''];
    } elseif (is_404()) {
        $items[] = ['name' => __('404', 'lemag'), 'url' => ''];
    }
    ob_start();
    echo '<nav class="breadcrumb" aria-label="breadcrumb"><ol>';
    $total = count($items);
    foreach ($items as $i => $item) {
        $last = $i === $total - 1;
        echo '<li>';
        if (!$last && $item['url']) echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a>';
        else echo '<span>' . esc_html($item['name']) . '</span>';
        if (!$last) echo ' <span class="sep">›</span> ';
        echo '</li>';
    }
    echo '</ol></nav>';
    return ob_get_clean();
}

/**
 * Articles liés (par catégorie + tag, fallback par catégorie).
 */
function lemag_related_posts(int $count = 3): array {
    $post_id = get_the_ID();
    $cats = get_the_category($post_id);
    if (!$cats) return [];
    $cat_ids = wp_list_pluck($cats, 'term_id');
    $tags = get_the_tags($post_id);
    $tag_ids = $tags ? wp_list_pluck($tags, 'term_id') : [];
    $args = [
        'posts_per_page'      => $count,
        'post__not_in'        => [$post_id],
        'ignore_sticky_posts' => 1,
        'orderby'             => 'rand',
    ];
    if ($tag_ids) {
        $args['tax_query'] = [
            'relation' => 'OR',
            ['taxonomy' => 'category', 'terms' => $cat_ids],
            ['taxonomy' => 'post_tag', 'terms' => $tag_ids],
        ];
    } else {
        $args['category__in'] = $cat_ids;
    }
    $query = new WP_Query($args);
    return $query->posts;
}

/**
 * Articles tendance (trending) basés sur les commentaires + récence.
 */
function lemag_trending_posts(int $count = 5): array {
    $query = new WP_Query([
        'posts_per_page'      => $count,
        'ignore_sticky_posts' => 1,
        'meta_key'            => '_lemag_trending',
        'orderby'             => ['meta_value_num' => 'DESC', 'date' => 'DESC'],
    ]);
    if (!$query->have_posts()) {
        $query = new WP_Query([
            'posts_per_page'      => $count,
            'ignore_sticky_posts' => 1,
            'orderby'             => 'comment_count',
            'order'              => 'DESC',
        ]);
    }
    return $query->posts;
}

/**
 * Boutons de partage social.
 */
function lemag_share_buttons(): string {
    // Respecte les réglages de l'admin (Le Mag → En-tête & Pied de page → Partage social)
    $hf = function_exists('lemag_hf_settings') ? lemag_hf_settings() : [];
    if (!empty($hf) && empty($hf['share_enabled'])) return '';

    $url = get_permalink();
    $title = rawurlencode(get_the_title());
    $all_links = [
        'facebook'  => "https://www.facebook.com/sharer/sharer.php?u=" . rawurlencode($url),
        'twitter'   => "https://twitter.com/intent/tweet?text={$title}&url=" . rawurlencode($url),
        'linkedin'  => "https://www.linkedin.com/sharing/share-offsite/?url=" . rawurlencode($url),
        'whatsapp'  => "https://wa.me/?text={$title}%20" . rawurlencode($url),
        'telegram'  => "https://t.me/share/url?url=" . rawurlencode($url) . "&text={$title}",
        'email'     => "mailto:?subject={$title}&body=" . rawurlencode($url),
    ];
    // Filtre selon les réglages admin ; si pas de réglages, tout est activé par défaut.
    if (!empty($hf)) {
        $links = [];
        foreach ($all_links as $network => $href) {
            $key = 'share_' . $network;
            if (!empty($hf[$key])) $links[$network] = $href;
        }
    } else {
        $links = $all_links;
    }
    if (empty($links)) return '';
    $icons = [
        'facebook'  => 'M9 8H6v4h3v8h4v-8h3l1-4h-4V5.5c0-1 .3-1.5 1.7-1.5H17V0h-3C9.7 0 9 2.4 9 5z',
        'twitter'   => 'M22 5.8c-.7.3-1.5.6-2.4.7.9-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1A4.1 4.1 0 0011 9c0 .3 0 .6.1.9-3.4-.2-6.4-1.8-8.4-4.3-.4.6-.6 1.3-.6 2.1 0 1.4.7 2.7 1.8 3.4-.7 0-1.3-.2-1.9-.5v.1c0 2 1.4 3.7 3.3 4-.3.1-.7.2-1.1.2-.3 0-.5 0-.8-.1.5 1.6 2.1 2.8 3.9 2.8a8.2 8.2 0 01-5.1 1.8H2c1.8 1.2 4 1.9 6.3 1.9 7.5 0 11.7-6.3 11.7-11.7v-.5c.8-.6 1.5-1.3 2-2.1z',
        'linkedin'  => 'M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14zM8.3 18.3V9.9H5.6v8.4h2.7zM6.9 8.7c.9 0 1.6-.7 1.6-1.6S7.8 5.5 6.9 5.5 5.3 6.2 5.3 7.1s.7 1.6 1.6 1.6zm11.4 9.6v-4.6c0-2.5-1.3-3.6-3.1-3.6-1.4 0-2.1.8-2.4 1.3V9.9h-2.7v8.4h2.7v-4.7c0-1.3.8-1.9 1.7-1.9.8 0 1.5.5 1.5 1.9v4.7h2.6z',
        'whatsapp'  => 'M12 2a10 10 0 00-8.6 15l-1.4 5 5.1-1.3A10 10 0 1012 2zm5.4 13.8c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.1-.7s-3.4-3.1-3.5-3.3-1-1.5-1-2.8.7-1.9.9-2.2c.2-.2.4-.3.6-.3h.4c.2 0 .4 0 .5.4l.7 1.7c.1.2 0 .4 0 .5l-.4.5-.3.3c-.1.1-.2.2-.1.4l.7 1.2c.6.9 1 1.4 1.7 1.9.5.3.8.4 1 .4.2 0 .4-.2.5-.4l.7-.8c.2-.3.4-.2.6-.1l1.7.8c.2.1.4.2.4.3.1.1.1.6-.1 1.2z',
        'telegram'  => 'M21.9 4.3l-3.3 15.5c-.2 1-.9 1.3-1.7.8l-4.7-3.5-2.3 2.2c-.3.3-.5.5-1 .5l.3-4.8 8.7-7.9c.4-.3-.1-.5-.6-.2L6.3 13.2l-4.6-1.4c-1-.3-1-1 .2-1.5l18-7c.8-.3 1.6.2 1.3 1z',
        'email'     => 'M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z',
    ];
    ob_start();
    echo '<div class="lemag-share">';
    foreach ($links as $network => $href) {
        $icon = $icons[$network] ?? '';
        printf(
            '<a href="%1$s" target="_blank" rel="noopener noreferrer" class="lemag-share-btn lemag-share-%2$s" aria-label="%3$s" onclick="window.open(this.href,\'\',\'width=600,height=400\');return false;"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="%4$s"/></svg></a>',
            esc_url($href),
            esc_attr($network),
            esc_attr(ucfirst($network)),
            $icon
        );
    }
    echo '</div>';
    return ob_get_clean();
}

/**
 * Détermine si un article a un format média spécifique (vidéo, galerie, audio).
 */
function lemag_get_post_format_type(int $post_id = 0): string {
    $post = get_post($post_id ?: get_the_ID());
    if (!$post) return 'standard';
    $content = $post->post_content;
    if (has_block('core/video', $content) || strpos($content, 'youtube.com/embed') !== false || strpos($content, 'vimeo.com') !== false) return 'video';
    if (has_block('core/gallery', $content) || has_block('core/image', $content) && substr_count($content, '<!-- wp:core/image') >= 3) return 'gallery';
    if (has_block('core/audio', $content) || has_block('core/file', $content)) return 'audio';
    return 'standard';
}

/**
 * Données structurées JSON-LD pour article (SEO).
 */
function lemag_json_ld(): string {
    if (!is_singular('post')) return '';
    $post_id = get_the_ID();
    $author = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
    $image = get_the_post_thumbnail_url($post_id, 'full');
    $data = [
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'headline'         => get_the_title($post_id),
        'datePublished'    => get_the_date('c', $post_id),
        'dateModified'     => get_the_modified_date('c', $post_id),
        'author'           => [['@type' => 'Person', 'name' => $author]],
        'publisher'        => ['@type' => 'Organization', 'name' => get_bloginfo('name')],
        'mainEntityOfPage' => get_permalink($post_id),
        'wordCount'        => str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id))),
        'timeRequired'     => 'PT' . lemag_reading_time($post_id) . 'M',
    ];
    if ($image) $data['image'] = [$image];
    return '<script type="application/ld+json">' . wp_json_encode($data) . '</script>';
}
