<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Template: inc-linkpages
 * Description: 投稿／固定ページ内のページ送り
 */

if (!post_password_required()) {
  wp_link_pages(array(
    'next_or_number' => 'number',
    'before' => '<div class="link-pages"><span class="heading">ページ</span>' . "\n",
    'after' => "</div>\n",
    'separator' => "\n",
    'pagelink' => '<span class="page">%</span>'
  ));
}
