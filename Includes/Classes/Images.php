<?php

namespace Includes\Classes;

class Images
{

    public static function setup()
    {
        add_action('after_setup_theme', array(__CLASS__, 'add_image_sizes'));
        add_action('image_size_names_choose', array(__CLASS__, 'customSizesAdmin'));
    }


    /**
    * @param orignWidth
    * @param orignHeight
    */
    public static function percentageChange($originNumber, $newNumber) {
      return ($newNumber / $originNumber ) * 100;
    }

    /**
    * @param orignWidth
    * @param orignHeight
    * @param newWidth
    * @param newHeight
    */
    public static function resizeWH($orignWidth, $orignHeight, $newSize, $dimension) {

      if($dimension == 'width') {
        $percentageChange = self::percentageChange($orignWidth, $newSize);
        $newHeight = ($orignHeight * $percentageChange) / 100;
        $newWidth = $orignWidth;
      } else if($dimension == 'height') {
        $percentageChange = self::percentageChange($orignHeight, $newSize);
        $newWidth = ($originWidth * $percentageChange) / 100;
        $newHeight = $originHeight;
      }

      return array( (int)$newWidth, (int)$newHeight );
    }

    /**
     *  Add Image Sizes
     */
      public static function add_image_sizes()
      {
         add_theme_support('post-thumbnails');


         // 1140 x 645
         // Single Featured Image (Work Post)
         // Feature Image Full Width & Posts Featured Image first post  (News Post)
         // Full-width (About Page)
         // Full-Width (Contact Page)
         add_image_size('featured_image_work--desktop--x2', 2280, 1290, true);
         add_image_size('featured_image_work--desktop', 1140, 645, true);
         $tablet = self::resizeWH(1140, 645, 768, 'width');
         add_image_size('featured_image_work--tablet--x2', 768*2, 645*2, true);
         add_image_size('featured_image_work--tablet', 768, 645, true);
         $mobile = self::resizeWH(1140, 645, 640, 'width');
         add_image_size('featured_image_work--mobile--x2', 640*2, 645*2, true);
         add_image_size('featured_image_work--mobile', 640, 645, true);

         // 1140 X 450 - 1 Column landscape
         // gallery
         // news featured images
         add_image_size('landscape_1_column--desktop--x2', 2280, 1290, true);
         add_image_size('landscape_1_column--desktop', 1140, 450, true);
         $tablet = self::resizeWH(1140, 450, 768, 'width');
         add_image_size('landscape_1_column--tablet--x2', 768*2, 645*2, true);
         add_image_size('landscape_1_column--tablet', 768, 645, true);
         $mobile = self::resizeWH(1140, 450, 640, 'width');
         add_image_size('landscape_1_column--mobile--x2', 640*2, 645*2, true);
         add_image_size('landscape_1_column--mobile', 640, 645, true);

         // 560 x 340 - 2 Column Landscape
         // Featured Image (News Posts)
         // Content Image (News)
         add_image_size('landscape_2_column--desktop--x2', 1120, 680, true);
         add_image_size('landscape_2_column--desktop', 560, 340, true);
         add_image_size('landscape_2_column--tablet--x2', 1120, 680, true);
         add_image_size('landscape_2_column--tablet', 560, 340, true);
         $mobile = self::resizeWH(560, 340, 640, 'width');
         add_image_size('landscape_2_column--mobile--x2', $mobile[0]*2, $mobile[1]*2, true);
         add_image_size('landscape_2_column--mobile', $mobile[0], $mobile[1], true);

         // 270 x 340 - 2 Column Landscape
         // Featured Image (News Posts)
         // Content Image (News)
         add_image_size('landscape_2_column_portrait--desktop--x2', 270*2, 340*2, true);
         add_image_size('landscape_2_column_portrait--desktop', 270, 340, true);
         // add_image_size('landscape_2_column--tablet--x2', 1120, 680, true);
         // add_image_size('landscape_2_column--tablet', 560, 340, true);
         // $mobile = self::resizeWH(560, 340, 640, 'width');
         // add_image_size('landscape_2_column--mobile--x2', $mobile[0]*2, $mobile[1]*2, true);
         // add_image_size('landscape_2_column--mobile', $mobile[0], $mobile[1], true);

         // 680 x 500 - 2 Column Portrait
         // All gallery images. For both tall and regular
         // Don't crop, scale to retain original aspect ratio.
          add_image_size('portrait_2_column--desktop--x2', 1360, 1000, true);
          add_image_size('portrait_2_column--desktop', 680, 500, true);
          $tablet = self::resizeWH(680, 500, 768, 'width');
          add_image_size('portrait_2_column--tablet--x2', $tablet[0]*2, $tablet[1]*2, true);
          add_image_size('portrait_2_column--tablet--x2', $tablet[0], $tablet[1], true);
          $mobile = self::resizeWH(680, 500, 640, 'width');
          add_image_size('portrait_2_column--mobile--x2', $mobile[0]*2, $mobile[1]*2, true);
          add_image_size('portrait_2_column--mobile--x2', $mobile[0], $mobile[1], true);

          // 366.667 x 500 - 3 Column Portrait
          // All gallery images. For both tall and regular
          // Don't crop, scale to retain original aspect ratio.
           add_image_size('portrait_3_column--desktop--x2', 734, 1000, true);
           add_image_size('portrait_3_column--desktop', 367, 500, true);
           $tablet = self::resizeWH(367, 500, 768, 'width');
           add_image_size('portrait_3_column--tablet--x2', $tablet[0]*2, $tablet[1]*2, true);
           add_image_size('portrait_3_column--tablet--x2', $tablet[0], $tablet[1], true);
           $mobile = self::resizeWH(367, 500, 640, 'width');
           add_image_size('portrait_3_column--mobile--x2', $mobile[0]*2, $mobile[1]*2, true);
           add_image_size('portrait_3_column--mobile--x2', $mobile[0], $mobile[1], true);

         //366 x 366
         // Featured Image (Post People)
         add_image_size('gallery_image--desktop--x2', 540, 732, true);
         add_image_size('gallery_image--desktop', 270, 366, true);
         add_image_size('gallery_image--tablet--x2', 1080, 1464, true);
         add_image_size('gallery_image--tablet', 540, 732, true);
         add_image_size('gallery_image--mobile--x2', 540, 732, true);
         add_image_size('gallery_image--mobile', 270, 366, true);

        // 330 x 200
        // Featured image Related posts (News)
        // Featured image Related posts (Work and Services)
        add_image_size('related_post--desktop--x2', 660, 400, true);
        add_image_size('related_post--desktop', 330, 200, true);
        add_image_size('related_post--tablet--x2', 660, 400, true);
        add_image_size('related_post--tablet', 330, 200, true);
        add_image_size('related_post--mobile--x2', 660, 400, true);
        add_image_size('related_post--mobile', 330, 200, true);

        update_option('image_default_size', 'landscape_2_column--desktop' );

        add_image_size('frontPage_xs', 270, 184, true);
        add_image_size('frontPage_sm', 361, 203, true);
        add_image_size('frontPage_md', 576, 330, true);
        add_image_size('frontPage_lg', 760, 506, true);
    }

    public static function customSizesAdmin( $sizes ) {
        return array_merge( $sizes, array(
            'landscape_2_column--desktop' => __( 'Optimized Image Size' ),
            'landscape_2_column_portrait--desktop' => __( 'Optimized Image Size Portrait' ),
        ) );
    }

    /**
     *  Return image url based on Device
     *
     *  @param  {Array} Image Array
     *  @param  {String} image type
     *  @param  {boolean} Return Url Only
     *  @param  {String} Classes
     *  @example serve_image( $array, 'banner', true, false);
     */
     public static function serve_image( $image_array = array(), $type = 'tile', $srcOnly = false, $retina = false, $class = '' )
     {
       $size = $image_array['sizes'];
       $title = $image_array['title'];
       $device = \Utils\Utils::isDevice();

       // key of image size iE tile-desktop
       $key = $type.'__'.$device;
       $key = ($retina) ? $key . '--retina' :  $key ;

       // Use the above key to get the value
       $src = $size[$key];

       // return the image as URL or IMG element
       return ( $srcOnly === true ) ? $src : '<img src="'. $src .'" alt="'. $title .'" class="'.$class.'"/>' ;

     }

     /**
     * Get all the registered image sizes along with their dimensions
     *
     * @global array $_wp_additional_image_sizes
     *
     * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
     * @return array $image_sizes The image sizes
     */
    public static function get_all_image_sizes($thumbnailID, $image_sizes = array( 'thumbnail', 'medium', 'large' )) {
    	global $_wp_additional_image_sizes;

      $images = array();
      $device = \Includes\Utils\Utils::isDevice();

      foreach ( $image_sizes as $size ) {
          $images[$size] = array(
              'width'  => intval( get_option( "{$size}_size_w" ) ),
              'height' => intval( get_option( "{$size}_size_h" ) ),
              'crop'   => get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false,
          );
      }

      if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
          $images = array_merge( $images, $_wp_additional_image_sizes );
      }

      foreach ( $image_sizes as $size ) {
        $images[$size]['img'] = wp_get_attachment_image($thumbnailID, $size );
        $images[$size]['url'] = wp_get_attachment_url($thumbnailID, $size );
        $media = strstr($size, '--', true);
        $images[$media.'__media']['img'] = wp_get_attachment_image($thumbnailID, $media . '--' . $device );
        $images[$media.'__media']['url'] = wp_get_attachment_url($thumbnailID, $media . '--' .$device );
      }

    	return $images;
    }

}
