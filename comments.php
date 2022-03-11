<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: comment
 * Description: コメント
 */
?>

<?php
if (post_password_required()) {
  return;
}
?>
<?php if ((comments_open() || get_comments_number()) && post_type_supports(get_post_type(), 'comments')) : ?>
  <section id="comments" class="bb-form-style">
    <?php if (have_comments()) : ?>
      <div id="comments-title">コメント一覧</div>
      <ol class="comment-list">
        <?php wp_list_comments(array('callback' => 'bb_theme_comment')); ?>
      </ol>
    <?php endif; ?>

    <?php
    if (comments_open()) {
      /**
       * コメントフォーム
       */
      // デフォルト値取得
      $commenter = wp_get_current_commenter();
      $req = get_option('require_name_email');
      $aria_req = ($req ? ' aria-required="true"' : '');
      $req_text = '<span class="required">（必須）</span>';

      // $fields 設定
      $fields = array(
        'author' => '<p class="inputtext">' . '<label for="author">' . '名前'
          . ($req ? $req_text : null) . '</label>' .
          '<input id="author" name="author" type="text" value="'
          . esc_attr($commenter['comment_author']) . '"' . $aria_req . ' /></p>' . "\n",

        'email'  => '<p class="inputtext"><label for="email">' . 'メールアドレス'
          . ($req ? $req_text : null) . '</label>' .
          '<span class="not-publish">※メールアドレスは公開されません</span>' .
          '<input id="email" name="email" type="text" value="'
          . esc_attr($commenter['comment_author_email']) . '"' . $aria_req . ' /></p>' . "\n",

        'url'    => '<p class="inputtext"><label for="url">' . 'ウェブサイト'
          . '</label>' .
          '<input id="url" name="url" type="text" value="'
          . esc_attr($commenter['comment_author_url']) . '"' . ' /></p>' . "\n",
        );

      // $comment_field 設定
      $comment_field = '<p class="comment-form-comment"><label for="comment">' . 'コメント'
        . $req_text . '</label>'
        . '<textarea id="comment" name="comment" aria-required="true"></textarea></p>' . "\n";


      // $comment_notes_before 設定
      $comment_notes_before = null;
      $comment_notes_after = null;

      // $args 設定
      $args = array(
        'fields'               => apply_filters('comment_form_default_fields', $fields),
        'cancel_reply_link'    => 'キャンセル',
        'comment_field'        => $comment_field,
        'comment_notes_before' => $comment_notes_before,
        'comment_notes_after'  => $comment_notes_after
      );
      comment_form($args);
    }
  ?>
  </section>
<?php endif;
