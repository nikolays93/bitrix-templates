<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
* @global CMain $APPLICATION
* @global CUser $USER
*/

$sections = Conditions::get_dir_sections();
if( sizeof( $sections ) )
    $APPLICATION->SetPageProperty('page-class', ' page-' . implode(' ', $sections));

if( $filename = Conditions::seekSection('sidebar') ) {
    $APPLICATION->SetPageProperty('content-class', 'col-10');
    Conditions::set_arg('sidebar', $filename);
}

?><!DOCTYPE html>
<html class="no-js" lang="ru-RU">
<head>
    <?$APPLICATION->IncludeFile(
        $APPLICATION->GetTemplatePath("include/head.php"),
        array(),
        array('SHOW_BORDER' => false)
    );?>
</head>
<body class="<?=is_front_page() ? 'home' : 'inner';?>">
    <?$APPLICATION->ShowPanel();?>
    <!--[if lte IE 9]>
        <p class="browserupgrade">Вы используете <strong>устаревший</strong> браузер. Пожалуйста <a href="https://browsehappy.com/">обновите ваш браузер</a> для лучшего отображения и безопасности.</p>
    <![endif]-->

    <div id="page" class="site<?=$APPLICATION->ShowProperty('page-class');?>">
        <header class="site-header">
            <div class="container">
                <div class="row align-content-center" itemscope itemtype="http://schema.org/Organization">
                    <div class="col-3 logotype">
                        <?$APPLICATION->IncludeFile(
                            SITE_DIR . "include/head.logotype.php",
                            array(),
                            Array("MODE" => "html")
                        );?>
                    </div>
                    <div class="col-3 numbers">
                        <?$APPLICATION->IncludeFile(
                            SITE_DIR . "include/head.numbers.php",
                            array(),
                            Array("MODE" => "html")
                        );?>
                    </div>
                    <div class="col-3 contacts">
                        <?$APPLICATION->IncludeFile(
                            SITE_DIR . "include/head.contacts.php",
                            array(),
                            Array("MODE" => "html")
                        );?>
                    </div>
                    <div class="col-3 mini-cart"></div>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <!-- <a class="navbar-brand" href="#">Navbar</a> 
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>-->

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath("include/navbar.php"),
                            array(),
                            array('SHOW_BORDER' => false)
                        );?>
                    </div>
                </div><!-- .container -->
            </nav>
        </header>

        <section class="site-content">
            <?if( !is_front_page() ):?>
            <section class="site-content__breadcrumb breadcrumb">
                <div class="container">
                    <?$APPLICATION->IncludeComponent(
                       "bitrix:breadcrumb", 
                       ".default", 
                       array(
                          "PATH" => "",
                          "SITE_ID" => "s1",
                          "START_FROM" => "0",
                          "COMPONENT_TEMPLATE" => ".default"
                          ),
                       false
                       );?>
                </div>
            </section>
            <?endif;?>

            <div class="<?$APPLICATION->ShowProperty("container-class", "container"); // or container-fluid ?>">
                <div class="row">
                    <?if( $sidebar = Conditions::get_arg('sidebar') ):?>
                        <div class="sidebar-left col-2">
                            <section id="sidebar-left">
                                <?$APPLICATION->IncludeFile(
                                    $sidebar,
                                    array(),
                                    array('SHOW_BORDER' => false)
                                );?>
                            </section>
                        </div>
                    <?endif;?>

                    <div class="site-column site-column_content <?$APPLICATION->ShowProperty(
                        "content-class", 'col-12' );?>">
