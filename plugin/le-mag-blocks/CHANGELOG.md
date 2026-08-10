# Changelog

## 1.8.1
- Thème : correction fatal error TypeError dans `lemag_reading_time()` — la signature accepte désormais `int|WP_Post` (corrige single.php, single-video/audio/gallery.php qui passaient un objet WP_Post au lieu d'un ID).

## 1.8.0
- Blocs dynamiques : rendu en direct dans l'éditeur Gutenberg via `wp.serverSideRender` (au lieu de placeholders texte).
- Mega Menu simplifié : 3 modèles prédéfinis (Articles récents, Sous-catégories, Aucun) sélectionnables dans l'éditeur de menus WordPress.
- Suppression du CPT `lemag_mega_menu` et du Walker admin custom (interface plus intuitive).
- Nouveau sous-menu « En-tête & Pied de page » : sticky, reading progress, secondary nav, disposition logo, newsletter, colonnes footer, réseaux sociaux.
- Thème : 17 templates (ajout de 404, search, category, tag, author, date, front-page, attachment, page-full-width, page-landing, single-video/gallery/audio).
- Thème : helpers magazine (breadcrumb, related posts, reading time, share buttons, trending, JSON-LD SEO).
- Thème : taxonomies personnalisées (Série, Format) avec template loader automatique.
- Correction : fatal error sur `submenu_file` quand la valeur est `null`.

## 1.7.3
- Renommage complet `prism` → `lemag` (namespaces de blocs, options, constantes, text domains, classes CSS, filtres, nonces).
- Fichier principal du plugin renommé `prism-blocks.php` → `le-mag-blocks.php`.
- Découplage thème/plugin : `blocks.css` est désormais la source unique des styles de blocs, chargée côté front via `wp_enqueue_scripts`.
- Suppression de la duplication CSS des blocs dans `main.css` du thème.
- Live preview du mode sombre dans le Customizer (modifie les variables CSS au lieu de recharger la page).
- Suppression des modules redondants `font-library` et `menu-plus` (typo et sticky gérés par le Customizer).
- Suppression du doublon `register_nav_menus(['secondary'])` dans le plugin (géré par le thème).
- Fallback `is_post_publicly_viewable()` pour WordPress < 5.7.
- Vérification `current_user_can('edit_theme_options')` sur `wp_update_nav_menu_item`.
- `wp_slash()` appliqué au `post_content` des kits lors de l'import.
- Suppression du fichier orphelin `src/post-hero.json`.
- Ajout de `package.json`, `.gitignore`, `LICENSE.md`, `CHANGELOG.md`.
