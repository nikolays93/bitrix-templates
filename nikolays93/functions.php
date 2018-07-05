<?php

if( !function_exists('get_column_class') ) {
    /**
     * Получение класса элемента в bootstrap 4 сетке по количеству элементов в строке
     * @param  Str/Int $columns количество элементов в строке
     * @var Константа определенная в constants.php.
     *      Определяет возможность исползования адаптивной верстки
     * @return String
     */
    function get_column_class( $columns = 0, $less = false, $responsive = null ) {
        if( null === $responsive )
            $responsive = (defined( 'TPL_RESPONSIVE' ) && TPL_RESPONSIVE);

        if( $responsive ) {
            switch ( strval($columns) ) {
                case '1':   $cl = 'col-12'; break;
                case '2':   $cl = 'col-12 col-lg-6'; break;
                case '3':   $cl = 'col-12 col-md-6 col-lg-4'; break;

                case '5':   $cl = 'col-6 col-sm-6 col-md-2-4'; break;
                case '6':   $cl = 'col-6 col-md-4 col-lg-3 col-xl-2'; break;
                case '12':  $cl = 'col-1'; break; // !@#??

                case '3-9': $cl = 'col-12 col-sm-4 col-md-3'; break;
                case '9-3': $cl = 'col-12 col-sm-8 col-md-9'; break;

                case '4-8': $cl = 'col-12 col-sm-4'; break;
                case '8-4': $cl = 'col-12 col-sm-8'; break;

                case '4':
                default: $cl = 'col-6 col-sm-6 col-md-4 col-lg-3'; break;
            }

            if( $less ) { // && is_int($columns) && $columns > 3
                $cl = str_replace('col-6', 'col-12', $cl);
            }
        }
        else {
            $cl = 'col-' . str_replace('.', '-', strval($columns / 12));
        }

        return $cl;
    }
}

if( !function_exists('recursiveTermsUList') ) {
    /**
     * Рекурсивно получить ненумерованный(UL) список секций
     * @param Array  $arSections  Масив обработанный по принципу @see get_terms_hierarchical
     * @param Array   $params     Список параметров
     * @param object  $tpl        Экземпляр шаблона компанента CBitrixComponentTemplate
     *                            В случае, если нужно установить сссылки на редактирование и удаление
     */
    function recursiveTermsUList( $arSections, Array $params, $tpl = false ) {
        if( empty($arSections) || !is_array($arSections) )
            return false;

        $params = bx_parse_args( $params, array(
            'level' => 0,
            'list_class' => 'unstyled',
            'item_class' => 'item',
            'link_class' => 'item__link',
            'count_elements' => false,
        ) );
        $params['level'] = 1;

        printf('<ul class="list-%s%s">',
            $params['list_class'],
            (1 > $params['level']) ? ' sub-list level-' . $params['level'] : '');

        foreach ($arSections as $arSection) {
            if( $tpl ) {
                $tpl->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'],
                    CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                $tpl->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'],
                    CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"),
                    array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));

                printf('<li class="%s" id="%s">', $params['item_class'], $tpl->GetEditAreaId($arSection['ID']) );
            }
            else {
                printf('<li class="%s">', $params['item_class']);
            }

            printf('<a class="%s" href="%s">%s</a>%s',
                $params['link_class'],
                $arSection['SECTION_PAGE_URL'],
                $arSection['NAME'],
                $params["count_elements"] ?
                    sprintf('<small>(%d)</small>', $arSection['ELEMENT_CNT']) : ''
            );

            if( isset($arSection['CHILD']) && is_array($arSection['CHILD']) )
                recursiveTermsUList($arSection['CHILD'], $params);

            echo "</li>";
        }
        printf('</ul><!-- .list-%s -->', $params['list_class']);
    }
}

if( !function_exists('get_default_catalog_args') ) {
    /**
     * * - Эти параметры компонента на момент написания этого файла отличались от документации
     * //* - Означает что этот параметр был присвоен моим компонентом, но его нет в документации
     *
     * @date 31/05/18
     * @link https://dev.1c-bitrix.ru/user_help/components/content/catalog/catalog.php
     * @param  mixed $blocks  select prop block keycode
     * @return array          default args
     */
    function get_default_catalog_args( $keycode = null, &$arParams = array() ) {
        // catalog.section = ['IBLOCK', 'FILTER', 'TPL', 'TPL_MESSAGES', 'URLS', 'CACHE', 'PRICE', 'BASKET', 'SLIDER']

        /** Основные параметры */
        $arrParams['IBLOCK'] = array(
            'IBLOCK_TYPE' => '1c_catalog', // Тип инфоблока
            'IBLOCK_ID'   => 7, // ID инфоблока
            'COMPONENT_TEMPLATE' => 'books',
            'FILTER_NAME' => 'arrCatalogFilter',
            // 'CUSTOM_FILTER' => '',
        );

        /** Источник данных */
        $arrParams['FILTER'] = array(
            'HIDE_NOT_AVAILABLE'        => 'N', // Недоступные товары
            'HIDE_NOT_AVAILABLE_OFFERS' => 'N', // Недоступные торговые предложения
        );

        /** Внешний вид */
        $arrParams['TPL'] = array(
            'TEMPLATE_THEME'          => 'blue', // Цветовая тема
            'COMMON_SHOW_CLOSE_POPUP' => 'Y', //*Показывать кнопку продолжения покупок во всплывающих окнах
            'PRODUCT_SUBSCRIPTION'    => 'Y', // Разрешить "Подписку" к товарам
            'SHOW_DISCOUNT_PERCENT'   => 'N', // Показывать процент скидки
            'SHOW_OLD_PRICE'          => 'Y', // Показывать старую цену
            'SHOW_MAX_QUANTITY'       => 'N', // Показывать остаток товара

            'ADD_PICT_PROP'            => '-', // Дополнительная картинка основного товара
            'LABEL_PROP'               => '', //* Свойство меток товара
            'PRODUCT_DISPLAY_MODE'     => '', //* Схема отображения
            'OFFER_ADD_PICT_PROP'      => '-', //* Дополнительные картинки предложения
            'OFFER_TREE_PROPS'         => '', //* Свойства для отбора предложений
            'DETAIL_SHOW_MAX_QUANTITY' => '', //* Показывать общее количество товара на детальной странице
        );

        /** Сообщения */
        $arrParams['TPL_MESSAGES'] = array(
            'MESS_BTN_BUY'           => 'Купить', // Текст кнопки "Купить"
            'MESS_BTN_ADD_TO_BASKET' => 'В корзину', // Текст кнопки "Добавить в корзину"
            'MESS_BTN_COMPARE'       => 'Сравнить', //*Текст кнопки "Сравнение"
            'MESS_BTN_DETAIL'        => 'Подробнее', // Текст кнопки "Подробнее"
            'MESS_NOT_AVAILABLE'     => 'Нет в наличии', // Сообщение об отсутствии товара
            'MESS_BTN_SUBSCRIBE'     => 'Подписаться', // Текст кнопки "Подписаться"
        );

        /** Сайдбар */
        $arrParams['TPL_SIDEBAR'] = array(
            'SIDEBAR_SECTION_SHOW' => 'N', // Показывать правый блок в списке товаров
            'SIDEBAR_DETAIL_SHOW'  => 'N', // Показывать правый блок на детальной странице
            'SIDEBAR_PATH'         => '', //*Путь к включаемой области для sidebar'а
        );

        /** Сео настройка (ЧПУ) */
        $arrParams['URLS'] = array(
            'SEF_MODE' => 'Y',
            'SEF_FOLDER' => '/catalog/',
            'SEF_URL_TEMPLATES' => array(
                'sections'=>'',
                'section'=>'cat/#SECTION_CODE_PATH#/',
                'element'=>'#ELEMENT_CODE#/',
                'compare'=>'compare.php?action=#ACTION_CODE#',
                'smart_filter'=>'#SECTION_ID#/filter/#SMART_FILTER_PATH#/apply/',
            ),
            // if N == SEF_MODE
            // 'SECTION_ID' => '',
            // 'ELEMENT_ID' => '',
            'SEF_RULE' => '',
        );

        /* AJAX режим */
        $arrParams['AJAX'] = array(
            'AJAX_MODE'              => 'N', // Включить режим AJAX
            'AJAX_OPTION_JUMP'       => 'N', // Включить прокрутку к началу компонента
            'AJAX_OPTION_STYLE'      => 'Y', // Включить подгрузку стилей
            'AJAX_OPTION_HISTORY'    => 'N', // Включить эмуляцию навигации браузера
            'AJAX_OPTION_ADDITIONAL' => '', //*
        );

        /** Кэширование */
        $arrParams['CACHE'] = array(
            'CACHE_TYPE'   => 'A', // Тип кеширования
            'CACHE_TIME'   => '36000000', // Время кеширования (сек.)
            'CACHE_FILTER' => 'N', // Кешировать при установленном фильтре
            'CACHE_GROUPS' => 'Y', // Учитывать права доступа
        );

        /** Дополнительные настройки */
        $arrParams['ADVANCED'] = array(
            'SET_LAST_MODIFIED'           => 'N', // Устанавливать в заголовках ответа время модификации страницы
            'USE_MAIN_ELEMENT_SECTION'    => 'N', // Использовать основной раздел для показа элемента
            'DETAIL_STRICT_SECTION_CHECK' => 'N', //*Строгая проверка раздела для детального показа элемента
            'SET_BROWSER_TITLE'           => 'Y', //*
            'SET_TITLE'                   => 'Y', // Устанавливать заголовок страницы
            'ADD_SECTIONS_CHAIN'          => 'Y', // Включать раздел в цепочку навигации
            'ADD_ELEMENT_CHAIN'           => 'N', //*Включать название элемента в цепочку навигации
            'USE_SALE_BESTSELLERS'        => 'N', // Показывать список лидеров продаж
        );

        /** Настройки фильтра */
        /** Настройки отзывов */

        /** Настройки переменных */
        $arrParams['VARIABLES'] = array(
            'ACTION_VARIABLE'     => 'action', // Название переменной, в которой передается действие
            'PRODUCT_ID_VARIABLE' => 'id', // Название переменной, в которой передается код товара для покупки
        );

        /** Сравнение товаров */
        $arrParams['COMPARE'] = array(
            'DISPLAY_COMPARE' => 'N', //*
            'USE_COMPARE'     => 'N', //*Разрешить сравнение товаров
        );

        /** Цена/Стоимость */
        $arrParams['PRICE'] = array(
            'PRICE_CODE' => array( // Тип цены
                0 => 'BASE',
            ),
            'USE_PRICE_COUNT'      => 'N', // Использовать вывод цен с диапазонами
            'SHOW_PRICE_COUNT'     => '1', // Выводить цены для количества
            'PRICE_VAT_INCLUDE'    => 'Y', // Включать НДС в цену
            'PRICE_VAT_SHOW_VALUE' => 'N', //*Отображать значение НДС
            'CONVERT_CURRENCY'     => 'N', // Показывать цены в одной валюте
        );

        /** Корзина */
        $arrParams['BASKET'] = array(
            'BASKET_URL'                       => defined('PATH_TO_BASKET')? PATH_TO_BASKET : '/basket/', // URL, ведущий на страницу с корзиной покупателя
            'USE_PRODUCT_QUANTITY'             => 'N', // Разрешить указание количества товара
            'QUANTITY_FLOAT'                   => 'N', //*Разрешить указание дробного количества товара
            'ADD_PROPERTIES_TO_BASKET'         => 'Y', // Добавлять в корзину свойства товаров и предложений
            // 'USE_COMMON_SETTINGS_BASKET_POPUP' => '', //*Одинаковые настройки показа кнопок добавления в корзину или покупки на всех страницах
            // 'COMMON_ADD_TO_BASKET_ACTION'      => '', //*Показывать кнопку добавления в корзину или покупки
            // 'TOP_ADD_TO_BASKET_ACTION'         => '', //*Показывать кнопку добавления в корзину или покупки на странице с top'ом товаров
            'SECTION_ADD_TO_BASKET_ACTION'     => 'ADD', //*Показывать кнопку добавления в корзину или покупки на странице списка товаров
            // 'DETAIL_ADD_TO_BASKET_ACTION'      => '', //*Показывать кнопки добавления в корзину и покупки на детальной странице товара
            // 'DETAIL_SHOW_BASIS_PRICE'          => '', //*Показывать на детальной странице цену за единицу товара
        );

        /** Поиск */
        // $arrParams['SEARCH']
        // RESTART
        // NO_WORD_LOGIC
        // USE_LANGUAGE_GUESS
        // CHECK_DATES

        /** Популярные(лучшие) товары */
        $arrParams['TOP'] = array(
            'SHOW_TOP_ELEMENTS' => 'N' // Выводить топ элементов
        );

        /** Настройки списка разделов */
        $arrParams['SECTION_LIST'] = array(
            'SECTION_COUNT_ELEMENTS' => 'N', //* Показывать количество элементов в разделе
            'SECTION_TOP_DEPTH' => 1, // Максимальная отображаемая глубина разделов
            'SECTIONS_VIEW_MODE' => 'TILE', //* Вид списка подразделов
            'SECTIONS_SHOW_PARENT_NAME' => 'Y', //*Показывать название раздела
        );

        $arrParams['SECTION'] = array(
            'PAGE_ELEMENT_COUNT'        => '9', // Количество элементов на странице
            'LINE_ELEMENT_COUNT'        => '3', // Количество элементов, выводимых в одной строке таблицы
            'ELEMENT_SORT_FIELD'        => 'sort', // По какому полю сортируем товары в разделе
            'ELEMENT_SORT_FIELD2'       => 'id', // Порядок сортировки товаров в разделе
            'ELEMENT_SORT_ORDER'        => 'asc', // Поле для второй сортировки товаров в разделе
            'ELEMENT_SORT_ORDER2'       => 'desc', // Порядок второй сортировки товаров в разделе   
            // 'LIST_PROPERTY_CODE'        => array(), // Свойства
            'INCLUDE_SUBSECTIONS'       => 'Y', // Показывать элементы подразделов раздела
            // 'LIST_META_KEYWORDS'        => '', // Установить ключевые слова страницы из свойства раздела 
            // 'LIST_META_DESCRIPTION'     => '', // Установить описание страницы из свойства раздела
            'LIST_BROWSER_TITLE'        => '', // Установить заголовок окна браузера из свойства раздела
            // 'LIST_OFFERS_FIELD_CODE'    => '', // Поля предложений
            // 'LIST_OFFERS_PROPERTY_CODE' => '', // Свойства предложений
            'LIST_OFFERS_LIMIT'         => 5, // Максимальное количество предложений для показа
            'SECTION_BACKGROUND_IMAGE'  => '-', // Установить фоновую картинку для шаблона из свойства
            // ------------ in catalog.section
            'SECTION_USER_FIELDS' => array(),
            'SHOW_ALL_WO_SECTION' => 'Y',
            'ENLARGE_PRODUCT' => 'STRICT',
            'PRODUCT_BLOCKS_ORDER' => 'price,props,sku,quantityLimit,quantity,buttons,compare',
            'SHOW_FROM_SECTION' => 'N',
        );
        $arrParams['SECTION']['PROPERTY_CODE'] = $arrParams['SECTION']['LIST_PROPERTY_CODE'];
        $arrParams['SECTION']['PROPERTY_CODE_MOBILE'] = $arrParams['SECTION']['LIST_PROPERTY_CODE'];
        $arrParams['SECTION']['OFFERS_LIMIT'] = $arrParams['SECTION']['LIST_OFFERS_LIMIT'];
        $arrParams['SECTION']['BACKGROUND_IMAGE'] = $arrParams['SECTION']['SECTION_BACKGROUND_IMAGE'];
        $arrParams['SECTION']['ADD_TO_BASKET_ACTION'] = $arrParams['BASKET']['SECTION_ADD_TO_BASKET_ACTION'];
        $arrParams['SECTION']['BROWSER_TITLE'] = $arrParams['SECTION']['LIST_BROWSER_TITLE'];


        $arrParams['DETAIL'] = array(
            // 'DETAIL_PROPERTY_CODE'             => '', //*
            // 'DETAIL_META_KEYWORDS'             => '', //*
            // 'DETAIL_META_DESCRIPTION'          => '', //*
            // 'DETAIL_BROWSER_TITLE'             => '', //*
            // 'DETAIL_SET_CANONICAL_URL'         => '', //*
            'SECTION_ID_VARIABLE'              => 'SECTION_ID', // Название переменной, в которой передается код группы
            // 'DETAIL_CHECK_SECTION_ID_VARIABLE' => '', //*
            // 'DETAIL_OFFERS_FIELD_CODE'         => '', //*
            // 'DETAIL_OFFERS_PROPERTY_CODE'      => '', //*
            // 'DETAIL_BACKGROUND_IMAGE'          => '', //*
            // 'SHOW_DEACTIVATED'                 => '', //*
            // 'DETAIL_USE_VOTE_RATING'           => '', //*
            // 'DETAIL_USE_COMMENTS'              => '', //*
            // 'DETAIL_BRAND_USE'                 => '', //*
            // 'DETAIL_DISPLAY_NAME'              => '', //*
            // 'DETAIL_DETAIL_PICTURE_MODE'       => '', //*
            // 'DETAIL_ADD_DETAIL_TO_SLIDER'      => '', //*
            // 'DETAIL_DISPLAY_PREVIEW_TEXT_MODE' => '', //*
            // 'DETAIL_PRODUCT_INFO_BLOCK_ORDER'  => '', //*
            // 'DETAIL_PRODUCT_PAY_BLOCK_ORDER'   => '', //*
        );

        $arrParams['SLIDER'] = array(
            'SHOW_SLIDER'                      => 'Y', // Показывать слайдер для товаров
            'SLIDER_INTERVAL'                  => '4000', // Интервал смены слайдов, мс
            'SLIDER_PROGRESS'                  => 'N', // Показывать полосу прогресса
        );


// 'BROWSER_TITLE' => '-',
// 'COMPATIBLE_MODE' => 'Y',
// 
// 'DETAIL_URL' => '',
// 'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
// 'DISPLAY_BOTTOM_PAGER' => 'N',
// 'DISPLAY_TOP_PAGER' => 'N',


// 'LAZY_LOAD' => 'N',
// 
// 'LOAD_ON_SCROLL' => 'N',
// 'MESSAGE_404' => '',

// 'META_DESCRIPTION' => '-',
// 'META_KEYWORDS' => '-',
// 'PAGER_BASE_LINK_ENABLE' => 'N',
// 'PAGER_DESC_NUMBERING' => 'N',
// 'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
// 'PAGER_SHOW_ALL' => 'N',
// 'PAGER_SHOW_ALWAYS' => 'N',
// 'PAGER_TEMPLATE' => '.default',
// 'PAGER_TITLE' => 'Товары',
// 'PARTIAL_PRODUCT_PROPERTIES' => 'N',

// 'PRODUCT_PROPERTIES' => array(
// ),
// 'PRODUCT_PROPS_VARIABLE' => 'prop',
// 'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
// 'PRODUCT_ROW_VARIANTS' => '[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]',
// 


// 'RCM_PROD_ID' => $_REQUEST['PRODUCT_ID'],
// 'RCM_TYPE' => 'personal',
// 'SECTION_CODE' => '',
// 'SECTION_ID' => $_REQUEST['SECTION_ID'],

// 'SECTION_URL' => '',




// 'SET_META_DESCRIPTION' => 'Y',
// 'SET_META_KEYWORDS' => 'Y',
// 'SET_STATUS_404' => 'N',

// 'SHOW_404' => 'N',
// 'SHOW_CLOSE_POPUP' => 'N',
// 'USE_ENHANCED_ECOMMERCE' => 'N',




        if( !$keycode ) {
            foreach ($arrParams as $params_block) {
                $arParams = array_merge($arParams, $params_block);
            }
        }
        elseif( is_string($keycode) && isset($arrParams[ $keycode ]) ) {
            $arParams = $arrParams[ $keycode ];
        }
        elseif( is_array($keycode) ) {
            foreach ($keycode as $code) {
                if( isset($arrParams[ $code ]) )
                    $arParams = array_merge($arParams, $arrParams[ $code ]);
            }
        }

        return $arParams;
    }
}
