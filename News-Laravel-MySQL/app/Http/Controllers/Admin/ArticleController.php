<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest as MainRequest;
use Illuminate\Support\Facades\DB;
use App\Models\ArticleModel as ArticleModel;
use App\Models\CategoryModel as CategoryModel;


class ArticleController extends Controller
{
    private $pathViewController = "admin.pages.article.";
    private $controllerName     = "article";
    private $params             = [];
    private $model;

    public function __construct()
    {
        $this->model = new ArticleModel();
        $this->params['pagination']['totalItemsPerPage'] = 5;
        view()->share('controllerName', $this->controllerName);
    }
    public function index(Request $request)
    {
        $this->params['filter']['status'] = $request->input('filter_status', 'all');
        $this->params['search']['field']  = $request->input('search_field', '');
        $this->params['search']['value']  = $request->input('search_value', '');
        $items           = $this->model->listItems($this->params, ['task' => 'admin-list-items' ]);
        $countByStatus     = $this->model->countItems($this->params, ['task' => 'admin-count-items' ]);

        return view($this->pathViewController . 'index',[
            'controllerName' => $this->controllerName,
            'params' => $this->params,
            'items' => $items,
            'countByStatus' => $countByStatus
        ]);

    }

    public function form(Request $request)
    {
        $item = null;
        if($request->id != null){
            $params['id'] = $request->id;
            $item  = $this->model->getItem($params, ['task' => 'get-item']);
        }

        $categoryModel = new CategoryModel();
        $itemsCategory = $categoryModel->listItems($this->params, ['task' => 'admin-list-items-in-selecbox']);

        return view($this->pathViewController . 'form', [
            'item' => $item,
            'itemsCategory' => $itemsCategory
        ]);
    }

    public function save(MainRequest $request)
    {
        if($request->method() == 'POST'){
            $params = $request->all();

            $task = "add-item";
            $notify = "Thêm phần tử thành công!";

            if($params['id'] != null){
                $task = "edit-item";
                $notify = "Cập nhật phần tử thành công!";
            }
            $this->model->saveItem($params, ['task' => $task]);
            return redirect()->route($this->controllerName)->with("notify", $notify);
        }
    }

    public function status(Request $request)
    {
       $params['currentStatus'] = $request->status;
       $params['id']            = $request->id;
       $this->model->saveItem($params, ['task' => 'change-status']);
       return redirect()->route($this->controllerName)->with('notify', 'Cập nhật trạng thái thành công !');
    }

    public function delete(Request $request)
    {
       $params['id']            = $request->id;
       $this->model->deleteItem($params, ['task' => 'delete-item']);
       return redirect()->route($this->controllerName)->with('notify', 'Xóa phần tử thành công !');
    }

    public function type(Request $request)
    {
       $params['currentType']    = $request->type;
       $params['id']             = $request->id;

       $this->model->saveItem($params, ['task' => 'change-type']);
       return redirect()->route($this->controllerName)->with('notify', 'Cập nhật kiểu hiển thị thành công !');
    }
}
