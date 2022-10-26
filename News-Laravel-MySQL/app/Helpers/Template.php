<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class Template
{

    public static function showItemHistory($by, $time)
    {
        $xhtml = '';
        $xhtml = sprintf(' <p><i class="fa fa-user"></i> %s</p>
                        <p><i class="fa fa-clock-o"></i> %s</p>', $by, date(Config::get('zvn.format.short_time'), strtotime($time)));
        return $xhtml;
    }



    public static function showItemStatus($controlerName, $id, $statusValue)
    {
        $xhtml = '';
        $templateStatus = Config::get("zvn.template.status");

        $statusValue = array_key_exists($statusValue, $templateStatus) ? $statusValue : 'default';
        $currentStatus = $templateStatus[$statusValue];

        $link          = route($controlerName .'/status', ['status' => $statusValue, 'id' => $id]);
        $xhtml = sprintf('<a href="%s" type="button" class="btn btn-round %s">%s</a>', $link, $currentStatus['class'], $currentStatus['name']);
        return $xhtml;
    }

    public static function showItemSelect($id, $displayValue, $fieldName)
    {
        $templateDisplay = Config::get("zvn.template." . $fieldName);

        $link          = route($fieldName, [$fieldName => 'value_new', 'id' => $id]);
        $xhtml = sprintf('<select name="select_change_attr" data-url="%s" class="form-control">', $link);
        foreach ($templateDisplay as $key => $value){
            $xhtmlSelected = '';

            if($key == $displayValue) $xhtmlSelected = 'selected="selected"';
            $xhtml .= sprintf('<option value="%s" %s>%s</option>', $key, $xhtmlSelected, $value['name']);
        }
        $xhtml .= '</select>';
        return $xhtml;
    }

    public static function showItemIsHome($id, $isHomeValue)
    {
        $xhtml = '';
        $templateIsHome = Config::get("zvn.template.is_home");

        $isHomeValue = array_key_exists($isHomeValue, $templateIsHome) ? $isHomeValue : 'yes';
        $currentIsHome = $templateIsHome[$isHomeValue];

        $link          = route('isHome', ['isHome' => $isHomeValue, 'id' => $id]);
        $xhtml = sprintf('<a href="%s" type="button" class="btn btn-round %s">%s</a>', $link, $currentIsHome['class'], $currentIsHome['name']);
        return $xhtml;
    }

    public static function showButtonFilter($controlerName, $countByStatus, $currentFilterStatus, $paramsSearch)
    {
        $xhtml = '';
        $templateStatus = Config::get("zvn.template.status");
        if (count($countByStatus) > 0) {
            array_unshift($countByStatus, [
                'count' => array_sum(array_column($countByStatus, 'count')),
                'status' => 'all'
            ]);

            foreach ($countByStatus as $item) {
                $statusValue = $item['status']; //[''active' , 'inactive', 'default', 'block']
                $statusValue = array_key_exists($statusValue, $templateStatus) ? $statusValue : 'default';
                $currentStatus = $templateStatus[$statusValue];

                $link = route($controlerName) . "?filter_status=" . $statusValue;
                if($paramsSearch['value'] != ''){
                    $link .= "&search_field=" . $paramsSearch['field'] . "&search_value=" . $paramsSearch['value'];
                }
                $classActive = ($currentFilterStatus == $statusValue) ? 'btn-danger' : 'btn-info';
                $xhtml .= sprintf('<a href="%s" type="button" class="btn %s">
                                        %s <span class="badge bg-white">%s</span>
                                   </a>', $link, $classActive, $currentStatus['name'], $item['count']);
            }
        }
        return $xhtml;
    }

    public static function showItemImage($controlerName, $name, $alt)
    {
        $xhtml = '';
        $xhtml = sprintf('<img src="%s" alt="%s" class="zvn-thumb">', asset("images/$controlerName/$name"), $alt);
        return $xhtml;
    }

    public static function showButtonAction($controlerName, $id)
    {
        $templateButton = Config::get('zvn.template.button');
        $buttonInArea = Config::get('zvn.config.button');

        $controlerName = (array_key_exists($controlerName, $buttonInArea)) ? $controlerName : 'default';
        $listButtons   = $buttonInArea[$controlerName];

        $xhtml = '';
        $xhtml .= '<div class="zvn-box-btn-filter">';
        foreach ($listButtons as $btn) {
            $currentButton = $templateButton[$btn];
            $link = route($controlerName . $currentButton['route-name'], ['id' => $id]);
            $xhtml .= sprintf('<a href="%s" type="button" class="btn btn-icon %s" data-toggle="tooltip" data-placement="top" data-original-title="%s">
                                    <i class="fa %s"></i>
                              </a>', $link, $currentButton['class'], $currentButton['title'], $currentButton['icon']);
        }

        $xhtml .= '</div>';

        return $xhtml;
    }

    public static function showAreaSearch($controlerName, $paramsSearch)
    {
        $xhtml = '';
        $xhtmlField = '';

        $templateField = Config::get('zvn.template.search');
        $fieldInController = Config::get('zvn.config.search');
        $controllerName = (array_key_exists($controlerName, $fieldInController)) ? $controlerName : 'default';

        foreach ($fieldInController[$controlerName] as $field) { //all id
            $xhtmlField .= sprintf('<li><a href="#" class="select-field" data-field="%s">%s</a><li>', $field, $templateField[$field]['name']);
        }

        $searchField = (in_array($paramsSearch['field'], $fieldInController[$controlerName])) ? $paramsSearch['field'] : 'all';

        $xhtml = sprintf('<div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle btn-active-field" data-toggle="dropdown" aria-expanded="false">
                                  %s <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                   %s
                                </ul>
                            </div>
                            <input type="text" class="form-control" name="search_value" value="%s">
                            <input type="hidden" name="search_field" value="%s">
                            <span class="input-group-btn">
                        <button id="btn-clear" type="button" class="btn btn-success"
                                style="margin-right: 0px">Xóa tìm kiếm</button>
                        <button id="btn-search" type="button" class="btn btn-primary">Tìm kiếm</button>
                        </span>

                     </div>', $templateField[$searchField]['name'], $xhtmlField, $paramsSearch['value'], $searchField);

        return $xhtml;
    }

    public static function  showDatetimeFrontend($dateTime){
        return date_format(date_create($dateTime), Config::get('zvn.format.short_time'));
    }

    public static function showContent($content, $length, $prefix = '...')
    {
        $prefix = ($length == 0) ? '' : $prefix;
        $content = str_replace(['<p>', '</p>'], '', $content);
        return $content;
    }
}
