<?php

if( !function_exists('body_class') ) {
    function body_class()
    {
        global $APPLICATION;

        /**
         * @todo
         * $APPLICATION->AddBufferContent('ShowBodyClass');
         */

        $class = '';
        if( class_exists('Conditions') ) {
            $sections = Conditions::get_dir_sections();

            if( function_exists('esc_attr') )
                $sections = array_filter($sections, 'esc_attr');

            $class = 'page-' . implode(' ', $sections);
        }

        echo 'class="';
        echo $class . ' ';
        $APPLICATION->ShowProperty('body-class');
        echo '"';
    }
}

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

                case '4':
                default: $cl = 'col-6 col-sm-6 col-md-4 col-lg-3'; break;
            }

            if( $less ) { // && is_int($columns) && $columns > 3
                $cl = str_replace('col-6', 'col-12', $cl);
            }
        }
        else {
            $cl = 'col-' . str_replace('.', '-', strval(12 / $columns));
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
