<script>
var ajax_url = '<?= LJS_AJAXURL ?>';

var require = {
    baseUrl: '<?= get_template_directory_uri() ?>/MVC/View/Assets/Scripts',
    paths: {
        Polyfills: 'Lib/polyfills',
        Util: 'Utils/util',
        Mustard: 'Lib/mustard',
        Core: 'Core',
        App: 'App',
        Config: 'Lib/appConfig',
    }
};
</script>
<script src="<?php echo get_template_directory_uri(); ?>/MVC/View/Assets/Scripts/Lib/require.js" data-main="Main"></script>
