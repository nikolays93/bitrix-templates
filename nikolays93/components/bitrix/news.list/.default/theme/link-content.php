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

// included from news.list/{tpl_name}/template.php

$hasPicture = "Y" == $arParams["DISPLAY_PICTURE"] && !empty($arItem["PREVIEW_PICTURE"]["SRC"]);
$class = !$hasPicture ? $itemClass : $itemClass . ' has-picture';
if( empty($arParams["NAME_TAG"]) ) $arParams["NAME_TAG"] = 'h3';

$HTML = array(
    'image' => '',
    'title' => '',
);

if( $hasPicture ) {
    $HTML['image'] = '<img src="'. $arItem["PREVIEW_PICTURE"]["SRC"] .'">';

    if( isset($arParams['PICTURE_DETAIL_URL']) ) {
        if( "Y" == $arParams['PICTURE_DETAIL_URL'] && !empty($arItem["DETAIL_PICTURE"]["SRC"]) ) {
            $HTML['image'] = sprintf('<object><a href="%s" class="zoom">%s</a></object>',
                $arItem["DETAIL_PICTURE"]["SRC"],
                $HTML['image']
            );
        }
    }
    elseif( $arItem["DETAIL_PAGE_URL"] ) {
        $HTML['image'] = sprintf('<a href="%s">%s</a>',
            $arItem["DETAIL_PICTURE"]["SRC"],
            $HTML['image']);
    }
}

if("Y" == $arParams["DISPLAY_NAME"]) {
    $HTML['title'] = sprintf('<%1$s class="%3$s__name">%2$s</%1$s>',
        $arParams["NAME_TAG"],
        $arItem["NAME"],
        'item'
    );
}
?>
        <div class="<?=$class;?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <article class="media item">
                <div class="item__thumb"><?=$HTML['image'];?></div>

                <div class="media-body item__body">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
                        <?=$HTML['title'];?>
                        <?php
                        if( "Y" == $arParams["DISPLAY_DATE"] && $arItem["DISPLAY_ACTIVE_FROM"]) {
                            printf('<div class="item__date">%s</div>', $arItem["DISPLAY_ACTIVE_FROM"]);
                        }

                        if( "Y" == $arParams["DISPLAY_PREVIEW_TEXT"] && $arItem["PREVIEW_TEXT"]) {
                            printf('<div class="item__description">%s</div>', $arItem["PREVIEW_TEXT"]);
                        }
                        ?>
                    </a>
                    <?=$arItem["DETAIL_PAGE_URL_HTML"];?>
                </div>
            </article>
        </div>