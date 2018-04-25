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

if( !$arParams["MORE_LINK_TEXT"] ) $arParams["MORE_LINK_TEXT"] = 'Подробнее';

$res = CIBlock::GetByID( $arParams['IBLOCK_ID'] );
if($ar_res = $res->GetNext())
    $arParams['IBLOCK_CODE'] = $ar_res['CODE'];

$columnClass = function_exists('get_column_class') ?
    get_column_class( $arParams['COLUMNS'] ) : 'columns-' . $arParams['COLUMNS'];

echo "<section class='article-list
                      article-list_type_{$arParams['IBLOCK_CODE']}
                      article-list_id_{$arParams['IBLOCK_ID']}'>";

if( $arParams["DISPLAY_TOP_PAGER"] ) {
    echo "<div class='article-list__pager article-list__pager_top'>{$arResult["NAV_STRING"]}</div>";
}

printf('<div class="%s">', $arParams['ROW_CLASS'] ? $arParams['ROW_CLASS'] : 'row');
foreach($arResult["ITEMS"] as $arItem) {
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array(
            "CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM') ) );

    if( !$arItem["DETAIL_PAGE_URL"] || "#" == $arItem["DETAIL_PAGE_URL"] )
        $arResult["USER_HAVE_ACCESS"] = false;
    $link = $arParams["HIDE_LINK_WHEN_NO_DETAIL"] ||
        ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]) ? $arItem["DETAIL_PAGE_URL"] : false;
    $htmlMoreLink = ($link && "Y" == $arParams["DISPLAY_MORE_LINK"]) ?
    sprintf('<a class="element__more" href="%s">%s</a>', $link, $arParams["MORE_LINK_TEXT"]) : '';

    if( "Y" == $arParams['HIDE_GLOBAL_LINK'] )
        $link = false;

    printf('<article class="element element_type_%s %s mb20" id="%s"><div class="inner media">',
        $arParams['IBLOCK_CODE'],
        $columnClass,
        $this->GetEditAreaId($arItem['ID'])
    );

        if( "Y" == $arParams["DISPLAY_PICTURE"] ) {
            if( is_array($arItem["PREVIEW_PICTURE"]) ) {
                $pp_class = 'element__picture alignleft order-first';

                $pic = sprintf('%s', bx_get_image( $arItem["PREVIEW_PICTURE"], $args, true ));
                if( "Y" == $arParams['PICTURE_DETAIL_URL'] && !empty($arItem["DETAIL_PICTURE"]["SRC"]) ) {
                    $pic = sprintf('<a href="%s" class="zoom">%s</a>', $arItem["DETAIL_PICTURE"]["SRC"], $pic);
                }
                elseif ( $link ) {
                    $pic = sprintf('<a href="%s">%s</a>', $link, $pic);
                }

                printf('<div class="%s">%s</div>', $pp_class, $pic);
            }
            else {
                echo '<div class="element__picture element_empty"></div>';
            }
        }

        echo '<div class="media-body order-1">';
        if( "Y" == $arParams["DISPLAY_NAME"] && $arItem["NAME"] ) {
            if( ! $arParams["NAME_TAG"] ) $arParams["NAME_TAG"] = 'h3';
            echo $link ?
                sprintf('<%1$s class="element__title"><a href="%3$s">%2$s</a></%1$s>',
                    $arParams["NAME_TAG"], $arItem["NAME"], $link) :
                sprintf('<%1$s class="element__title">%2$s</%1$s>', $arParams["NAME_TAG"], $arItem["NAME"]);
        }

        if( "Y" == $arParams["DISPLAY_DATE"] && $arItem["DISPLAY_ACTIVE_FROM"]) {
            printf('<div class="element__date">%s</div>', $arItem["DISPLAY_ACTIVE_FROM"]);
        }

        if( "Y" == $arParams["DISPLAY_PREVIEW_TEXT"] && $arItem["PREVIEW_TEXT"]) {
            echo sprintf('<div class="element__description">%s</div>', $arItem["PREVIEW_TEXT"]);
        }

        // echo "<div class='clear'></div>";
        // foreach($arItem["FIELDS"] as $code => $value) {
        //  echo "<small>";
        //  echo GetMessage("IBLOCK_FIELD_" . $code) . ":&nbsp;" . $value;
        //  echo "</small><br />";
        // }

        // foreach($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty) {
        //  echo "<small>";
        //  echo "{$arProperty["NAME"]}:&nbsp;";
        //  if( is_array($arProperty["DISPLAY_VALUE"]) ) {
        //      echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
        //  }
        //  else {
        //      echo $arProperty["DISPLAY_VALUE"];
        //  }
        //  echo "</small><br />";
        // }

        echo $htmlMoreLink;
        echo "</div><!-- .media-body -->";

    echo "</div></article>";
}
echo '</div>';

if( $arParams["DISPLAY_BOTTOM_PAGER"] ) {
    echo "<div class='article-list__pager article-list__pager_bottom'>{$arResult["NAV_STRING"]}</div>";
}

echo "</section>";