<?php
if (post_password_required()) return;
?>

<div id="comments" class="comments-area">
  <?php if (have_comments()): ?>
    <h3 class="comments-title">
      <?php
      $count = get_comments_number();
      printf(_n('%d commentaire', '%d commentaires', $count, 'prism'), $count);
      ?>
    </h3>
    <ol class="comment-list">
      <?php wp_list_comments(['style' => 'ol', 'avatar_size' => 48]); ?>
    </ol>
    <?php the_comments_navigation(); ?>
  <?php endif; ?>

  <?php if (!comments_open() && get_comments_number()): ?>
    <p><?php esc_html_e('Commentaires fermés.', 'prism'); ?></p>
  <?php endif; ?>

  <?php if (comments_open()): ?>
    <?php comment_form(); ?>
  <?php endif; ?>
</div>
