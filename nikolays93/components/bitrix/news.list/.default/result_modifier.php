<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$res = CIBlock::GetByID( $arParams['IBLOCK_ID'] );
if($ar_res = $res->GetNext())
    $arParams['IBLOCK_CODE'] = $ar_res['CODE'];

foreach ($arResult["ITEMS"] as &$arItem) {
    $more = $arParams["MORE_LINK_TEXT"];

    // disable access if is link empty (not exists)
    if( !$arItem["DETAIL_PAGE_URL"] || "#" == $arItem["DETAIL_PAGE_URL"] )
        $arResult["USER_HAVE_ACCESS"] = false;

    $arItem['DETAIL_PAGE_URL'] = ("N" != $arParams["HIDE_LINK_WHEN_NO_DETAIL"]) ||
        ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]) ? $arItem["DETAIL_PAGE_URL"] : false;

    if( !empty($arItem['PROPERTIES']['EXTERNAL_LINK']['VALUE']) ) {
        $arItem['DETAIL_PAGE_URL'] = $arItem['PROPERTIES']['EXTERNAL_LINK']['VALUE'];
        $more = 'читать в источнике';
    }

    if( !empty($arItem['DETAIL_PAGE_URL']) && "Y" == $arParams["DISPLAY_MORE_LINK"] )
        $arItem['DETAIL_PAGE_URL_HTML'] = '<a class="item__more" href="' .$arItem['DETAIL_PAGE_URL']. '">' .$more. '</a>';

    if( "Y" == $arParams['HIDE_GLOBAL_LINK'] )
        $arItem['DETAIL_PAGE_URL'] = '';
}
