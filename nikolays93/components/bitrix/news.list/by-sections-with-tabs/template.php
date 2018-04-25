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

$res = CIBlock::GetByID( $arParams['IBLOCK_ID'] );
if($ar_res = $res->GetNext())
    $arParams['IBLOCK_CODE'] = $ar_res['CODE'];

if( !function_exists('getTermItems') ) {
    function getTermItems( $term ) {
        $arSort = array(
            $arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
            $arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
        );
        if(!array_key_exists("ID", $arSort))
            $arSort["ID"] = "DESC";

        $arFilter = array (
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "IBLOCK_LID" => SITE_ID,
            "ACTIVE" => "Y",
            "CHECK_PERMISSIONS" => $arParams['CHECK_PERMISSIONS'] ? "Y" : "N",
            "SECTION_ID" => $term['ID'],
        );
        if($arParams["CHECK_DATES"])
            $arFilter["ACTIVE_DATE"] = "Y";

        $arSelect = array_merge($arParams["FIELD_CODE"], array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "ACTIVE_FROM",
            "TIMESTAMP_X",
            "DETAIL_PAGE_URL",
            "LIST_PAGE_URL",
            "DETAIL_TEXT",
            "DETAIL_TEXT_TYPE",
            "PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE",
            "PREVIEW_PICTURE",
        ));

        return CIBlockElement::GetList($arSort, $arFilter, false, array(), $arSelect);
    }
}

$columnClass = function_exists('get_column_class') ?
    get_column_class( $arParams['COLUMNS'] ) : 'columns-' . $arParams['COLUMNS'];

echo "<section class='article-list
                      article-list_type_{$arParams['IBLOCK_CODE']}
                      article-list_id_{$arParams['IBLOCK_ID']}'>";

$terms = get_terms_hierarchical($arParams['IBLOCK_ID']);

echo '
<!-- Nav tabs -->
<ul class="nav" id="article-list_type_'.$arParams['IBLOCK_CODE'].'-navs" role="tablist">';
$i = 0;
foreach ($terms as $term) {
    $class = (0 === $i) ? ' active' : '';
    ?>
    <li class="nav-item">
        <a
            id="section_<?=$term['ID'];?>"
            class="nav-link<?=$class;?>"
            href="#section_<?=$term['ID'];?>__content"
            data-toggle="tab"
            role="tab"
            rel="noScroll"
            aria-controls="<?=$term['ID'];?>"
            aria-selected="<?=$class ? 'true' : 'false';?>">
            <?=$term['NAME'];?>
        </a>
    </li>
    <?php
    $elements[ $term['ID'] ] = getTermItems($term);
    $i++;
}
echo "</ul>";

echo '<div class="tab-content" id="nav-tabContent">';
$i = 0;
foreach ($elements as $id => $sectionElements) {
    $class = (0 === $i) ? ' show active' : '';

    printf('<div class="tab-pane fade%s" id="section_%s__content" role="tabpanel"><div class="row">', $class, $id);

    while($element = $sectionElements->GetNextElement()) {
        $arItem = $element->fields;

        $arButtons = CIBlock::GetPanelButtons(
            $arItem["IBLOCK_ID"],
            $arItem["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        $this->AddEditAction($arItem['ID'], $arButtons["edit"]["edit_element"]["ACTION_URL"],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arButtons["edit"]["delete_element"]["ACTION_URL"],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array(
            "CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')
            ) );
    $link = $arParams["HIDE_LINK_WHEN_NO_DETAIL"] ||
        ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]) ? $arItem["DETAIL_PAGE_URL"] : false;

    printf('<article class="element element_type_%s %s" id="%s">',
        $arParams['IBLOCK_CODE'],
        $columnClass,
        $this->GetEditAreaId($arItem['ID'])
    );
    echo '<div class="element__content">';

        if( "Y" == $arParams["DISPLAY_PICTURE"] ) {
            if( is_array($arItem["PREVIEW_PICTURE"]) ) {
                $pp_class = 'element__picture';
                $image = bx_get_image( $arItem["PREVIEW_PICTURE"], $args, true );

                echo ( $link ) ?
                    sprintf('<div class="%s"><a href="%s">%s</a></div>', $pp_class, $args['link'], $image) :
                    sprintf('<div class="%s">%s</div>', $pp_class, $image);
            }
            else {
                echo '<div class="element__picture element_empty"></div>';
            }
        }

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

    echo "</div>";
    echo "</article>";
    }

    echo "</div></div>";

    $i++;
}
echo '</div>';

echo "</section>";
