<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use App\Models\AdminModel;


class CategoryModel extends AdminModel
{

    public function __construct()
    {
        $this->table = 'category';
        $this->folderUpload = 'category';
        $this->fieldSearchAccepted = ['id', 'name'];
        $this->crudNotAccepted = ['_token'];
    }

    public function listItems($params, $options)
    {
        $result = null;
        if ($options['task'] == 'admin-list-items') {
            $query = $this->select('id', 'name', 'status', 'is_home', 'display' ,'created', 'created_by', 'modified', 'modified_by');

            if ($params['filter']['status'] != 'all') {
                $query->where('status', '=', $params['filter']['status']);
            }

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

            $result = $query->orderBy('id', 'desc')
                ->paginate($params['pagination']['totalItemsPerPage']);
        }

        if ($options['task'] == 'news-list-items') {
            $query = $this->select('id', 'name')
                         ->where('status', '=', 'active')
                         ->limit(8);
             $result = $query->get()->toArray();
        }

         if ($options['task'] == 'news-list-items-is-home') {
            $query = $this->select('id', 'name', 'display')
                         ->where('status', '=', 'active')
                         ->where('is_home', '=', 'yes');
             $result = $query->get()->toArray();
        }

        if ($options['task'] == 'admin-list-items-in-selecbox') {
            $query = $this->select('id', 'name')
                         ->orderBy('name', 'asc')
                         ->where('status', '=', 'active');
            $result = $query->pluck('name','id')->toArray();


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

        if($options['task'] == 'change-is-home'){
            $isHome = ($params['currentIsHome'] == 'yes') ? 'no' : 'yes';
            self::where('id', $params['id'])
                ->update(['is_home' => $isHome]);
        }

        if($options['task'] == 'change-display'){
            $display = ($params['currentDisplay']);
            self::where('id', $params['id'])
                ->update(['display' => $display]);
        }

        if($options['task'] == 'add-item'){

            $params['created_by'] = "tanphuc";
            $params['created']    = date('Y-m-d');
            $params['thumb']      = $this->uploadThumb($params['thumb']);

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
            self::where('id', $params['id'])->update($params);
        }


    }

    public function deleteItem($params = null, $options = null)
    {

        if($options['task'] == 'delete-item'){
            self::where('id', $params['id'])->delete();
        }
    }


    public function getItem($params = null, $options = null)
    {
        $result = null;
        if($options['task'] == 'get-item'){
           $result = self::select('id', 'name', 'status')->where('id', $params['id'])->first();
        }

        if($options['task'] == 'news-get-item'){
            $result = self::select('id', 'name', 'display')->where('id', $params['category_id'])->first();

            if($result) $result = $result->toArray();
        }


        return $result;
    }
}
