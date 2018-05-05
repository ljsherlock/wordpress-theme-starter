<?php

// Wordpress Unique text domain
define('LJS_TEXT_DOMAIN', 'ljsherlock');

define('PROJECT_VERSION', '0.0.0');

// CUTTING THE MUSTARD
if( isset( $_COOKIE['cuts-the-mustard'] ) )
{
    define('_MUSTARD', $_COOKIE['cuts-the-mustard']);
} else {
    define('_MUSTARD', false);
}

// Base locations
define('LJS_ROOT', dirname(__FILE__));
define('LJS_URL', esc_url( home_url( '/' ) ) );
define('LJS_THEME_URL', get_stylesheet_directory_uri());

// Server side
// define('LJS_INC', LJS_ROOT . '/Includes');

//Client Side

define('LJS_VIEW_DIR', LJS_ROOT . '/MVC/View');
define('LJS_VIEW', LJS_THEME_URL . '/MVC/View');

define('LJS_ASSETS_DIR', LJS_VIEW_DIR . '/Assets');
define('LJS_ASSETS', LJS_VIEW . '/Assets');

define('LJS_SCRIPTS_DIR', LJS_ASSETS_DIR . '/Scripts');
define('LJS_SCRIPTS', LJS_ASSETS . '/Scripts');

define('LJS_ICONS_DIR', LJS_ASSETS_DIR . '/Icons');
define('LJS_ICONS', LJS_ASSETS . '/Icons');

define('LJS_CSS_DIR', LJS_ASSETS_DIR . '/CSS');
define('LJS_CSS', LJS_ASSETS . '/CSS');

define('LJS_FAV', LJS_ICONS . '/favicon');

define('LJS_AJAXURL', admin_url('admin-ajax.php'));
define('LJS_INSTAGRAM_AT', '4735961676.3f1743c.4ea5be6a77c2493f912875e8ace885cd');


if (!defined('LJS_ASSET_LOADING_ENABLE_CACHE_BUSTING')) {
    define('LJS_ASSET_LOADING_ENABLE_CACHE_BUSTING', false);
}

if ( ! is_admin() ) {
    define('LJS_CACHE', 600);
}
