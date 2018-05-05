<?php

namespace Includes\Utils;

class Utils
{
    /**
     *  Return image type
     *
     *  @param  {String} Device String
     *  @return  {Boolean} True if detected otherwise false
     */
   public static function isDevice( ) {
      $detect = new \Detection\MobileDetect();

      if( $detect->isMobile() ) {
         return 'mobile';
      } elseif ( $detect->isTablet() ) {
         return 'tablet';
      } else {
         return 'desktop';
      }

      return false;
   }

    /**
     *  Return image url based on Device
     *
     *  @param  {String} Device String
     *  @return  {Boolean} True if detected otherwise false
     */
   public static function deviceIs( $device ) {
      $detect = new \Detection\MobileDetect();

      switch($device) {
         case 'mobile':
            ( $detect->isMobile() ) ? true : false ;
            break;
         case 'tablet':
            return ( $detect->isTablet() ) ? true : false ;
            break;
         case 'desktop':
            return ( $detect->isDesktop() ) ? true : false ;
            break;
         case 'not-desktop':
            return ( !$detect->isDesktop() ) ? true : false ;
      }

      return false;
   }

    /**
     *  Return image url based on Device
     *
     *  @param  {Array} Image Array
     *  @param  {String} image type
     *  @param  {boolean} Return Url Only
     *  @param  {String} Classes
     */
     public static function serveImage( $image_array = array(), $type = 'tile', $srcOnly = false, $class )
     {
       $size = $image_array['sizes'];
       $title = $image_array['title'];

       // die(var_dump($image_array));

       // if Mobile
       if( Utils::deviceIs('mobile') )
       {
          switch($type)
          {
             case 'tile' :
                $src = $size['tile-mobile'];
                break;
             case 'banner' :
                $src = $size['banner-mobile'];
                break;
          }
       }

       // if Tablet
       else if( Utils::deviceIs('tablet') )
       {
          switch($type)
          {
             case 'tile' :
                $src = $size['tile-tablet'];
                break;
             case 'banner' :
                $src = $size['banner-tablet'];
                break;
          }
       }

       // if Desktop
       else
       {
          switch($type)
          {
             case 'tile' :
                $src = $size['tile-desktop'];
                break;
             case 'banner' :
                $src = $size['banner-desktop'];
                break;
          }
       }

       return ( $srcOnly === true ) ? $src : '<img src="'. $src .'" alt="'. $title .'" class="'.$class.'"/>' ;

     }

     // create breadcrumbs from post or from taxonomy term.

     // list of terms ready to go (objects)
     // ids to convert to terms (12, 23 )

     public static function build_terms_from_str($taxonomy_str, $term_str)
     {
         // get the term of the :term variable
         $term = get_term_by('slug', $term_str, $taxonomy_str);
         // get the ancesters (id) of the above term
         $ancestors = get_ancestors( $term->term_id, $taxonomy_str );
         // reverse the array so it outputs correctly
         $ancestors = array_reverse( $ancestors );

         $terms = array();

         foreach($ancestors as $ancestor)
         {
             $ancestor_term = get_term_by('id', $ancestor, 'category');
             array_push( $terms, $ancestor_term );

         }
         array_push( $terms, $term );

         return $terms;
     }

     public static function create_breadcrumbs($taxonomy, $terms)
     {
         $breadcrumbs = array();
         foreach($terms as $term)
         {
             if($term->name !== 'Year')
             {
                 array_push($breadcrumbs, array('anchor_link' => '/'.$taxonomy->rewrite['slug'] .'/'. $term->slug, 'anchor_text' => $term->name ));
             }
         }

         return $breadcrumbs;
     }

     public static function wp_get_post_terms_hierarchy ( $post_id, $taxonomy )
     {
        $terms = wp_get_post_terms( $post_id, $taxonomy, array('parent' => 0) );
        $sorted_terms = [];

        foreach( $terms as $term )
        {
            $children = wp_get_post_terms($post_id, $taxonomy, array( 'parent' => $term->term_id) );
            if( count($children) > 0 )
            {
                $term->children = $children;
            }
            $sorted_terms[] = $term;
        }
        return $sorted_terms;
    }

    // public static function wp_get_terms_hierarchy($tax)
    // {
    //     $terms = get_terms( array('taxonomy' => $tax, 'parent' => 0, 'hide_empty' => false) );
    //     $sorted_terms = [];
    //     foreach ($terms as $key => $term)
    //     {
    //         $children = get_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => false) );
    //         if( count($children) > 0 )
    //         {
    //             $term->children = $children;
    //         }
    //         $sorted_terms[] = $term;
    //     }
    //     return $sorted_terms;
    // }

    public static function wp_get_terms_hierarchy($tax)
    {
        $terms = get_terms( array('taxonomy' => $tax, 'parent' => 0, 'hide_empty' => true) );
        $sorted_terms = [];

        $sorted_terms = self::wp_get_terms_hierarchy_loop($tax, $terms, $sorted_terms);

        return $sorted_terms;
    }

    public static function wp_get_terms_hierarchy_loop($tax, $terms, $sorted_terms = array())
    {
        foreach ($terms as $key => &$term)
        {
            // get children at current level.
            $children = get_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => true) );

            if( count($children) > 0 )
            {
                // loop through indefinite children (scary).
                $loop = self::wp_get_terms_hierarchy_loop($tax, $children, $sorted_terms);

                // add returned children to current term.
                $term->children = $loop['children'];
            }
            // Add the current term to final array.
            $sorted_terms[] = $term;
        }

        return array('children' => $terms, 'sorted_terms' => $sorted_terms);
    }

    public static function wp_get_terms_hierarchy_show_empty($tax)
    {
        $terms = get_terms( array('taxonomy' => $tax, 'parent' => 0, 'hide_empty' => false) );
        $sorted_terms = [];

        $sorted_terms = self::wp_get_terms_hierarchy_loop_show_empty($tax, $terms, $sorted_terms);

        return $sorted_terms;
    }

    public static function wp_get_terms_hierarchy_loop_show_empty($tax, $terms, $sorted_terms = array())
    {
        foreach ($terms as $key => &$term)
        {
            // get children at current level.
            $children = get_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => false) );

            if( count($children) > 0 )
            {
                // loop through indefinite children (scary).
                $loop = self::wp_get_terms_hierarchy_loop($tax, $children, $sorted_terms);

                // add returned children to current term.
                $term->children = $loop['children'];
            }
            // Add the current term to final array.
            $sorted_terms[] = $term;
        }

        return array('children' => $terms, 'sorted_terms' => $sorted_terms);
    }

    public static function wp_get_posts_from_terms($terms, $sorted_terms = array())
    {
        foreach ($terms as $key => &$term)
        {
            // get children at current level.
            $children = get_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => true) );

            if( count($children) > 0 )
            {
                // loop through indefinite children (scary).
                $loop = self::wp_get_terms_hierarchy_loop($tax, $children, $sorted_terms);

                // add returned children to current term.
                $term->children = $loop['children'];
            }
            // Add the current term to final array.
            $sorted_terms[] = $term;
        }

        return array('children' => $terms, 'sorted_terms' => $sorted_terms);
    }

    public static function breadcrumbs()
    {
        // Change to current theme name
        $breadcrumbs_options = get_option( 'current_theme_name' );
        if ( !is_search() ) {
        	/* === OPTIONS === */
        	$text['home']     = __( 'Home' ); // text for the 'Home' link
        	$text['category'] = ''; // text for a category page
        	$text['tax']      = __( ' %s' ); // text for a tag page
        	$text['search']   = __( 'Search results for: %s' ); // text for a search results page
        	$text['tag']      = __( 'Posts tagged %s' ); // text for a tag page
        	$text['author']   = __( 'View all posts by %s' ); // text for an author page
        	$text['404']      = __( 'Error 404' ); // text for the 404 page
        	$showCurrent = 0; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        	$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        	/* === END OF OPTIONS === */

        	global $post, $paged, $page;
        	$homeLink   = home_url( '/' );
            $breadcrumbs = array();
        	// $link       = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

        	if( is_front_page() ) {
        		if( $showOnHome == 1 ) {
        			echo '<div class="breadcrumb-list"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
        		}
        	}
        	else {
        		// echo '<div class="breadcrumb-list" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf( $link, $homeLink, $text['home'] ) . ;

        		// if( is_home() ) {
        		// 	if( $showCurrent == 1 ) {
        		// 		echo get_the_title( get_option( 'page_for_posts', true ) );
        		// 	}
                //
        		// }
        		if( is_category() ) {
        			$thisCat = get_category( get_query_var( 'cat' ), false );
        			if( $thisCat->parent != 0 ) {
        				$cats = get_category_parents( $thisCat->parent );
        				// $cats = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
        				// $cats = str_replace( '</a>', '</a>' . $linkAfter, $cats );
        				return $cats;
        			}
        			return sprintf( $text['category'], single_cat_title( '' ) );
        		}
        		elseif( is_search() ) {
        			return sprintf( $text['search'], get_search_query() );
        		}
        		elseif( is_day() ) {
        			echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) ;
        			echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) ;
        			echo get_the_time( 'd' );
        		}
        		elseif( is_month() ) {
        			echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) ;
        			echo get_the_time( 'F' );
        		}
        		elseif( is_year() ) {
        			echo get_the_time( 'Y' );
        		}

        		elseif( is_single() && !is_attachment() )
                {
        			// if( get_post_type() != 'post' ) {
        				$post_type = get_post_type_object( get_post_type() );
        				$slug      = $post_type->rewrite;
                        $breadcrumbs[] = array('anchor_text' => $post_type->labels->name, 'anchor_link' => $homeLink . '/' . $slug['slug']);
                        // $breadcrumbs[] = array('anchor_text' => $post_type->labels->singular_name, 'anchor_link' => '#');
        				if( $showCurrent == 1 )
                        {
                            $breadcrumbs[] = array('anchor_text' => $post->title, 'anchor_link' => '#');
        				}
        			// }
        			// else {
                    //     $cat  = get_the_category();
                    //     $cat  = $cat[0];
                    //     $cats = get_category_parents( $cat, true, $delimiter );
                    //     if( $showCurrent == 0 ) {
                    //         $cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
                    //     }
                    //     $cats = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
                    //     $cats = str_replace( '</a>', '</a>' . $linkAfter, $cats );
                    //     echo $cats;
                    //     if( $showCurrent == 1 ) {
                    //         echo $before . get_the_title() . $after;
                    //     }
        			// }
        		}

                // Taxonomies mostly
        		elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() )
                {
        			if( is_tax() )
                    {
        				$post_type = get_post_type_object( get_post_type() );
                        $breadcrumbs[] = array('anchor_text' => $post_type->labels->name, 'anchor_link' => $homeLink .$post_type->rewrite['slug']);

                        $thisTax = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

        				if( $thisTax->parent != 0 )
                        {
        					$parent = get_term( $thisTax->parent, $thisTax->taxonomy );
        					$breadcrumbs[] = array( 'anchor_text' => $parent->name, 'anchor_link' => get_term_link( $parent ) );

        				}
                        $breadcrumbs[] = array( 'anchor_text' => single_cat_title('', false), 'anchor_link' => '#' );
        			}
        			else
                    {
        				$post_type = get_post_type_object( get_post_type() );
                        $breadcrumbs[] = array( 'anchor_text' => $post_type->labels->name, 'anchor_link' => '#' );
        			}
        		}

        		elseif( is_attachment() ) {
        			$parent = get_post( $post->post_parent );
        			$cat    = get_the_category( $parent->ID );
        			if( isset( $cat[0] ) ) {
        				$cat = $cat[0];
        			}
        			if( $cat ) {
        				$cats = get_category_parents( $cat, true );
        				echo $cats;
        			}
        			printf( $link, get_permalink( $parent ), $parent->post_title );
        			if( $showCurrent == 1 ) {
        				echo  get_the_title();
        			}
        		}

        		elseif( is_page() && !$post->post_parent ) {
        			if( $showCurrent == 0 ) {
        				$breadcrumbs[] = array( 'anchor_text' => get_the_title(), 'anchor_link' => '#' );
        			}
        		}

        		elseif( is_page() && $post->post_parent ) {
        			$parent_id   = $post->post_parent;
        			$breadcrumbs = array();
        			while( $parent_id ) {
        				$page_child    = get_page( $parent_id );
                        $breadcrumbs[] = array( 'anchor_text' => get_the_title( $page_child->ID ), 'anchor_link' => get_permalink( $page_child->ID ) );
        				$parent_id     = $page_child->post_parent;
        			}
        			$breadcrumbs = array_reverse( $breadcrumbs );
        			if( $showCurrent == 1 ) {
                          $breadcrumbs[] = array( 'anchor_text' => get_the_title(), 'anchor_link' => '#' );
        			}
        		}
        		elseif( is_tag() ) {
                    $breadcrumbs[] = array( 'anchor_text' => single_tag_title( '', false ), 'anchor_link' => '#' );
        		}
        		elseif( is_author() ) {
        			global $author;
        			$userdata = get_userdata( $author );
        			echo sprintf( $text['author'], $userdata->display_name );
        		}
        		elseif( is_404() ) {
        			$breadcrumbs[] = array( 'anchor_text' => '404', 'anchor_link' => '#' );
        		}
        		if( get_query_var( 'paged' ) ) {
        			if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
        				echo ' (';
        			}
        			echo sprintf( __( 'Page %s' ), max( $paged, $page ) );
        			if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
        				echo ')';
        			}
        		}

                return $breadcrumbs;

        	}
        }
    }

    public static function posts_orderby_lastname ($orderby_statement)  {
      $orderby_statement = "RIGHT(post_title, LOCATE(' ', REVERSE(post_title)) - 1) ASC";
        return $orderby_statement;
    }
}
