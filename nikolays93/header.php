<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if( ! defined('TPL') ) define('TPL', SITE_TEMPLATE_PATH);
$RESP = defined('TPL_RESPONSIVE') || TPL_RESPONSIVE;

/**
* @global CMain $APPLICATION
* @global CUser $USER
*/
?><!DOCTYPE html>
<html lang="ru-RU">
<head>
    <?$APPLICATION->IncludeFile(
        $APPLICATION->GetTemplatePath("include/head.php"),
        array(),
        array('SHOW_BORDER' => false)
    );?>
</head>
<body class="<?$APPLICATION->ShowProperty('body-class');?>">
    <?$APPLICATION->ShowPanel();?>

    <div id="page" class="site">
        <header class="site-header container">
            <div class="container">
                <div class="row">
                    <div class="col-3 site-header__logotype">
                        <a href="/"><img src="<?=TPL?>/img/logo.png" class="logotype"></a>
                    </div><!-- .logotype -->

                    <div class="col-3 site-header__phone">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/head_phone.php" // [;)
                        ), false);
                        ?>
                    </div><!-- .site-header__phone -->

                    <div class="col-3 site-header__offer">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/head_offer.php"
                        ), false);
                        ?>
                    </div><!-- .site-header__offer -->
                </div><!-- .row -->
            </div>

            <nav class="site-header__navigation navbar navbar-expand-lg navbar-light bg-light">
                <!-- <a class="navbar-brand" href="/"><img src="<?=TPL?>/img/logo.png" class="logotype"></a> -->
                <?if( $RESP ):?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <?endif?>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "bootstrap",
                        array(
                            "COMPONENT_TEMPLATE" => "bootstrap",
                            "ROOT_MENU_TYPE" => "top",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => array(
                            ),
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "N",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N"
                        ),
                        false
                        );
                    ?>
                </div><!-- .collapse -->
            </nav>
        </header><!-- .site-header -->

        <section class="site-content">
            <?if( ! is_front_page() ):?>
            <section class="container site-content__breadcrumb">
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
            </section>
            <?endif;?>

            <div class="<?$APPLICATION->ShowProperty("container-class", "container"); // or container-fluid ?>">
                <div class="row">
                    <div class="site-column site-column_content <?$APPLICATION->ShowProperty(
                        "content-class", DEFAULT_COLUMN_MAIN );?>">
