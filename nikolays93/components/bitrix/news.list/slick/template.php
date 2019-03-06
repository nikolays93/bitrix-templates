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

if( !sizeof($arResult["ITEMS"]) ) return;

/**
 * @todo try exclude this from component
 */
$arParams["DISPLAY_TOP_PAGER"] = false;
$arParams["DISPLAY_BOTTOM_PAGER"] = false;
$arParams["DISPLAY_PICTURE"] = "Y";
$arParams["DISPLAY_DATE"] = "N";
$arItem["DETAIL_PAGE_URL"] = '';

/**
 * Include in component
 */
$arParams["EXCLUDE_STYLE"]  = true;
$arParams["EXCLUDE_THEME"]  = true;
$arParams["EXCLUDE_SCRIPT"] = true;

if( !$arParams["EXCLUDE_STYLE"] )  $this->addExternalCss($templateFolder . "/assets/slick/slick.css");
if( !$arParams["EXCLUDE_THEME"] )  $this->addExternalCss($templateFolder . "/assets/slick/slick-theme.css");
if( !$arParams["EXCLUDE_SCRIPT"] ) $this->addExternalJS ($templateFolder . "/assets/slick/slick.min.js");

$rnd = randString(6);
$imageLink = $arItem["DETAIL_PAGE_URL"] && !$arParams["HIDE_LINK_WHEN_NO_DETAIL"] ||
    ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]);
?>
<div class="slick" id="slick-<?= $rnd ?>">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="slick__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<?if( $imageLink ):?>
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
    <?endif?>
        <picture>
            <?if(!empty($arItem["PREVIEW_PICTURE"]["SRC"])):?>
            <source srcset="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                media="(max-width: 575px)"
                class="slick__picture slick__preview-picture"
                width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                alt="<?=$arParams["DISPLAY_NAME"]!="N" ? $arItem["NAME"] : $arItem["PREVIEW_PICTURE"]["ALT"]?>"
                title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
            >
            <?endif;?>
            <img
                class="slick__picture"
                src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>"
                width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>"
                height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>"
                alt="<?=$arParams["DISPLAY_NAME"]!="N" ? $arItem["NAME"] : $arItem["DETAIL_PICTURE"]["ALT"]?>"
                title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>"
            />
        </picture>

        <?if( $imageLink ):?>
        </a>
        <?endif;?>

		<?/*if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;*/?>
        <?$detail = trim($arItem["DETAIL_TEXT"]);
        if( !empty($detail) ):
            list($column1, $column2) = explode('<!--column-->', $detail); ?>
        <div class="slick__item--detail-text">
            <div class="container">
                <p class="mobile-name"><?= $arItem["~NAME"] ?></p>
                <div class="row">
                    <div class="column column--1"><?= $column1 ?></div>
                    <div class="column column--2"><?= "<h3>{$arItem["~NAME"]}</h3>" . $column2 ?></div>
                </div>
            </div>
        </div>
		<?endif;
        /*foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;*/?>
	</div>
<?endforeach;?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery(document).ready(function($) {
            var slick = new slickSlider('#slick-<?= $rnd ?>', {
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true,
                responsive: [{
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
            }]});

            // once init
            slick.init();
            // init on window.width < 992
            // slick.responsive( 992 );
        });
    });
</script>
