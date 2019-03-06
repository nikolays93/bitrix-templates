<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$res = CIBlock::GetByID( $arParams['IBLOCK_ID'] );
if($ar_res = $res->GetNext()) $arParams['IBLOCK_CODE'] = $ar_res['CODE'];

$sectClass = array(
    'news-list',
    $arParams['ITEM_CLASS'] . "-list",
    "news-list_type_" . $arParams['IBLOCK_CODE'],
    "news-list_id_" . $arParams['IBLOCK_ID'],
);
$arResult['SECTION_CLASS'] = implode(' ', $sectClass);

if( empty($arParams['ROW_CLASS']) ) $arParams['ROW_CLASS'] = 'row';
if( empty($arParams['COLUMNS']) ) $arParams['COLUMNS'] = 1;
if( empty($arParams['ITEM_CLASS']) ) $arParams['ITEM_CLASS'] = 'item';
if( empty($arParams["NAME_TAG"]) ) $arParams["NAME_TAG"] = 'h3';

$arParams['COLUMN_CLASS'] = function_exists('get_column_class') ?
    get_column_class($arParams['COLUMNS']) : 'columns-' . $arParams['COLUMNS'];

foreach ($arResult["ITEMS"] as &$arItem) {
    $more = $arParams["MORE_LINK_TEXT"];

    // disable access if is link empty (not exists)
    if( !$arItem["DETAIL_PAGE_URL"] || "#" == $arItem["DETAIL_PAGE_URL"] )
        $arResult["USER_HAVE_ACCESS"] = false;

    $arItem['DETAIL_PAGE_URL'] = ("N" != $arParams["HIDE_LINK_WHEN_NO_DETAIL"]) ||
        ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]) ? $arItem["DETAIL_PAGE_URL"] : false;

    /**
     * @todo
     * /
    if( !empty($arItem['PROPERTIES'][ $arParams['EXTERNAL_LINK_PROPERTY'] ]['VALUE']) ) {
        $arItem['DETAIL_PAGE_URL'] = $arItem['PROPERTIES'][ $arParams['EXTERNAL_LINK_PROPERTY'] ]['VALUE'];
        $more = 'читать в источнике';
    } // */

    if( !empty($arItem['DETAIL_PAGE_URL']) && "Y" == $arParams["DISPLAY_MORE_LINK"] )
        $arItem['DETAIL_PAGE_URL_HTML'] = '<a class="item__more" href="' .$arItem['DETAIL_PAGE_URL']. '">' .$more. '</a>';

    $arItem['HAS_PICTURE'] = "Y" == $arParams["DISPLAY_PICTURE"] && !empty($arItem["PREVIEW_PICTURE"]["SRC"]);
    $arItem['COLUMN_CLASS'] = array($arParams['ITEM_CLASS'], $arParams['COLUMN_CLASS']);
    if( $arItem['HAS_PICTURE'] ) $arItem['COLUMN_CLASS'][] = 'has-picture';
    $arItem['COLUMN_CLASS'] = implode(' ', array_filter($arItem['COLUMN_CLASS']));

    $arItem['HTML'] = array(
        'IMAGE' => '',
        'TITLE' => '',
        'GL_LINK_START' => '',
        'GL_LINK_END' => '',
    );

    if( $arItem['HAS_PICTURE'] ) {
        $arItem['HTML']['IMAGE'] = sprintf('<img src="%s">', $arItem["PREVIEW_PICTURE"]["SRC"]);

        if( "Y" == $arParams['PICTURE_DETAIL_URL'] && !empty($arItem["DETAIL_PICTURE"]["SRC"]) ) {
            $arItem['HTML']['IMAGE'] = sprintf('<a href="%s" class="zoom">%s</a>',
                $arItem["DETAIL_PICTURE"]["SRC"],
                $arItem['HTML']['IMAGE']
            );

            if( "Y" == $arParams['WIDE_GLOBAL_LINK'] ) {
                $arItem['HTML']['IMAGE'] = '<object>' . $arItem['HTML']['IMAGE'] . '</object>';
            }
        }
    }

    if("Y" == $arParams['WIDE_GLOBAL_LINK']) {
        // add wide global link
        $arItem['HTML']['GL_LINK_START'] = '<a href="'. $arItem["DETAIL_PAGE_URL"] .'">';
        $arItem['HTML']['GL_LINK_END'] = '</a>';
    }
    else {
        if("Y" != $arParams['HIDE_GLOBAL_LINK'] && $arItem["DETAIL_PAGE_URL"]) {
            // Add link to image
            if( "Y" != $arParams['PICTURE_DETAIL_URL'] || empty($arItem["DETAIL_PICTURE"]["SRC"]) ) {
                $arItem['HTML']['IMAGE'] = sprintf('<a href="%s">%s</a>',
                    $arItem["DETAIL_PAGE_URL"],
                    $arItem['HTML']['IMAGE']
                );
            }

            // add link to title
            $arItem['HTML']['TITLE'] = sprintf('<a href="%s">%s</a>',
                $arItem["DETAIL_PAGE_URL"],
                $arItem["NAME"]
            );
        }
    }

    if("Y" == $arParams["DISPLAY_NAME"]) {
        if( !$arItem['HTML']['TITLE'] ) $arItem['HTML']['TITLE'] = $arItem["NAME"];

        $arItem['HTML']['TITLE'] = sprintf('<%1$s class="%3$s__name">%2$s</%1$s>',
            $arParams["NAME_TAG"],
            $arItem['HTML']['TITLE'],
            $arParams['ITEM_CLASS']
        );
    }
}
