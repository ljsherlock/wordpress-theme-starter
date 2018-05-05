<?php

namespace ContentTypes;

class Work extends CustomPost
{
  protected $name = "work";
  protected $singular = "Work Project";
  protected $plural = "Work Projects";
  protected $icon = 'dashicons-awards';

  public function __construct()
  {
    parent::__construct();

    add_action( 'save_post_work', array(__CLASS__, 'add_default_term' ));

    $this->taxonomy = array(
      array(
        'name' => 'type',
        'single' => 'Project',
        'plural' => 'Projects',
      )
    );

    $this->taxonomy_args = array(
       array(
        'hierarchical' => true,
        'query_var'    => true,
        'label' => __('Work Types', LJS_TEXT_DOMAIN),
        'show_admin_column' => true,
        'rewrite' => array( 'slug' => $this->name . '/' . $this->taxonomy[0]['name'], 'with_front' => false ),
        'labels' => array(
            'name'          => 'Work Types',
            'singular_name' => $this->taxonomy[0]['single'],
            'search_items'  => 'Search ' . $this->taxonomy[0]['plural'],
            'edit_item'     => 'Edit ' . $this->taxonomy[0]['single'],
            'add_new_item'  => 'Add New ' . $this->taxonomy[0]['single'],
            'view_item' => 'View ' . $this->taxonomy[0]['single'],
            'not_found'          => __("No {$this->taxonomy[0]['plural']} found.", LJS_TEXT_DOMAIN),
            'not_found_in_trash' => __("No {$this->taxonomy[0]['plural']} found in Trash.", LJS_TEXT_DOMAIN),
            'all_items'          => __("All {$this->taxonomy[0]['plural']}", LJS_TEXT_DOMAIN),
            'search_items'       => __("Search {$this->taxonomy[0]['plural']}", LJS_TEXT_DOMAIN),
            'parent_item'       => __( "Parent {$this->taxonomy[0]['single']}", LJS_TEXT_DOMAIN ),
            'parent_item_colon'  => __("Parent {$this->taxonomy[0]['single']}:", LJS_TEXT_DOMAIN),
        )
      )
    );

    foreach($this->taxonomy as $key => &$taxonomy) {
      $taxonomy['args'] = $this->taxonomy_args[$key];
    }
  }

  public static function add_default_term($ID) {
    $current_post = get_post( $ID );
    wp_set_object_terms( $ID, 'all', 'type', true );
    // This makes sure the taxonomy is only set when a new post is created
    // if ( $current_post->post_date == $current_post->post_modified ) {
    // }
  }
}
