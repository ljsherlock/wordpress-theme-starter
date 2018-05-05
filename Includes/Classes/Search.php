<?php

namespace Redwire;

class WPSearch
{

    public static function setup()
    {
        // Ajax Search
        add_action('wp_ajax_fps_search_ajax', array(__CLASS__, 'search_ajax') );
        add_action('wp_ajax_nopriv_fps_search_ajax', array(__CLASS__, 'search_ajax') );
    }

    /**
     * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
     * @param  [query-part/string]      $where    [the initial "where" part of the search query]
     * @param  [object]                 $wp_query []
     * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
     * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
     * credits to Vincent Zurczak for the base query structure/spliting tags section
     */
    public static function advanced_custom_search( $where, &$wp_query ) {
        global $wpdb;

        if ( empty( $where ))
            return $where;

        // get search expression
        $terms = $wp_query->query_vars[ 's' ];

        // explode search expression to get search terms
        $exploded = explode( ' ', $terms );
        if( $exploded === FALSE || count( $exploded ) == 0 )
            $exploded = array( 0 => $terms );

        // reset search in order to rebuilt it as we whish
        $where = '';

        // get searcheable_acf, a list of advanced custom fields you want to search content in
        $list_searcheable_acf = ACF::list_searcheable_fields();
        foreach( $exploded as $tag ) :
            $where .= "
              AND (
                (wp_posts.post_title LIKE '%$tag%')
                OR (wp_posts.post_content LIKE '%$tag%')
                OR EXISTS (
                  SELECT * FROM wp_postmeta
    	              WHERE post_id = wp_posts.ID
    	                AND (";
            foreach ($list_searcheable_acf as $searcheable_acf) :
              if ($searcheable_acf == $list_searcheable_acf[0]):
                $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
              else :
                $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
              endif;
            endforeach;
    	        $where .= ")
                )
                OR EXISTS (
                  SELECT * FROM wp_comments
                  WHERE comment_post_ID = wp_posts.ID
                    AND comment_content LIKE '%$tag%'
                )
                OR EXISTS (
                  SELECT * FROM wp_terms
                  INNER JOIN wp_term_taxonomy
                    ON wp_term_taxonomy.term_id = wp_terms.term_id
                  INNER JOIN wp_term_relationships
                    ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
                  WHERE (
              		taxonomy = 'post_tag'
                		OR taxonomy = 'category'
                		OR taxonomy = 'myCustomTax'
              		)
                  	AND object_id = wp_posts.ID
                  	AND wp_terms.name LIKE '%$tag%'
                )
            )";
        endforeach;
        return $where;
    }

    /**
    *   Search
    */
    public function search_ajax()
    {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        $term = $data->term;

        $posts = self::search_posts($term);

        $context['articles'] = $posts;
        $template = \Timber::compile( array( 'articles/fps-articles.twig'), $context);

        if( $data->action == 'fps_search_ajax' ) {
            echo $template;
            die();
        }
        return $template;
    }

    public static function search_posts($term, $post_types = array())
    {
        global $wpdb;
        $args = array(
            'post_type' => $post_types,
            'post_status' => 'publish',
            's' => $term,
        );
        $wp_query = new \WP_Query( $args );
        $sql = "SELECT ID FROM wp_posts
        WHERE (";

        foreach($post_types as $key => $post_type)
        {
            $query = "wp_posts.post_type = '";
            if($key > 0) {
                $query = " OR wp_posts.post_type = '";
            }
            $sql .= $query . $post_type ."' ";
        }

        $sql .= ") AND wp_posts.post_status = 'publish'";
        $search = self::advanced_custom_search($sql, $wp_query );
        $sql .= $search;
        $sql .= "ORDER BY wp_posts.post_title ASC LIMIT 0, 10";
        $results = $wpdb->get_results( $sql );

        $ids = array();

        foreach ($results as &$item)
        {
            // add link to object
            // $item->link = get_the_permalink($item);
            array_push($ids, get_post( $item->ID ) );
        }

        $results = $ids;

        return $results;
    }

    public static function search_documents($term)
    {
        global $wpdb;
        $args = array(
            'post_type' => array('document'),
            'post_status' => 'publish',
            's' => $term,
        );
        $wp_query = new \WP_Query( $args );
        $sql = "SELECT * FROM wp_posts
        WHERE (
            wp_posts.post_type = 'document'
        ) AND wp_posts.post_status = 'publish'";
        $search = self::advanced_custom_search($sql, $wp_query );
        $sql .= $search;
        $sql .= "ORDER BY wp_posts.post_title ASC LIMIT 0, 10";
        $results = $wpdb->get_results( $sql );

        foreach ($results as &$item)
        {
            // add link to object
            $item->link = get_the_permalink($item);
        }

        return $results;
    }

}
