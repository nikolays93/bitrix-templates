<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

if( !defined('TPL') )
    define('TPL', SITE_TEMPLATE_PATH);

$assets = \Bitrix\Main\Page\Asset::getInstance();
$min = ("N" == \Bitrix\Main\Config\Option::get("main", "use_minified_assets")) ? '' : '.min';
?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?
    if( defined('TPL_RESPONSIVE') && TPL_RESPONSIVE ) {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    }
    else {
        ?>
        <meta name="viewport" content="width=1200">
        <style type="text/css"> .container {
            min-width: 1200px !important;
            max-width: 1200px !important;
        } </style>
        <?php
    }

    /**
     * CSS
     * @see also: Настройки > Настройки продукта > Настройки модулей > Настройки главного модуля => Оптимизация CSS
     */
    // $assets->addCss('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/assets/font-awesome.min.css');
    // $assets->addCss(TPL . '/assets/animate.min.css');
    // $assets->addCss(TPL . '/assets/jquery.formstyler.css');
    if( function_exists('update_theme_style') ) update_theme_style();
    $assets->addCss(TPL . '/assets/bootstrap'.$min.'.css');

    /**
     * JS
     */
    // \CJSCore::Init( array('jquery') );
    $assets->addJs('http://code.jquery.com/jquery-3.2.1.min.js');
    $assets->addJs(TPL . '/assets/bootstrap'.$min.'.js');
    // $assets->addJs(TPL . '/assets/jquery.maskedinput.min.js');

    /** fancybox */
    // $assets->addCss(TPL . '/assets/fancybox/jquery.fancybox'.$min.'.css');
    // $assets->addJs(TPL . '/assets/fancybox/jquery.fancybox'.$min.'.js');

    /** slick */
    // $assets->addCss(TPL . 'assets/slick/slick-theme.css');
    // $assets->addCss(TPL . '/assets/slick/slick'.$min.'.css');
    // $assets->addJs(TPL . '/assets/slick/slick'.$min.'.js');

    /** Masonry */
    // $assets->addJs(TPL . '/assets/masonry/masonry.pkgd'.$min.'.js');

    /** VK Api */
    // $assets->addJs('https://vk.com/js/api/openapi.js?152');

    // $assets->addJs(TPL . '/assets/script.js');

    /**
     * BITRIX ->ShowHead()
     */
    CJSCore::Init(array("fx"));
    $assets->addString("<link rel='shortcut icon' href='/favicon.ico' />");

    $APPLICATION->ShowMeta("robots", false);
    $APPLICATION->ShowMeta("keywords", false);
    $APPLICATION->ShowMeta("description", false);
    $APPLICATION->ShowLink("canonical", null);
    $APPLICATION->ShowCSS(true);
    $APPLICATION->ShowHeadStrings();
    $APPLICATION->ShowHeadScripts();
    ?>
    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title><? $APPLICATION->ShowTitle() ?></title>
