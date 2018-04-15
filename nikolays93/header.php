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
    <div class="left-menu">
        <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"ROOT_MENU_TYPE" => "top",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "top",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"LIST_CLASS" => "unstyled"
	),
	false
);?>
    </div>
    <div id="page" class="site">
        <header class="site-header" style="background-image: url(<?$APPLICATION->ShowProperty('head__bg');?>);">
            <div class="container">
                <div class="row align-content-center text-center">
                    <div class="col-1"></div>
                    <div class="col-3">
                        <img src="<?=TPL;?>/img/icon-social__instagram.png">
                        <img src="<?=TPL;?>/img/icon-social__vk.png">
                    </div>
                    <div class="col-4 logotype">
                        <a href="/">
                            <img class="logotype__img" src="/images/logo.png">
                        </a>
                    </div>
                    <div class="col-3 phonenumbers">
                        <img class="phonenumbers__icon" src="<?=TPL;?>/img/icon__phone.png">
                        <span class="prefix">+7 (3412)</span>
                        <p class="number"> 62-01-88 </p>
                        <p class="number"> 68-01-87 </p>
                    </div>
                    <div class="col-1">
                        <img src="<?=TPL;?>/img/icon__cart.png">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 text-center">
                        <h1 class="site-header__title">
                            <?$APPLICATION->ShowProperty('head__title');?>
                        </h1>
                        <p class="site-header__description">
                            <?$APPLICATION->ShowProperty('head__description');?>
                        </p>
                    </div>
                </div>
            </div>
        </header>

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
