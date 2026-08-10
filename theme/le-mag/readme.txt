=== Le Mag ===
Contributors: skillsvault
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.8.1
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme magazine WordPress classique. Menu automatique, Site Kits en 1 clic, design responsive.

== Description ==
Le Mag est un theme WordPress classique conçu pour les sites d'actualite et les blogs.

* Menu principal automatique (categories par defaut)
* Site Kits : import de design en 1 clic (Magazine, Tech, Cuisine)
* Design responsive, optimise mobile
* Compatible Gutenberg
* Sidebar avec widgets
* Traduction prête (text-domain : lemag)

== Installation ==
1. Telechargez le fichier ZIP
2. Allez dans Apparence > Themes > Ajouter > Uploader
3. Activez le theme
4. Allez dans Le Mag > Site Kits pour importer un design

== Changelog ==
= 1.8.1 =
- Correction : fatal error TypeError dans lemag_reading_time() — accepte désormais int|WP_Post (single.php, single-video/audio/gallery.php).

= 1.8.0 =
- 17 templates (404, search, category, tag, author, date, front-page, attachment, page-full-width, page-landing, single-video/gallery/audio)
- Helpers magazine : breadcrumb, related posts, reading time, share buttons, trending, JSON-LD SEO
- Taxonomies personnalisées : Série, Format
- Mega Menu simplifié : 3 modèles prédéfinis
- Sous-menu En-tête & Pied de page
- Correction fatal error submenu_file

= 1.7.3 =
- Renommage prism → lemag
- Découplage thème/plugin (blocks.css source unique)
- Live preview dark mode Customizer
