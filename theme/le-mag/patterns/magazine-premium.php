<?php
/**
 * Title: Magazine Premium — Page d'accueil
 * Slug: lemag/magazine-premium
 * Categories: lemag-magazine
 * Description: Hero dynamique + grille articles — 100% blocs natifs WordPress
 */
?>
<!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
<div class="wp-block-query alignwide">
<!-- wp:post-template {"layout":{"type":"grid","columnCount":1}} -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:0;padding-left:0;padding-right:0">
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"spacing":{"margin":{"bottom":"0"}}},"className":"is-style-default"} /-->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">
<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
<!-- wp:post-title {"level":2,"isLink":true,"style":{"typography":{"fontSize":"1.3rem"}}} /-->
<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":20} /-->
<!-- wp:post-date /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- /wp:post-template -->
</div>
<!-- /wp:query -->

<!-- wp:heading {"level":2,"className":"section-title"} -->
<h2 class="wp-block-heading section-title">Derniers articles</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":2,"query":{"perPage":6,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
<div class="wp-block-query alignwide">
<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10"} /-->
<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"medium"} /-->
<!-- wp:post-excerpt {"excerptLength":15} /-->
<!-- wp:post-date /-->
<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
