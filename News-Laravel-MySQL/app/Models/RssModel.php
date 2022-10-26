<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminModel;

class RssModel extends AdminModel
{

    public function __construct()
    {

        $this->table = 'rss';
        $this->folderUpload = 'rss';
        $this->fieldSearchAccepted = ['id', 'name', 'link'];
        $this->crudNotAccepted = ['_token'];
    }

    public function listItems($params, $options)
    {
        $result = null;
        if ($options['task'] == 'admin-list-items') {
            $query = $this->select('id', 'name', 'status', 'link', 'ordering', 'source', 'created', 'created_by', 'modified', 'modified_by');

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

            $result = $query->orderBy('ordering', 'asc')
                ->paginate($params['pagination']['totalItemsPerPage']);
        }
        if($options['task'] == 'news-list-items') {
            $query = $this->select('id', 'link', 'source')
                        ->where('status', '=', 'active' )
                        ->orderBy('ordering', 'asc');

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
        if ($options['task'] == 'change-status') {
            $status = ($params['currentStatus'] == 'active') ? 'inactive' : 'active';
            self::where('id', $params['id'])
                ->update(['status' => $status]);
        }

        if ($options['task'] == 'add-item') {

            $params['created_by'] = "tanphuc";
            $params['created']    = date('Y-m-d');
            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::insert($params);
        }

        if ($options['task'] == 'edit-item') {
            $params['modified_by'] = "tanphuc";
            $params['modified']    = date('Y-m-d');
            $params = array_diff_key($params, array_flip($this->crudNotAccepted));
            self::where('id', $params['id'])->update($params);
        }
    }

    public function deleteItem($params = null, $options = null)
    {

        if($options['task'] == 'delete-item') {
            self::where('id', $params['id'])->delete();
        }
    }


    public function getItem($params = null, $options = null)
    {
        $result = null;
        if($options['task'] == 'get-item') {
            $result = self::select('id', 'name', 'status', 'link', 'ordering', 'source')->where('id', $params['id'])->first();
        }
        return $result;
    }
}
