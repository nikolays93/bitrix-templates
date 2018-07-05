<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
?>
                    </div><!-- .site-content__main-column -->
                </div><!-- .row -->
            </div><!-- .container -->
        </section><!-- .site-content -->

        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <?$APPLICATION->IncludeComponent("bitrix:menu", "", Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(
                                0 => "",
                                ),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "Bottom1",
                            "USE_EXT" => "N",
                            ),
                        false
                        );?>
                    </div>
                    <div class="col-3">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu", 
                            "", 
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(
                                    ),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "Bottom2",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => ""
                                ),
                            false
                            );?>
                    </div>
                    <div class="col-3">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/foot_column3.php"
                        ), false);
                        ?>
                    </div>
                    <div class="col-3">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/foot_column4.php"
                        ), false);
                        ?>
                    </div>
                </div><!-- .row -->
            </div>
        </footer><!-- #site-footer -->
    </div><!-- #page -->
    <?$APPLICATION->IncludeFile(
        $APPLICATION->GetTemplatePath("include/foot.php"),
        array(),
        array('SHOW_BORDER' => false)
    );?>
</body>
</html>