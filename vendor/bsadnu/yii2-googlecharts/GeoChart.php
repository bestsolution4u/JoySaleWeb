<?php

namespace bsadnu\googlecharts;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use bsadnu\googlecharts\GoogleJsApiAsset;

/**
 * Geo chart widget.
 * A geochart is a map of a country, a continent, or a region with areas identified in one of three ways: region mode, markers mode and text mode.
 * A geochart is rendered within the browser using SVG or VML. Note that the geochart is not scrollable or draggable, and it's a line drawing rather than a terrain map.
 * The regions style fills entire regions (typically countries) with colors corresponding to the values that you assign.
 * 
 * @author Stanislav Bannikov <bsadnu@gmail.com>
 */
class GeoChart extends Widget
{
    /**
     * @var string unique id of chart
     */
    public $id;

    /**
     * @var array table of data
     * Example:
     * [
     *     ['Country', 'Popularity'],
     *     ['Germany', 200],
     *     ['United States', 300],
     *     ['Brazil', 400],
     *     ['Canada', 500],
     *     ['France', 600],
     *     ['RU', 700]
     * ]
     */
    public $data = [];

    /**
     * @var array options
     * Example:
     * [
     *     'fontName' => 'Verdana',
     *     'height' => 500,
     *     'width' => '100%',
     *     'fontSize' => 12,
     *     'tooltip' => [
     *         'textStyle' => [
     *             'fontName' => 'Verdana',
     *             'fontSize' => 13
     *         ]
     *     ]
     * ]
     */
    public $options = [];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $view = Yii::$app->getView();
        $this->registerAssets();
        $view->registerJs($this->getJs(), View::POS_END);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $content = Html::tag('div', null, ['id'=> $this->id]);

        return $content;
    }

    /**
     * Registers necessary assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        GoogleJsApiAsset::register($view);
    }

    /**
     * Return necessary js script
     */
    private function getJs()
    {



        $uniqueInt = mt_rand(1, 999999);

        $js = "
            google.load('visualization', '1', {packages:['geochart','Table',DataView]});
            google.setOnLoadCallback(drawMap". $uniqueInt .");
        ";

        $js .= "
            function drawMap". $uniqueInt ."() {

                var data". $uniqueInt ." = google.visualization.arrayToDataTable(". Json::encode($this->data[0]) .");

                var options". $uniqueInt ." = ". Json::encode($this->options) .";

                var chart". $uniqueInt ." = new google.visualization.GeoChart($('#". $this->id ."')[0]);
                chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");

                var table". $uniqueInt ." = new google.visualization.Table($('#table')[0]);
            table". $uniqueInt .".draw(data". $uniqueInt .", null);
           

            google.visualization.events.addListener(chart". $uniqueInt .", 'select', function () {
                table". $uniqueInt .".setSelection(chart". $uniqueInt .".getSelection());
                
                var selection". $uniqueInt ." = chart". $uniqueInt .".getSelection();
        if (selection". $uniqueInt .".length > 0) {
            var view". $uniqueInt ." = new google.visualization.DataView(data". $uniqueInt .");

            // view". $uniqueInt .".setColumns([0, {
            //     type: 'number',
            //     label: data". $uniqueInt .".getColumnLabel(1),
            //     calc: function (dt, row) {
            //         return (selection". $uniqueInt ."[0].row == row) ? 2 : 1;
            //     }
            // }]);
            var countryname = $('.google-visualization-table-tr-sel');
            //console.log(countryname);
            var temp = countryname[0]['innerText'].length;
            var len = temp - 2;
            var name = $.trim(countryname[0]['innerText'].substring(0,len)); 

            $.ajax({
        type: 'GET',
        dataType: 'json',
        url: baseUrl+'/admin/changeregion/',
        data: {
            name: name
        },
        success: function (responce) {
            $('#user_countryname').text('Top Users in'+' '+name);
            var obj = responce;
            var result = Object.keys(obj).map(function(key) {
                return [String(key), obj[key], String('#4fb1c9'), obj[key]];
            });

            google.load('visualization', '1', {packages:['corechart'], callback: drawChart});
            function drawChart () {
                var title = [['Cities', 'Users', { role: 'style' }, { role: 'annotation' }]];
                var res = title.concat(result);

    var data = google.visualization.arrayToDataTable(
         res
      );
    
    var chart = new google.visualization.BarChart(document.querySelector('#map'));
    
    google.visualization.events.addListener(chart, 'select', function () {
        var selection = chart.getSelection();
        if (selection.length) {
            var row = selection[0].row;
            
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1, {
                type: 'string',
                role: 'style',
                calc: function (dt, i) {
                    return (i == row) ? 'color: red' : null;
                }
            }]);
            
            chart.draw(view, {
                height: 360,
                width: '100%'
            });
        }
    });
    
    chart.draw(data, {
        height: 360,
        width: '100%',
        'colors': ['#4fb1c9'],
        legend: { position: 'top','alignment': 'center','textStyle': {'color': '#000','fontSize': 12} },
       
    });
}
        }
    });


           
            chart". $uniqueInt .".draw(view". $uniqueInt .", options". $uniqueInt .");
        }
        else {
            chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");
        }
                 });
          chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");
            }
        ";     

        //  print_r($js);exit;   

        return $js;
    }   
}


class GeoChartt extends Widget
{
    /**
     * @var string unique id of chart
     */
    public $id;

    /**
     * @var array table of data
     * Example:
     * [
     *     ['Country', 'Popularity'],
     *     ['Germany', 200],
     *     ['United States', 300],
     *     ['Brazil', 400],
     *     ['Canada', 500],
     *     ['France', 600],
     *     ['RU', 700]
     * ]
     */
    public $data = [];

    /**
     * @var array options
     * Example:
     * [
     *     'fontName' => 'Verdana',
     *     'height' => 500,
     *     'width' => '100%',
     *     'fontSize' => 12,
     *     'tooltip' => [
     *         'textStyle' => [
     *             'fontName' => 'Verdana',
     *             'fontSize' => 13
     *         ]
     *     ]
     * ]
     */
    public $options = [];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $view = Yii::$app->getView();
        $this->registerAssets();
        $view->registerJs($this->getJs(), View::POS_END);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $content = Html::tag('div', null, ['id'=> $this->id]);

        return $content;
    }

    /**
     * Registers necessary assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        GoogleJsApiAsset::register($view);
    }

    /**
     * Return necessary js script
     */
    private function getJs()
    {



        $uniqueInt = mt_rand(1, 999999);

        $js = "
            google.load('visualization', '1', {packages:['geochart','Table',DataView]});
            google.setOnLoadCallback(drawMap". $uniqueInt .");
        ";

        $js .= "
            function drawMap". $uniqueInt ."() {

                var data". $uniqueInt ." = google.visualization.arrayToDataTable(". Json::encode($this->data[0]) .");

                var options". $uniqueInt ." = ". Json::encode($this->options) .";

                var chart". $uniqueInt ." = new google.visualization.GeoChart($('#". $this->id ."')[0]);
                chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");

                var table". $uniqueInt ." = new google.visualization.Table($('#protable')[0]);
            table". $uniqueInt .".draw(data". $uniqueInt .", null);
           

            google.visualization.events.addListener(chart". $uniqueInt .", 'select', function () {
                table". $uniqueInt .".setSelection(chart". $uniqueInt .".getSelection());
                
                var selection". $uniqueInt ." = chart". $uniqueInt .".getSelection();
        if (selection". $uniqueInt .".length > 0) {
            var view". $uniqueInt ." = new google.visualization.DataView(data". $uniqueInt .");

            
            var countryname = $('.google-visualization-table-tr-sel');
            var arrlen = countryname.length-1;
            var temp = countryname[arrlen]['innerText'].length;
            var len = temp - 2;
            var name = countryname[arrlen]['innerText'].substring(0,len);


            $.ajax({
        type: 'GET',
        dataType: 'json',
        url: baseUrl+'/admin/changeproregion/',
        data: {
            name: name
        },
        success: function (response) {
            $('#pro_countryname').text('Top Products in'+' '+name);
            var objj = response;
            var resultt = Object.keys(objj).map(function(keyy) {
                 return [objj[keyy]['city'], parseInt(objj[keyy]['counter']), String('#ea6173'), parseInt(objj[keyy]['counter'])];   
            });

            google.load('visualization', '1', {packages:['corechart'], callback: drawChart});
            function drawChart () {
                var title = [['Cities', 'Products', { role: 'style' }, { role: 'annotation' }]];
                var ress = title.concat(resultt);

    var data = google.visualization.arrayToDataTable(
         ress
      );
    
    var chart = new google.visualization.BarChart(document.querySelector('#mapp'));
    
    google.visualization.events.addListener(chart, 'select', function () {
        var selection = chart.getSelection();
        if (selection.length) {
            var row = selection[0].row;
            
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1, {
                type: 'string',
                role: 'style',
                calc: function (dt, i) {
                    return (i == row) ? 'color: red' : null;
                }
            }]);
            
            chart.draw(view, {
                height: 360,
                width: '100%'
            });
        }
    });
    
    chart.draw(data, {
        height: 360,
        width: '100%',
        'colors': ['#ea6173'],
        legend: { position: 'top','alignment': 'center','textStyle': {'color': '#000','fontSize': 12} },
       
    });
}
        }
    });


           
            chart". $uniqueInt .".draw(view". $uniqueInt .", options". $uniqueInt .");
        }
        else {
            chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");
        }
                 });
          chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");
            }
        ";     

        //  print_r($js);exit;   

        return $js;
    }   
}


// class GeoChartt extends Widget
// {
//     /**
//      * @var string unique id of chart
//      */
//     public $id;

//     /**
//      * @var array table of data
//      * Example:
//      * [
//      *     ['Country', 'Popularity'],
//      *     ['Germany', 200],
//      *     ['United States', 300],
//      *     ['Brazil', 400],
//      *     ['Canada', 500],
//      *     ['France', 600],
//      *     ['RU', 700]
//      * ]
//      */
//     public $data = [];

//     /**
//      * @var array options
//      * Example:
//      * [
//      *     'fontName' => 'Verdana',
//      *     'height' => 500,
//      *     'width' => '100%',
//      *     'fontSize' => 12,
//      *     'tooltip' => [
//      *         'textStyle' => [
//      *             'fontName' => 'Verdana',
//      *             'fontSize' => 13
//      *         ]
//      *     ]
//      * ]
//      */
//     public $options = [];


//     /**
//      * Initializes the widget.
//      */
//     public function init()
//     {
//         parent::init();
//         $view = Yii::$app->getView();
//         $this->registerAssets();
//         $view->registerJs($this->getJs(), View::POS_END);
//     }

//     /**
//      * Renders the widget.
//      */
//     public function run()
//     {
//         $content = Html::tag('div', null, ['id'=> $this->id]);

//         return $content;
//     }

//     /**
//      * Registers necessary assets
//      */
//     public function registerAssets()
//     {
//         $view = $this->getView();
//         GoogleJsApiAsset::register($view);
//     }

//     /**
//      * Return necessary js script
//      */
//     private function getJs()
//     {

//         $uniqueInt = mt_rand(1, 999999);

//         $js = "
//             google.load('visualization', '1', {packages:['geochart','Table',DataView]});
//             google.setOnLoadCallback(drawMap". $uniqueInt .");
//         ";

//         $js .= "
//             function drawMap". $uniqueInt ."() {

//                 var data". $uniqueInt ." = google.visualization.arrayToDataTable(". Json::encode($this->data[0]) .");

//                 var options". $uniqueInt ." = ". Json::encode($this->options) .";

//                 var chart". $uniqueInt ." = new google.visualization.GeoChart($('#". $this->id ."')[0]);
//                 chart". $uniqueInt .".draw(data". $uniqueInt .", options". $uniqueInt .");
//             }
//         ";     

//         //  print_r($js);exit;   

//         return $js;
//     }   
// }
