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

        switch ( intval( $columns ) ) {
            case 1: return 'col-12';
            case 2: return 'col-12 col-lg-6';
            case 3: return $RESP ? 'col-12 col-md-6 col-lg-4' : 'col-4';
            case 5: return $RESP ? 'col-md-2-4' : 'col-2-4';
            case 6: return $RESP ? 'col-xl-2 col-lg-3 col-md-4 col-sm-6' : 'col-2';
            case 12: return 'col-1';

            case '3/9': return $RESP ? 'col-12 col-sm-4 col-md-3' : 'col-3';
            case '9/3': return $RESP ? 'col-12 col-sm-8 col-md-9' : 'col-9';

            case '4/8': return $RESP ? 'col-12 col-sm-4' : 'col-4';
            case '8/4': return $RESP ? 'col-12 col-sm-8' : 'col-4';

            case 4:
            default: return $RESP ? 'col-lg-3 col-md-4 col-sm-6' : 'col-3';
        }
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
