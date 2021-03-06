<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPostServices
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.02
 */
class WpPostServices extends GlobalServices
{
  
  public function __construct()
  {}

  /**
   * @param string $file
   * @param string $line
   * @param array $params
   * @param string $viaWpQuery
   * @param string $wpPostType
   * @return array
   */
  public function getArticles($file, $line, $params=array(), $viaWpQuery=false, $wpPostType='WpPostMission')
  {
    $args = array(
      'orderby'=> 'name',
      'order'=>'ASC',
      'posts_per_page'=>-1,
      'post_type'=>'post',
      'tax_query' => array(
        array(
          'taxonomy' => 'post_tag',
          'field' => 'slug',
          'terms' => array('mission', 'survivant', 'news'),
        )
      )
    );
    if (!empty($params)) {
      foreach ($params as $key => $value) {
        $args[$key] = $value;
      }
    }
    if ($viaWpQuery) {
      $wpQuery = new WP_Query($args);
      $posts_array = $wpQuery->posts;
    } else {
      $posts_array = get_posts($args);
    }
    $WpPosts = array();
    if (!empty($posts_array)) {
      foreach ($posts_array as $post) {
        $tags = wp_get_post_tags($post->ID);
        $wpPostType = $this->getPostTypeFromTags($tags);
        $WpPosts[] = WpPost::convertElement($post, $wpPostType);
      }
    }
    return $WpPosts;
  }
  private function getPostTypeFromTags($tags)
  {
    $wpPostType = '';
    if (!empty($tags)) {
      foreach ($tags as $WpTerm) {
        if ($WpTerm->slug == 'mission') {
          $wpPostType = 'WpPostMission';
        } elseif ($WpTerm->slug == 'news') {
          $wpPostType = 'WpPostNews';
        } elseif ($WpTerm->slug == 'survivant') {
          $wpPostType = 'WpPostSurvivor';
        }
      }
    }
    return $wpPostType;
  }

  /**
   * @param int $pageId
   * @param int $limit
   * @return array
   */
  public function getChildPagesByParentId($pageId, $limit = -1)
  {
    global $post;
    $pages = array();
    $args = array(
      'orderby'=> 'name',
      'order'=>'ASC',
      'post_type' => 'page',
      'post_parent' => $pageId,
      'posts_per_page' => $limit
   );
    $the_query = new WP_Query($args);
    while ($the_query->have_posts()) {
      $the_query->the_post();
      $pages[] = WpPost::convertElement($post, 'WpPost');
    }
    wp_reset_postdata();
    return $pages;
  }
}
