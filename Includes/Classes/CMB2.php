<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'ljsherlock_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

namespace Includes\Classes;

class CMB2
{

    public static $prefix = '_ljsherlock_meta_';

    public static function init()
    {
        if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
        	require_once dirname( __FILE__ ) . '/cmb2/init.php';
        } elseif ( file_exists( dirname( __FILE__ ) . '/CMB_2/init.php' ) ) {
        	require_once dirname( __FILE__ ) . '/CMB_2/init.php';
        }

        add_action( 'cmb2_admin_init', array( __CLASS__, 'ljsherlock_register_front_page_meta') );
        add_action( 'cmb2_admin_init', array( __CLASS__, 'ljsherlock_register_custom_post_meta') );
        add_action( 'cmb2_admin_init', array( __CLASS__, 'myprefix_register_theme_options_metabox') );
        add_action( 'cmb2_admin_init', array( __CLASS__, 'myprefix_register_pages_meta') );
    }

    /**
    * @method Pages
		*
     * Hook in and add a metaboxes that only appears for the 'Project' posts
     * @return
     * @
     */
     public static function myprefix_register_pages_meta()
     {

       /**
       *  About Page
       */

         $cmb_about_page = new_cmb2_box( array(
             'id'           => self::$prefix . 'grid_content_meta',
             'title'        => esc_html__( 'Grid Content', 'cmb2' ),
             'object_types' => array( 'page' ), // Post type
             'context'      => 'normal',
             'priority'     => 'high',
             'show_names'   => true, // Show field names on the left
             'show_on'      => array(
                 'id' => array( 49 ),
             ), // Specific post IDs to display this metabox
         ) );

         $grid_content = $cmb_about_page->add_field( array(
           // 'name'    => __( 'Testimonial ', 'cmb2' ),
           'id'      => self::$prefix . 'about_grid_content',
          'type'        => 'group',
          // 'description' => __( 'Content in grid', 'cmb2' ),
          'repeatable'  => true, // use false if you want non-repeatable group
          'options'     => array(
            'group_title'   => __( 'Row {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'remove_button' => __( 'Remove', 'cmb2' ),
            // 'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
          ),
         ));

         $cmb_about_page->add_group_field($grid_content, array(
             'name' => esc_html__( 'Title', 'cmb2' ),
             'desc' => esc_html__( 'Title to appear above the content', 'cmb2' ),
             'id'   => 'title',
             'type' => 'text',
         ));

         $cmb_about_page->add_group_field($grid_content, array(
             'name' => esc_html__( 'Content', 'cmb2' ),
             'id'   => 'content',
             'type' => 'wysiwyg',
             'options' => array(
               'wpautop' => true, // use wpautop?
               'media_buttons' => false, // show insert/upload button(s)
               'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
               'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
               'quicktags' => true,
               //'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
               // 'tabindex' => '',
               // 'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
               // 'editor_class' => '', // add extra class(es) to the editor textarea
               // 'teeny' => false, // output the minimal editor config used in Press This
               // 'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
             )
         ));

         $cmb_about_page->add_group_field($grid_content, array(
             'name' => esc_html__( 'Images', 'cmb2' ),
             'desc' => esc_html__( 'Add images to appear in rows with 2 columns (1 will fill the row and more than 2 will wrap onto next row.)', 'cmb2' ),
             'id'   => 'images',
             'type' => 'file_list',
             'preview_size' => array( 150, 150 ), // Default: array( 50, 50 )
             'query_args' => array( 'type' => 'image' ), // Only images attachment
         ));

         /**
         *  Contact Page
         */

         $cmb_about_page = new_cmb2_box( array(
             'id'           => self::$prefix . 'contact_grid',
             'title'        => esc_html__( 'Grid Content', 'cmb2' ),
             'object_types' => array( 'page' ), // Post type
             'context'      => 'normal',
             'priority'     => 'high',
             'show_names'   => true, // Show field names on the left
             'show_on'      => array(
                 'id' => array( 100 ),
             ), // Specific post IDs to display this metabox
         ) );

         $grid_content = $cmb_about_page->add_field( array(
           // 'name'    => __( 'Testimonial ', 'cmb2' ),
           'id'      => self::$prefix . 'contact_grid_content',
          'type'        => 'group',
          // 'description' => __( 'Content in grid', 'cmb2' ),
          'repeatable'  => true, // use false if you want non-repeatable group
          'options'     => array(
            'group_title'   => __( 'Row {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'remove_button' => __( 'Remove', 'cmb2' ),
            // 'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
          ),
         ));

         // $cmb_about_page->add_group_field($grid_content, array(
         //     'name' => esc_html__( 'Title', 'cmb2' ),
         //     'desc' => esc_html__( 'Title to appear above the content', 'cmb2' ),
         //     'id'   => 'title',
         //     'type' => 'text',
         // ));
         //
         // $cmb_about_page->add_group_field($grid_content, array(
         //     'name' => esc_html__( 'Content', 'cmb2' ),
         //     'id'   => 'content',
         //     'type' => 'wysiwyg',
         //     'options' => array(
         //       'wpautop' => true, // use wpautop?
         //       'media_buttons' => false, // show insert/upload button(s)
         //       'textarea_rows' => get_option('default_post_edit_rows', 5), // rows="..."
         //       'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
         //       'quicktags' => true,
         //       //'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
         //       // 'tabindex' => '',
         //       // 'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
         //       // 'editor_class' => '', // add extra class(es) to the editor textarea
         //       // 'teeny' => false, // output the minimal editor config used in Press This
         //       // 'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
         //     )
         // ));

         $cmb_about_page->add_group_field($grid_content, array(
             'name' => esc_html__( 'Images', 'cmb2' ),
             'desc' => esc_html__( 'Add images to appear in rows with 2 columns (1 will fill the row and more than 2 will wrap onto next row.)', 'cmb2' ),
             'id'   => 'images',
             'type' => 'file_list',
             'preview_size' => array( 150, 150 ), // Default: array( 50, 50 )
             'query_args' => array( 'type' => 'image' ), // Only images attachment
         ));

     }


    /**
        Front Page

     * Hook in and add a metaboxes that only appears for the 'Project' posts
     * @return
     * @
     */
    public static function ljsherlock_register_front_page_meta()
    {
			$frontPageOptions = CMB2::get_frontpage_options();
			$frontPageLinks = CMB2::get_frontpage_links();

      $cmb_frontpage = new_cmb2_box( array(
          'id'           => self::$prefix . 'front_page_posts',
          'title'        => esc_html__( 'Fly in Posts', 'cmb2' ),
          'object_types' => array( 'page' ), // Post type
          'context'      => 'normal',
          'priority'     => 'high',
          'show_names'   => true, // Show field names on the left
          'show_on'      => array(
              'id' => array( 47 ),
          ), // Specific post IDs to display this metabox
      ) );

      $cmb_frontpage_group = $cmb_frontpage->add_field(array(
          'desc' => esc_html__( 'Captions to display on the side of the logo', 'cmb2' ),
          'id'   => self::$prefix . 'front_page_posts_group',
          'type' => 'group',
          'title' => 'Fly in Posts',
          'description' => __( 'Select the posts to show. Positions will be applied to them in the order that they appear here.', 'cmb2' ),
          'repeatable'  => true, // use false if you want non-repeatable group
          'options'     => array(
            'group_title'   => __( 'Item {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'remove_button' => __( 'Remove post', 'cmb2' ),
            'add_button' => __( 'Add post', 'cmb2' ),
            'sortable'      => true, // beta
          ),
      ));

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Link to Post', 'cmb2' ),
        'desc' => '<p class="cmb2-metabox-description">Select a post</p>',
        'id'   =>  'post',
        'type' => 'select',
        'default'          => 'custom',
        'show_option_none' => true,
        'select_all_button' => false,
        // use options callback
        'options' => $frontPageOptions,
      ) );

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Link to Archive', 'cmb2' ),
        'desc' => '<p class="cmb2-metabox-description">For buttons only</p>',
        'id'   =>  'link',
        'type' => 'select',
        'default'          => 'custom',
        'show_option_none' => true,
        'select_all_button' => false,
        // use options callback
        'options' => $frontPageLinks,
      ) );

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Media to Show', 'cmb2' ),
        'desc' => '<p class="cmb2-metabox-description">If "<strong>link</strong>" is selected a button will be used and the below options will become redundant.</p>',
        'id'   => 'media',
        'type' => 'radio_inline',
        'default'          => 'featured_image',
        'select_all_button' => false,
        'options'          => array(
          'featured_image' => __( 'Featured Image', 'cmb2' ),
          'customImage' => __( 'Custom Image', 'cmb2' ),
          'video' => __( 'Video', 'cmb2' ),
          'link' => __( 'Button', 'cmb2' ),
          'content' => __( 'Content', 'cmb2' ),
        ),
      ) );

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Custom Image', 'cmb2' ),
        'before' =>  '<p>Select an Image to override the featured image.</p>',
        'desc' => '<p>Make sure "<strong>Custom Image</strong>" is selected above.</p>',
        'id'   =>  'customImage',
        'type' => 'file',
      ) );

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Size', 'cmb2' ),
        'id'   => 'size',
        'type' => 'select',
        'default'          => 'md',
        'select_all_button' => false,
        'options'          => array(
          'xs' => __( 'Extra Small ( Width 275px )', 'cmb2' ),
          'sm' => __( 'Small ( Width 350px )', 'cmb2' ),
          'md' => __( 'Medium ( Width 575px )', 'cmb2' ),
          'lg' => __( 'Large ( Width 750px ) ', 'cmb2' ),
        ),
      ) );

      $cmb_frontpage->add_group_field($cmb_frontpage_group, array(
        'name' => esc_html__( 'Orientation', 'cmb2' ),
        'id'   => 'orientation',
        'type' => 'radio_inline',
        'default'          => 'landscape',
        'select_all_button' => false,
        'options'          => array(
      		'landscape' => __( 'Landscape', 'cmb2' ),
      		'portrait' => __( 'Portrait', 'cmb2' ),
      	),
      ) );
    }


    /**
     * Project Posts
     * Hook in and add a metaboxes that only appears for the 'Project' posts
     * @return Primary Image
     * @return Secondary Images
     */
    public static function ljsherlock_register_custom_post_meta()
    {
        /**
         * "Project" Custom Posd
         */
        $cmb_project = new_cmb2_box( array(
            'id'           => self::$prefix . 'project_metabox',
            'title'        => esc_html__( 'Project Gallery', 'cmb2' ),
            'object_types' => array( 'work' ), // Post type
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true, // Show field names on the left
            // 'show_on'      => array(
            //     'id' => array( 5 ),
            //), // Specific post IDs to display this metabox
        ) );

        $cmb_video = new_cmb2_box( array(
            'id'           => self::$prefix . 'project_video',
            'title'        => esc_html__( 'Project Video', 'cmb2' ),
            'object_types' => array( 'work' ), // Post type
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true, // Show field names on the left
            // 'show_on'      => array(
            //     'id' => array( 5 ),
            //), // Specific post IDs to display this metabox
        ) );

        $cmb_video->add_field( array(
          'id'      => self::$prefix . 'project_video',
          'type'        => 'file',
          'title' => 'Project Video',
          'description' => __( 'Upload your project video', 'cmb2' ),
        ));

        $gallery = $cmb_project->add_field( array(
          'id'      => self::$prefix . 'project_gallery',
          'type'        => 'group',
          'title' => 'Project Gallery',
          'description' => __( 'Upload your project images', 'cmb2' ),
          'repeatable'  => true, // use false if you want non-repeatable group
          'options'     => array(
            'group_title'   => __( 'Item {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'remove_button' => __( 'Remove File', 'cmb2' ),
            'add_button' => __( 'Add File', 'cmb2' ),
            // 'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
          ),
        ));

        $cmb_project->add_group_field($gallery, array(
            'name' => esc_html__( 'Image or Video', 'cmb2' ),
            'id'   => 'file_list',
            'type' => 'file_list',
        ) );

        $cmb_project_testimonial = new_cmb2_box( array(
            'id'           => self::$prefix . 'project_testimonial_box',
            'title'        => esc_html__( 'Project Testimonial', 'cmb2' ),
            'object_types' => array( 'work' ), // Post type
            'context'      => 'normal',
            'priority'     => 'low',
            'show_names'   => true, // Show field names on the left
            // 'show_on'      => array(
            //     'id' => array( 5 ),
            //), // Specific post IDs to display this metabox
        ) );

        $testimonial = $cmb_project_testimonial->add_field( array(
          // 'name'    => __( 'Testimonial ', 'cmb2' ),
          'id'      => self::$prefix . 'project_testimonial',
        	'type'        => 'group',
        	'description' => __( 'Details of your project testimonial', 'cmb2' ),
        	'repeatable'  => false, // use false if you want non-repeatable group
        	'options'     => array(
        		'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        		'remove_button' => __( 'Remove Icon', 'cmb2' ),
        		// 'sortable'      => true, // beta
        		// 'closed'     => true, // true to have the groups closed by default
        	),
        ));

          $cmb_project_testimonial->add_group_field($testimonial, array(
              'name' => esc_html__( 'Content', 'cmb2' ),
              'id'   => 'content',
              'type' => 'textarea_small',
          ));

          $cmb_project_testimonial->add_group_field($testimonial, array(
              'name' => esc_html__( 'Author', 'cmb2' ),
              'id'   => 'author',
              'type' => 'text',
          ));

          $cmb_project_testimonial->add_group_field($testimonial, array(
              'name' => esc_html__( 'Author Description', 'cmb2' ),
              'id'   => 'author_desc',
              'type' => 'text',
          ));


      /**
       * "People" Custom Post
       */
      $cmb_project = new_cmb2_box( array(
          'id'           => self::$prefix . 'people_metabox',
          'title'        => esc_html__( 'Key projects', 'cmb2' ),
          'object_types' => array( 'staff' ), // Post type
          'context'      => 'normal',
          'priority'     => 'high',
          'show_names'   => true, // Show field names on the left
      ));

      $cmb_project->add_field( array(
          // 'name' => esc_html__( 'Key Projects', 'cmb2' ),
          'desc' => esc_html__( 'Select 4 key projects', 'cmb2' ),
          'id'   => self::$prefix . 'staff_key_projects',
          'type' => 'multicheck_inline',
        	'default'          => 'custom',
          'select_all_button' => false,
          // use options callback
          'options_cb' => array( __CLASS__, 'get_project_options'),
      ) );

    }

    /**
    *    Theme Options
    */
    /**
     * Hook in and register a metabox to handle a theme options page and adds a menu item.
     */
    public static function myprefix_register_theme_options_metabox()
    {
    	/**
    	 * Registers options page menu item and form.
    	 */

      /**
      *
      * Contact Details
      *
      */
      $cmb_theme_settings_contact = new_cmb2_box( array(
    		'id'           => 'options_contact',
    		'title'        => esc_html__( 'Contact Details', 'myprefix' ),
    		'object_types' => array( 'options-page' ),
    		/*
    		 * The following parameters are specific to the options-page box
    		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
    		 */
    		'option_key'      => 'settings-contact', // The option key and admin menu page slug.
    		// 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
    		// 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
    		'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
    		// 'capability'      => 'manage_options', // Cap required to view options-page.
    		// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
    		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
    		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
    		// 'save_button'     => esc_html__( 'Save Theme Options', 'myprefix' ), // The text for the options-page save button. Defaults to 'Save'.
    	));

      $cmb_theme_settings_contact->add_field( array(
       'name'    => __( 'Contact Email ', 'cmb2' ),
       'id'      => self::$prefix . 'contact_email',
       'type'    => 'text_email',
     ));

     $cmb_theme_settings_contact->add_field( array(
      'name'    => __( 'Jobs Email ', 'cmb2' ),
      'id'      => self::$prefix . 'vacancy_email',
      'type'    => 'text_email',
    ));

     $cmb_theme_settings_contact->add_field( array(
       'name'    => __( 'Contact Telephone ', 'cmb2' ),
       'id'      => self::$prefix . 'contact_telephone',
       'type'    => 'text',
     ));

      $cmb_address = $cmb_theme_settings_contact->add_field( array(
       'name'    => __( 'Company Address ', 'cmb2' ),
       'id'      => self::$prefix . 'company_address',
       'type'        => 'group',
       'options'     => array(
         'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
         'add_button'    => __( 'Add Another Line', 'cmb2' ),
         'remove_button' => __( 'Remove Line', 'cmb2' ),
         // 'closed'     => true, // true to have the groups closed by default
       ),
       // 'description' => __( 'Generates reusable form entries', 'cmb2' ),
       // 'repeatable'  => false, // use false if you want non-repeatable group
     ));

     $cmb_theme_settings_contact->add_field( array(
       'name'    => __( 'Google Map Coordinates ', 'cmb2' ),
       'id'      => self::$prefix . 'map_coordinates',
       'type'    => 'text',
     ));

     $cmb_theme_settings_contact->add_group_field($cmb_address, array(
       'name'    => __( 'Line ', 'cmb2' ),
       'id'      => 'line',
       'type'    => 'text',
     ));


     /**
     *
     * Company Details
     *
     */
     $cmb_theme_settings_company = new_cmb2_box( array(
      'id'           => 'options_company',
      'title'        => esc_html__( 'Company Details', 'myprefix' ),
      'object_types' => array( 'options-page' ),
      /*
       * The following parameters are specific to the options-page box
       * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
       */
      'option_key'      => 'settings-company', // The option key and admin menu page slug.
      // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
      // 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
      'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
      'capability'      => 'manage_options', // Cap required to view options-page.
      // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
      // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
      // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
      // 'save_button'     => esc_html__( 'Save Theme Options', 'myprefix' ), // The text for the options-page save button. Defaults to 'Save'.
    ));

    $cmb_theme_settings_company->add_field( array(
      'name'    => __( 'Company Name', 'cmb2' ),
      'id'      => self::$prefix . 'company_name',
      'type'    => 'text',
    ));

    $cmb_theme_settings_company->add_field( array(
      'name'    => __( 'Company Number', 'cmb2' ),
      'id'      => self::$prefix . 'company_number',
      'type'    => 'text',
    ));

    $cmb_theme_settings_company->add_field( array(
      'name'    => __( 'Registered Locations', 'cmb2' ),
      'id'      => self::$prefix . 'company_registered_locations',
      'type'    => 'text',
    ));

    $cmb_social_icons = $cmb_theme_settings_company->add_field( array(
      'name'    => __( 'Social Media Links ', 'cmb2' ),
      'id'      => self::$prefix . 'social_media_links',
    	'type'        => 'group',
    	// 'description' => __( 'Generates reusable form entries', 'cmb2' ),
    	// 'repeatable'  => false, // use false if you want non-repeatable group
    	'options'     => array(
    		'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
    		'add_button'    => __( 'Add Another Icon', 'cmb2' ),
    		'remove_button' => __( 'Remove Icon', 'cmb2' ),
    		// 'sortable'      => true, // beta
    		// 'closed'     => true, // true to have the groups closed by default
    	),
    ));
    $cmb_theme_settings_company->add_group_field($cmb_social_icons, array(
    	'name'    => __( 'Link ', 'cmb2' ),
    	'id'      => 'link',
    	'type'    => 'text',
    ));
    $cmb_theme_settings_company->add_group_field($cmb_social_icons, array(
    	'name'    => __( 'Icon Slug ', 'cmb2' ),
    	'id'      => 'icon',
    	'type'    => 'text',
    ));

    /**
    *
    * API Details
    *
    */
    $cmb_theme_settings_api = new_cmb2_box( array(
     'id'           => 'options_api',
     'title'        => esc_html__( 'API Authentication', 'myprefix' ),
     'object_types' => array( 'options-page' ),
     /*
      * The following parameters are specific to the options-page box
      * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
      */
     'option_key'      => 'settings-api', // The option key and admin menu page slug.
     // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
     // 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
     'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
     'capability'      => 'manage_options', // Cap required to view options-page.
     // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
     // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
     // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
     // 'save_button'     => esc_html__( 'Save Theme Options', 'myprefix' ), // The text for the options-page save button. Defaults to 'Save'.
   ));

   $cmb_theme_settings_api->add_field(array(
     'name' => esc_html__( 'Instagram Access Token', 'cmb2' ),
     'desc' => esc_html__( 'The Instagram API requires authentication - specifically requests made on behalf of a user. Authenticated requests require an access_token. These tokens are unique to a user and should be stored securely. Access tokens may expire at any time in the future.', 'cmb2' ),
     'id'   => self::$prefix . 'instagram_at',
     'type' => 'text',
   ));
    }

    /**
     * Wrapper function around cmb2_get_option
     * @since  0.1.0
     * @param  string $key     Options array key
     * @param  mixed  $default Optional default value
     * @return mixed           Option value
     */
    public static function myprefix_get_option($options_prefix, $key = '', $default = false ) {
    	if ( function_exists( 'cmb2_get_option' ) ) {
    		// Use cmb2_get_option as it passes through some key filters.
    		return cmb2_get_option( $options_prefix, $key, $default );
    	}
    	// Fallback to get_option if CMB2 is not loaded yet.
    	$opts = get_option( $options_prefix, $default );
    	$val = $default;
    	if ( 'all' == $key ) {
    		$val = $opts;
    	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
    		$val = $opts[ $key ];
    	}
    	return $val;
    }

    // get project posts with archive
    public static function get_project_options() {

      $options = array();

      $work = new \Controllers\Archive(array('query' => array(
        'post_type' => 'work'
      ) ));
      $posts = $work->returnData('posts');
      foreach($posts['postsArray'] as $post) {
        $options[$post->slug] = __( $post->title, 'cmb2' );
      }

      return $options;
    }

    // get project posts with archive
    public static function get_frontpage_options() {

      $options = array();

      $work = new \Controllers\Archive(array(
				'extra_data' => false,
				'query' => array(
		      'post_type' => array('post', 'work', 'page'),
		      'posts_per_page' => 100,
		      'orderby' => 'post_type',
	      // 'order' => 'descending'
       )));
      $posts = $work->returnData('posts');

      foreach($posts['postsArray'] as $post) {
        $options[$post->ID] = __( get_post_type_object($post->post_type)->labels->singular_name .' - ' . $post->title, 'cmb2' );
      }

      return $options;
    }

    public static function get_frontpage_links() {
      $workTypeTerms = get_terms( 'type', array('hide_empty' => false) );

      foreach ($workTypeTerms as $key => $term) {
          $workTypeTerms[$term->term_id] = __( $term->taxonomy .' - '. $term->name, 'cmb2' );
          unset($workTypeTerms[$key]);
      }

      return $workTypeTerms;
    }

    /**
     * Sample template tag function for outputting a cmb2 file_list
     *
     * @param  string  $file_list_meta_key The field meta key. ('wiki_test_file_list')
     * @param  string  $img_size           Size of image to show
     */
    public static function cmb2_output_file_list( $file_list, $img_size = 'thumbnail' ) {

      array_walk($file_list, function( &$attachment_url, $attachment_id, $img_size) {
        // Loop through them and output an image
        $attachment_url = wp_get_attachment_image( $attachment_id, $img_size );
      }, $img_size);

      return $file_list;

    }

}
