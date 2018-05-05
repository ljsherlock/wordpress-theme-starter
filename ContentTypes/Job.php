<?php

namespace ContentTypes;

class Job extends CustomPost
{
  protected $name = "vacancy";
  protected $singular = "Vacancy";
  protected $plural = "Vacancies";
  protected $icon = 'dashicons-clipboard';

  public function __construct()
  {
    \Routes::map('news/page/:paged', function($params){
      $archive = new \Controllers\Archive(array( 'query' => array('category_name' => 'news', 'paged' => $params['paged'] )));
      $archive->show();
    });

    \Routes::map('jobs/page/:paged', function($params){
      $archive = new \Controllers\People(array( 'query' => array('paged' => $params['paged'] )));
      $archive->show();
    });

    parent::__construct();

    $this->args['supports'] = array( 'title', 'editor');

    $this->taxonomy = array(
      array(
        'name' => 'importance',
        'single' => 'Level of Importance',
        'plural' => 'Levels of Importance'
      )
    );

    $this->taxononmy_args = array(
      array(
        'hierarchical' => true,
        'query_var'    => true,
        'label' => __($this->taxonomy[0]['single'], LJS_TEXT_DOMAIN),
        'show_admin_column' => true,
        'rewrite' => array( 'slug' => $this->name . '/' . $this->taxonomy[0]['name'], 'with_front' => false ),
        'labels' => array(
            'name'          => $this->taxonomy[0]['plural'],
            'singular_name' => $this->taxonomy[0]['single'],
            'search_items'  => 'Search ' . $this->taxonomy[0]['plural'],
            'edit_item'     => 'Edit ' . $this->taxonomy[0]['single'],
            'add_new_item'  => 'Add New ' . $this->taxonomy[0]['single'],
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
      $taxonomy['args'] = $this->taxononmy_args[$key];
    }
  }
}
