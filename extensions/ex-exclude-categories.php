<?php
/**
 * Theme Name: BlankBlanc
 * Author: Naoki Yamamoto
 * Description: 特定のカテゴリーを除外する
 */

function bb_ex_exclude_categories() {
  new bbExcludeCategories();
}
add_action('init', 'bb_ex_exclude_categories');

class bbExcludeCategories
{
  public function __construct() {
    add_action('pre_get_posts', array($this, 'exclude_categoroes_posts'));
    add_filter('widget_categories_args', array($this, 'exclude_widget_category'), 10, 2);
    add_filter('widget_categories_dropdown_args', array($this, 'exclude_widget_category'), 10, 2);
    add_filter('widget_posts_args', array($this, 'exclude_widget_posts_args'), 10, 2);
    add_filter('widget_comments_args', array($this, 'exclude_widget_comments_args'), 10, 2);
    add_filter('getarchives_where', array($this, 'exclude_getarchives_where'), 10, 2);
    add_filter('get_calendar', array($this, 'get_calendar_excluding_posts'), 10, 1);
  }


  /**
   * 通常のカテゴリー一覧で除外する ID （カンマ区切りで複数対象）を設定
   * @param string $type  配列で必要な場合は array と記述
   * @return string/(array)  カンマ付き文字列または配列で返す
   */
  private function exclude_category_id($type = '') {
    global $bb_theme_config;
    $cat_id = str_replace(' ', '', $bb_theme_config['exclude_cat_id']);
    if (ARRAY_N == $type) {
      return explode(',', $cat_id);
    } else {
      return $cat_id;
    }
  }


  /**
   * 指定されたカテゴリーに属する投稿 ID を取得
   * @param string
   *   $cat_id,  カンマ区切りでカテゴリー ID を指定（デフォルトは除外対象カテゴリー）
   *   $type  配列で必要な場合は array と記述
   * @return string/(array)  カンマ付き文字列または配列で返す
   */
  private function get_categories_posts_id($type = '', $cat_id = '') {
    if (empty($cat_id)) {
      $cat_id = $this->exclude_category_id();
    }
    if (empty($cat_id)) {
      return '';
    }
    global $wpdb;
    $query = "SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ($cat_id) ORDER BY object_id ASC";
    $post_id = array();
    foreach ($wpdb->get_results($query) as $obj) {
      $post_id[] = $obj->object_id;
    }
    if (ARRAY_N == $type) {
      return $post_id;
    } else {
      return implode(',', $post_id);
    }
  }


  /**
   * 対象カテゴリーの投稿・フィードを一覧から除外
   */
  public function exclude_categoroes_posts($query) {
    if ($query->is_main_query() && !is_admin()) {
      if (!is_singular()) {
        // 除外するカテゴリ ID
        $query->set('category__not_in', $this->exclude_category_id(ARRAY_N));
      }
      if (!is_singular() || is_feed()) {
        // 除外するカテゴリ ID
        $query->set('category__not_in', $this->exclude_category_id(ARRAY_N));
      }
    }
  }


  /**
   * カテゴリ一覧ウィジェットから対象カテゴリーを除外
   */
  public function exclude_widget_category($cat_args) {
    // 除外するカテゴリ ID
    $cat_args['exclude_tree'] = $this->exclude_category_id();
    return $cat_args;
  }


  /**
   * 最近の投稿から対象カテゴリーの投稿を除外
   */
  public function exclude_widget_posts_args($args) {
    $args['category__not_in'] = $this->exclude_category_id(ARRAY_N);
    return $args;
  }


  /**
   * 最近のコメントから対象カテゴリーの投稿を除外
   */
  public function exclude_widget_comments_args($args) {
    $args['post__not_in'] = $this->get_categories_posts_id(ARRAY_N);
    return $args;
  }


  /**
   * アーカイブから対象カテゴリーを除外
   */
  public function exclude_getarchives_where($sql_where, $args) {
    global $wpdb;
    if ($posts_id = $this->get_categories_posts_id()) {
      return $sql_where . " AND ID NOT IN ({$posts_id})";
    }
    return $sql_where;
  }


  /**
   * 指定の投稿を除外したカレンダー出力
   * (original) general-template.php -> get_calendar()
   */
  public function get_calendar_excluding_posts() {
    // 除外する投稿 ID
    if ($categories_posts_id = $this->get_categories_posts_id()) {
      $exclude_posts_id = "AND ID NOT IN ({$categories_posts_id})";
    } else {
      $exclude_posts_id = '';
    }
    $initial = true;
    $echo = true;
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
    $key = md5( $m . $monthnum . $year );
    $cache = wp_cache_get( 'get_custom_calendar', 'calendar' );
    if ( $cache && is_array( $cache ) && isset( $cache[ $key ] ) ) {
      /** This filter is documented in wp-includes/general-template.php */
      $output = $cache[ $key ];
      if ( $echo ) {
        echo $output;
        return;
      }
      return $output;
    }
    if ( ! is_array( $cache ) ) {
      $cache = array();
    }
    // Quick check. If we have no posts at all, abort!
    if ( ! $posts ) {
      $gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1");
      if ( ! $gotsome ) {
        $cache[ $key ] = '';
        wp_cache_set( 'get_custom_calendar', $cache, 'calendar' );
        return;
      }
    }
    if ( isset( $_GET['w'] ) ) {
      $w = (int) $_GET['w'];
    }
    // week_begins = 0 stands for Sunday
    $week_begins = (int) get_option( 'start_of_week' );
    $ts = current_time( 'timestamp' );
    // Let's figure out when we are
    if ( ! empty( $monthnum ) && ! empty( $year ) ) {
      $thismonth = zeroise( intval( $monthnum ), 2 );
      $thisyear = (int) $year;
    } elseif ( ! empty( $w ) ) {
      // We need to get the month from MySQL
      $thisyear = (int) substr( $m, 0, 4 );
      //it seems MySQL's weeks disagree with PHP's
      $d = ( ( $w - 1 ) * 7 ) + 6;
      $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
    } elseif ( ! empty( $m ) ) {
      $thisyear = (int) substr( $m, 0, 4 );
      if ( strlen( $m ) < 6 ) {
        $thismonth = '01';
      } else {
        $thismonth = zeroise( (int) substr( $m, 4, 2 ), 2 );
      }
    } else {
      $thisyear = gmdate( 'Y', $ts );
      $thismonth = gmdate( 'm', $ts );
    }
    $unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
    $last_day = date_i18n( 't', $unixmonth );
    // Get the next and previous month and year with at least one post
    $previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
      FROM $wpdb->posts
      WHERE post_date < '$thisyear-$thismonth-01'
      AND post_type = 'post' AND post_status = 'publish'
      {$exclude_posts_id}
        ORDER BY post_date DESC
        LIMIT 1");
    $next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
      FROM $wpdb->posts
      WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
      AND post_type = 'post' AND post_status = 'publish'
      {$exclude_posts_id}
        ORDER BY post_date ASC
        LIMIT 1");
    /* translators: Calendar caption: 1: month name, 2: 4-digit year */
    $calendar_caption = _x('%1$s %2$s', 'calendar caption');
    $calendar_output = '<table id="wp-calendar">
    <caption>' . sprintf(
      $calendar_caption,
      $wp_locale->get_month( $thismonth ),
      date( 'Y', $unixmonth )
    ) . '</caption>
    <thead>
    <tr>';
    $myweek = array();
    for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
      $myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
    }
    foreach ( $myweek as $wd ) {
      $day_name = $initial ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
      $wd = esc_attr( $wd );
      $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
    }
    $calendar_output .= '
    </tr>
    </thead>
    <tfoot>
    <tr>';
    if ( $previous ) {
      $calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="' . get_month_link( $previous->year, $previous->month ) . '">&laquo; ' .
        $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) .
      '</a></td>';
    } else {
      $calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
    }
    $calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';
    if ( $next ) {
      $calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="' . get_month_link( $next->year, $next->month ) . '">' .
        $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) .
      ' &raquo;</a></td>';
    } else {
      $calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
    }
    $calendar_output .= '
    </tr>
    </tfoot>
    <tbody>
    <tr>';
    $daywithpost = array();
    // Get days with posts
    $dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH(post_date)
      FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
      AND post_type = 'post' AND post_status = 'publish'
      {$exclude_posts_id}
      AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
    if ( $dayswithposts ) {
      foreach ( (array) $dayswithposts as $daywith ) {
        $daywithpost[] = $daywith[0];
      }
    }
    // See how much we should pad in the beginning
    $pad = calendar_week_mod( date_i18n( 'w', $unixmonth ) - $week_begins );
    if ( 0 != $pad ) {
      $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr( $pad ) .'" class="pad">&nbsp;</td>';
    }
    $newrow = false;
    $daysinmonth = (int) date_i18n( 't', $unixmonth );
    for ( $day = 1; $day <= $daysinmonth; ++$day ) {
      if ( isset($newrow) && $newrow ) {
        $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
      }
      $newrow = false;
      if ( $day == gmdate( 'j', $ts ) &&
        $thismonth == gmdate( 'm', $ts ) &&
        $thisyear == gmdate( 'Y', $ts ) ) {
        $calendar_output .= '<td id="today">';
      } else {
        $calendar_output .= '<td>';
      }
      if ( in_array( $day, $daywithpost ) ) {
        // any posts today?
        $date_format = date_i18n( _x( 'F j, Y', 'daily archives date format' ), strtotime( "{$thisyear}-{$thismonth}-{$day}" ) );
        /* translators: Post calendar label. 1: Date */
        $label = sprintf( __( 'Posts published on %s' ), $date_format );
        $calendar_output .= sprintf(
          '<a href="%s" aria-label="%s">%s</a>',
          get_day_link( $thisyear, $thismonth, $day ),
          esc_attr( $label ),
          $day
        );
      } else {
        $calendar_output .= $day;
      }
      $calendar_output .= '</td>';
      if ( 6 == calendar_week_mod( date_i18n( 'w', mktime(0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
        $newrow = true;
      }
    }
    $pad = 7 - calendar_week_mod( date_i18n( 'w', mktime( 0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins );
    if ( $pad != 0 && $pad != 7 ) {
      $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr( $pad ) .'">&nbsp;</td>';
    }
    $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";
    $cache[ $key ] = $calendar_output;
    wp_cache_set( 'get_custom_calendar', $cache, 'calendar' );
    if ( $echo ) {
      /**
       * Filters the HTML calendar output.
       *
       * @since 3.0.0
       *
       * @param string $calendar_output HTML output of the calendar.
       */
      echo $calendar_output;
      return;
    }
    /** This filter is documented in wp-includes/general-template.php */
    return $calendar_output;
  }
}
