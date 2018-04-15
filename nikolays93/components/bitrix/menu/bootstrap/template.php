<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if( !empty($arResult) ) {
    echo "<ul class='navbar-nav mr-auto'>";

    foreach($arResult as $arItem) {
        if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
            continue;

        $class = ($arItem["SELECTED"]) ? 'nav-link active' : 'nav-link';
        echo sprintf('<li class="menu-item nav-item"><a href="%s" class="nav-link">%s</a></li>',
            $arItem["LINK"],
            $arItem["TEXT"]
        );
    }

    echo "</ul>";
}
