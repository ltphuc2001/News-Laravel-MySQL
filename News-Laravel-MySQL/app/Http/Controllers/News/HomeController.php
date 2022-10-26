<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderModel;
use App\Models\CategoryModel;
use App\Models\ArticleModel;

class HomeController extends Controller
{
    private $pathViewController = "news.pages.home.";
    private $controllerName     = "home";
    private $params             = [];
    private $model;
    public function __construct()
    {
        view()->share('controllerName', $this->controllerName);
    }


    public function index(Request $request)
    {
        $sliderModel = new SliderModel();
        $itemsSlider  = $sliderModel->listItems(null, ['task' => 'news-list-items']);

        $categoryModel = new CategoryModel();
        $itemsCategory  = $categoryModel->listItems(null, ['task' => 'news-list-items-is-home']);

        $articleModel = new ArticleModel();
        $itemsFeature  = $articleModel->listItems(null, ['task' => 'news-list-items-feature']);
        $itemsLatest  = $articleModel->listItems(null, ['task' => 'news-list-items-latest']);

        foreach($itemsCategory as $key => $category){
            $itemsCategory[$key]['article'] = $articleModel->listItems(['category_id' => $category['id']], ['task' => 'news-list-items-category']);
        }

        return view($this->pathViewController . 'index',[
            'item' => $itemsFeature,
            'params'      => $this->params,
            'itemsSlider' => $itemsSlider,
            'itemsCategory' => $itemsCategory,
            'itemsLatest' => $itemsLatest
        ]);
    }


}
