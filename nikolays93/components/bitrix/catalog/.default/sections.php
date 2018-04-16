<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
// $this->addExternalCss("/bitrix/css/main/bootstrap.css");
Conditions::set_location('catalog', true);
?>
<section class="shop">
	<div class="row">
		<?php /*
		<div class="sidebar <?php echo get_side_column_class();?>">
			<?php include($_SERVER["DOCUMENT_ROOT"] . "/" . $this->GetFolder() . "/sidebar.php"); ?>
		</div>
		*/ ?>
		<div class="catalog <?php echo get_main_column_class();?>">
	        <?php
	        Conditions::set_location('shop', true);
	        include($_SERVER["DOCUMENT_ROOT"] . "/" . $this->GetFolder() . "/catalog.php");
	        ?>
		</div>
	</div>
</section>
