<?php

namespace ContentTypes;

class Widget__Recent_Posts_All extends Widget
{
    public $name = 'Recent Posts All';
    public $className = 'recent-posts-all';
    public $desc = "Recent Post and Custom Posts";
    public $type = '';
    public $template = 'base/posts/posts';

    /**
     * Registers the widget with the WordPress Widget API.
     *
     * @return void.
     */
    public static function register() {
        register_widget( __CLASS__ );
    }

    /**
     * Registers the widget with the WordPress Widget API.
     *
     * @return void.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function widget( $args, $instance )
    {
        parent::widget( $args, $instance );

        if(!empty($instance) )
        {
            // widget values
            $this->title = ( !empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
            $this->button_text = ( !empty( $instance['button_text'] ) ) ? $instance['button_text'] : __( 'Read All' );
            $this->posts_per_page = ( !empty( $instance['posts_per_page'] ) ) ? absint( $instance['posts_per_page'] ) : '';
            $this->selected_post_type = ( !empty( $instance['post_type'] ) ) ? $instance['post_type'] : 'post';
            $this->template = ( !empty( $instance['template'] ) ) ? $instance['template'] : $this->template;
            $this->type = 'widget';

            // initiate Controller
            $widget = new \Controllers\Widget__Recent_Posts_All(
                array( 'widget' => $this, )
            );

            // Show the Widget
            $widget->show();
        }
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['button_text'] = sanitize_text_field( $new_instance['button_text'] );
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
        $instance['template'] = ( empty( $new_instance['template'] ) ? $this->template : sanitize_text_field( $new_instance['template'] ) );

        return $instance;
    }

    public function form( $instance )
    {
        // widget field_id, value & name
        $this->instances->title->id = $this->get_field_id( 'title' );
        $this->instances->title->value = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $this->instances->title->name = $this->get_field_name( 'title' );
        $this->instances->title->title = 'Title:';

        $this->instances->button_text->id = $this->get_field_id( 'button_text' );
        $this->instances->button_text->value = isset( $instance['button_text'] ) ? esc_attr( $instance['button_text'] ) : '';
        $this->instances->button_text->name = $this->get_field_name( 'button_text' );
        $this->instances->button_text->title = 'Button Text:';

        $this->instances->posts_per_page->id = $this->get_field_id( 'posts_per_page' );
        $this->instances->posts_per_page->value = isset( $instance['posts_per_page'] ) ? absint( $instance['posts_per_page'] ) : 5;
        $this->instances->posts_per_page->name = $this->get_field_name( 'posts_per_page' );
        $this->instances->posts_per_page->title = 'Posts Per Page:';

        $this->instances->post_type->id = $this->get_field_id( 'post_type' );
        $this->instances->post_type->value = !empty( $instance['post_type'] ) ? $instance['post_type'] : esc_html__( 'post', 'text_domain' );
        $this->instances->post_type->name = $this->get_field_name( 'post_type' );
        $this->instances->post_type->title = 'Post Type:';
        $this->instances->post_type->type = 'select';
        foreach( get_post_types( array( 'public'  => true ), 'name', 'and' ) as $key => $item )
        {
            $options[$key]['value'] = $item->name;
            $options[$key]['text'] = $item->label;
            if(isset($instance['post_type'])){
                $options[$key]['selected'] = ($item->name == $instance['post_type']) ? true : false;
            }
        }
        $this->instances->post_type->options = $options;

        $this->instances->template->id = $this->get_field_id( 'template' );
        $this->instances->template->value = isset( $instance['template'] ) ? esc_attr( $instance['template'] ) : '';
        $this->instances->template->name = $this->get_field_name( 'template' );
        $this->instances->template->title = 'Custom Template:';

        $this->type = 'form';

        //initiate Controller
        $widget = new \Controllers\Widget( array( 'widget' =>  $this ) );

        // Render the Form
        $widget->show();
    }
}
