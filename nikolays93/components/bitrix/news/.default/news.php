<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?php if("Y" == $arParams["USE_RSS"]) : ?>
    <div class="rss-component">
        <?php
        if(method_exists($APPLICATION, 'addheadstring')) {
            $APPLICATION->AddHeadString(
                sprintf('<link rel="alternate" type="application/rss+xml" title="%1$s" href="%1$s" />',
                    $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["rss"])
            );
        }

        printf('<a href="%s" title="rss" target="_self"><img alt="RSS" src="%s" class="alignright" /></a>',
            $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["rss"],
            $templateFolder . '/images/feed-icon-16x16.png');
        ?>
    </div>
<?php endif; ?>

<?php if("Y" == $arParams["USE_SEARCH"]) : ?>
    <div class="search-component">
        <?php
        echo GetMessage("SEARCH_LABEL");
        $APPLICATION->IncludeComponent(
            "bitrix:search.form",
            "flat",
            Array(
                "PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"]
            ),
            false // $component get default template
        );
        ?>
    </div>
<?php endif; ?>

<?php if("Y" == $arParams["USE_FILTER"]) : ?>
    <div class="filter-component">
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.filter",
            "",
            Array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
                "PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            ),
            false // $component get default template
        );
        ?>
    </div>
<?php endif; ?>


<?
$arParams['COLUMNS'] = 1;
$arParams['DISPLAY_MORE_LINK'] = 'Y';
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "archive",
    $arParams, // experiment
    $component
);?>
