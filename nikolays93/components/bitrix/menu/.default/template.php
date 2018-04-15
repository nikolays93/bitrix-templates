<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if( !$arParams["LIST_CLASS"] ) $arParams["LIST_CLASS"] = 'unstyled';
if( !empty( $arResult ) ) {
    printf('<ul class="list-%s menu">', $arParams["LIST_CLASS"]);

    foreach($arResult as $arItem) {
        if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;

        printf('<li><a href="%s" class="%s">%s</a></li>',
            $arItem["LINK"],
            $arItem["SELECTED"] ?
                sprintf('list-%s-item active selected', $arParams["LIST_CLASS"]) :
                sprintf('list-%s-item', $arParams["LIST_CLASS"]),
            $arItem["TEXT"] );
    }

    echo '</ul>';
}
