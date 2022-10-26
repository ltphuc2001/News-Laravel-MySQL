<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use App\Models\AdminModel;


class UserModel extends AdminModel
{

    public function __construct()
    {
        $this->table = 'user';
        $this->folderUpload = 'user';
        $this->fieldSearchAccepted = ['id', 'username', 'email', 'fullname'];
        $this->crudNotAccepted = ['_token', 'avatar_current', 'password_confirmation', 'task'];
    }

    public function listItems($params, $options)
    {
        $result = null;
        if ($options['task'] == 'admin-list-items') {
            $query = $this->select('id', 'username', 'status', 'fullname' ,'email', 'avatar' ,'level','created', 'created_by', 'modified', 'modified_by');

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
            $params['avatar'] = $this->uploadThumb($params['avatar']);
            $params['password'] = md5($params['password']);

            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::insert($params);
        }

        if($options['task'] == 'edit-item'){
           if(!empty($params['avatar'])){
                $this->deleteThumb($params['avatar_current']);
                $params['avatar'] = $this->uploadThumb($params['avatar']);
            }
            $params['modified_by'] = "tanphuc";
            $params['modified']    = date('Y-m-d');
            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::where('id', $params['id'])->update($params);
        }

        if($options['task'] == 'change-level'){
            $display = ($params['currentLevel']);
            self::where('id', $params['id'])
                ->update(['level' => $display]);
        }

        if($options['task'] == 'change-password'){
            $password = md5($params['password']);
            self::where('id', $params['id'])
                ->update(['password' => $password]);
        }


        if($options['task'] == 'change-level-post'){
            $level = $params['level'];
            self::where('id', $params['id'])
                ->update(['level' => $level]);
        }

    }

    public function deleteItem($params = null, $options = null)
    {

        if($options['task'] == 'delete-item'){
            $item = self::getItem($params, ['task' => 'get-avatar']);
            $this->delete($item['avatar']);
            self::where('id', $params['id'])->delete();
        }
    }


    public function getItem($params = null, $options = null)
    {
        $result = null;
        if($options['task'] == 'get-item'){
           $result = self::select('id', 'username', 'email', 'level','status', 'fullname', 'avatar')->where('id', $params['id'])->first();
        }

        if($options['task'] == 'get-avatar'){
            $result = self::select('id', 'avatar')->where('id', $params['id'])->first();

            if($result) $result = $result->toArray();
        }
        if($options['task'] == 'auth-login') {
            $result = self::select('id', 'username', 'fullname', 'email', 'level', 'avatar')
                    ->where('status', 'active')
                    ->where('email', $params['email'])
                    ->where('password', md5($params['password']) )->first();

            if($result) $result = $result->toArray();
        }


        return $result;
    }
}
