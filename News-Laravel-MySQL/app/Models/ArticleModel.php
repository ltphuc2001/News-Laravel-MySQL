<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminModel;
use Illuminate\Support\Str;
use Psy\Command\WhereamiCommand;

class ArticleModel extends AdminModel
{

    public function __construct()
    {
        $this->table = 'article';
        $this->folderUpload = 'article';
        $this->fieldSearchAccepted = ['name', 'content'];
        $this->crudNotAccepted = ['_token', 'thumb_current'];
    }

    public function listItems($params, $options)
    {
        $result = null;
        if ($options['task'] == 'admin-list-items') {
            $query = $this->select('article.id', 'article.name', 'article.status', 'article.content', 'article.thumb', 'article.type', 'category.name as category_name')
                        ->join('category', 'category.id', '=', 'article.category_id');

            if ($params['filter']['status'] != 'all') {
                $query->where('article.status', '=', $params['filter']['status']);
            }

            if ($params['search']['value'] != null) {
                if ($params['search']['field'] == 'all') {

                    $query->where(function ($query) use ($params) {
                        foreach ($this->fieldSearchAccepted as $column) {
                            $query->orWhere('article.'.$column, 'LIKE', "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where('article.'.$params['search']['field'], 'LIKE', "%{$params['search']['value']}%");
                }
            }

            $result = $query->orderBy('article.id', 'desc')
                ->paginate($params['pagination']['totalItemsPerPage']);
        }
        if ($options['task'] == 'news-list-items') {
           $query = $this->select('id', 'name', 'content', 'thumb')
                        ->where('status', '=', 'active')
                        ->limit(5);
            $result = $query->get()->toArray();
        }

        if ($options['task'] == 'news-list-items-feature') {
             $query = $this->select('article.id', 'article.name', 'article.content', 'article.thumb', 'article.category_id' ,'article.type','article.created', 'category.name as category_name')
                        ->join('category', 'category.id', '=', 'article.category_id')
                        ->where('article.status', '=', 'active')
                        ->where('article.type', 'feature')
                        ->orderBy('article.id', 'desc')
                        ->take(3);
             $result = $query->get()->toArray();
        }

        if ($options['task'] == 'news-list-items-latest') {
            $query = $this->select('article.id', 'article.name', 'article.content', 'article.thumb','article.created', 'article.category_id' ,'category.name as category_name')
                       ->join('category', 'category.id', '=', 'article.category_id')
                       ->where('article.status', '=', 'active')
                       ->orderBy('article.id', 'desc')
                       ->take(4);
            $result = $query->get()->toArray();
        }

       if ($options['task'] == 'news-list-items-category') {
        $query = $this->select('id', 'name', 'content', 'thumb','created')
                   ->where('status', '=', 'active')
                   ->where('category_id', '=', $params['category_id'])
                   ->take(4);
        $result = $query->get()->toArray();
        }

        if ($options['task'] == 'news-list-items-related-in-category') {
            $query = $this->select('id', 'name', 'content', 'thumb','created')
                       ->where('status', '=', 'active')
                       ->where('article.id', '!=', $params['article_id'])
                       ->where('category_id', '=', $params['category_id'])
                       ->take(4);
            $result = $query->get()->toArray();
        }


        return $result;
    }

    public function countItems($params = null, $options = null)
    {
        $result = null;
        if ($options['task'] == 'admin-count-items') {
            $query = $this::groupBy('status')
                ->select(DB::raw('status, COUNT(id) as count'));

            //->get();

            if ($params['search']['value'] != null) {
                if ($params['search']['field'] == 'all') {

                    $query->where(function ($query) use ($params) {
                        foreach ($this->fieldSearchAccepted as $column) {
                            $query->orWhere($column, 'LIKE', "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE', "%{$params['search']['value']}%");
                }
            }

            $result = $query->get()->toArray();
        }
        return $result;
    }

    public function saveItem($params = null, $options = null)
    {
        if($options['task'] == 'change-status'){
            $status = ($params['currentStatus'] == 'active') ? 'inactive' : 'active';
            self::where('id', $params['id'])
                ->update(['status' => $status]);
        }

        if($options['task'] == 'change-type'){
            $type = ($params['currentType']);
            self::where('id', $params['id'])
                ->update(['type' => $type]);
        }

        if($options['task'] == 'add-item'){

            $params['created_by'] = "tanphuc";
            $params['created']    = date('Y-m-d');
            $params['thumb'] = $this->uploadThumb($params['thumb']);

            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::insert($params);
        }

        if($options['task'] == 'edit-item'){
           if(!empty($params['thumb'])){
                $this->deleteThumb($params['thumb_current']);
                $params['thumb'] = $this->uploadThumb($params['thumb']);
           }
            $params['modified_by'] = "tanphuc";
            $params['modified']    = date('Y-m-d');
            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::where('id', $params['id'])
                ->update($params);
        }


    }

    public function deleteItem($params = null, $options = null)
    {

        if($options['task'] == 'delete-item'){
            $item = self::getItem($params, ['task' => 'get-thumb']);
            $this->deleteThumb($item['thumb']);
            self::where('id', $params['id'])->delete();

        }
    }

    public function uploadThumb($thumbObj)
    {
       $thumbName = Str::random(10) . '.' . $thumbObj->clientExtension();
       $thumbObj->storeAs('article', $thumbName, 'zvn_storage_image');
       return $thumbName;
    }

    public function deleteThumb($thumbName)
    {
        Storage::disk('zvn_storage_image')->delete('article' . '/' . $thumbName);
    }

    public function getItem($params = null, $options = null)
    {
        $result = null;
        if($options['task'] == 'get-item'){
           $result = self::select('id', 'name', 'content', 'status', 'thumb', 'category_id')->where('id', $params['id'])->first();
        }

        if($options['task'] == 'get-thumb'){
            $result = self::select('id', 'thumb')->where('id', $params['id'])->first();
        }

        if($options['task'] == 'news-get-item'){
            $result = $this->select('article.id', 'article.name', 'article.content', 'article.thumb', 'article.category_id' ,'article.type','article.created','category.display' ,'category.name as category_name')
                        ->join('category', 'category.id', '=', 'article.category_id')
                        ->where('article.id', '=', $params['article_id'])
                        ->where('article.status', '=', 'active')->first();
            if($result) $result = $result->toArray();
        }
        return $result;
    }
}
