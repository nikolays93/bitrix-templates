<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main;

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

$documentRoot = Main\Application::getDocumentRoot();
$folder = $this->GetFolder();
?>
<section class='<?=$arResult['SECTION_CLASS'];?>'>
    <?if( $arParams["DISPLAY_TOP_PAGER"] ):?>
    <div class='news-list__pager news-list__pager_top'><?=$arResult["NAV_STRING"];?></div>
    <?endif;?>
    <div class="<?=$arParams['ROW_CLASS'];?>">
        <?
        foreach($arResult["ITEMS"] as $arItem) :
            // add edit areas
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array(
                    "CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM') ) );
            ?>

            <div class="<?= $arItem['COLUMN_CLASS']; ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <?= $arItem['HTML']['GL_LINK_START']; ?>

                    <article class="media <?= $arParams['ITEM_CLASS']; ?>">
                        <div class="<?= $arParams['ITEM_CLASS']; ?>__image"><?= $arItem['HTML']['IMAGE']; ?></div>
                        <div class="media-body <?= $arParams['ITEM_CLASS']; ?>__body">
                            <?= $arItem['HTML']['TITLE']; ?>
                            <?php
                            if( "Y" == $arParams["DISPLAY_DATE"] && $arItem["DISPLAY_ACTIVE_FROM"]) {
                                printf('<div class="%s__date">%s</div>',
                                    $arParams['ITEM_CLASS'],
                                    $arItem["DISPLAY_ACTIVE_FROM"]
                                );
                            }

                            if( "Y" == $arParams["DISPLAY_PREVIEW_TEXT"] && $arItem["PREVIEW_TEXT"]) {
                                printf('<div class="%s__desc">%s</div>',
                                    $arParams['ITEM_CLASS'],
                                    $arItem["PREVIEW_TEXT"]
                                );
                            }
                            ?>
                            <?= $arItem["DETAIL_PAGE_URL_HTML"] . "\r\n"; ?>
                        </div>
                    </article>
                <?= $arItem['HTML']['GL_LINK_END'] . "\r\n"; ?>
            </div>
        <? endforeach; ?>
    </div><!-- .<?=$arParams['ROW_CLASS'];?> -->
    <?if( $arParams["DISPLAY_BOTTOM_PAGER"] ):?>
    <div class='news-list__pager news-list__pager_bottom'><?=$arResult["NAV_STRING"];?></div>
    <?endif;?>
</section>
