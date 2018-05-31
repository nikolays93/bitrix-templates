<?php

if( !function_exists('get_column_class') ) {
    /**
     * Получение класса элемента в bootstrap 4 сетке по количеству элементов в строке
     * @param  Integer $columns количество элементов в строке
     * @var Константа определенная в constants.php.
     *      Определяет возможность исползования адаптивной верстки
     * @return String
     */
    function get_column_class( $columns, $RESP = null ) {
        if( null === $RESP )
            $RESP = (defined( 'TPL_RESPONSIVE' ) && TPL_RESPONSIVE);

        switch ( $columns ) {
            case '1': return 'col-12';
            case '2': return 'col-12 col-lg-6';
            case '3': return $RESP ? 'col-12 col-md-6 col-lg-4' : 'col-4';
            case '5': return $RESP ? 'col-md-2-4' : 'col-2-4';
            case '6': return $RESP ? 'col-xl-2 col-lg-3 col-md-4 col-sm-6' : 'col-2';
            case '12': return 'col-1';

            case '3-9': return $RESP ? 'col-12 col-sm-4 col-md-3' : 'col-3';
            case '9-3': return $RESP ? 'col-12 col-sm-8 col-md-9' : 'col-9';

            case '4-8': return $RESP ? 'col-12 col-sm-4' : 'col-4';
            case '8-4': return $RESP ? 'col-12 col-sm-8' : 'col-8';

            case '4':
            default: return $RESP ? 'col-lg-3 col-md-4 col-sm-6' : 'col-3';
        }
    }
}

if( !function_exists('get_main_column_class') ) {
    function get_main_column_class()
    {
        // if( Conditions::is_catalog() )
        //     return get_column_class('9-3');

        if( function_exists( 'get_column_class' ) ) {
            return Conditions::is_show_sidebar() ? get_column_class('9-3') : 'col-12';
        }

        return Conditions::is_show_sidebar() ? 'col-9' : 'col-12';
    }
}

if( !function_exists('get_side_column_class') ) {
    function get_side_column_class()
    {
        // if( Conditions::is_catalog() )
        //     return get_column_class('3-9');

        if( function_exists( 'get_column_class' ) ) {
            return Conditions::is_show_sidebar() ? get_column_class('3-9') : 'hidden';
        }

        return Conditions::is_show_sidebar() ? 'col-3' : 'hidden';
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
        /** Основные параметры */
        $arrParams['IBLOCK'] = array(
            'IBLOCK_TYPE' => '1c_catalog', // Тип инфоблока
            'IBLOCK_ID'   => 7, // ID инфоблока
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
