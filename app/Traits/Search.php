<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Search
{
    /**
    * Boot function from Laravel
    */
    public static function generate($model, $request)
    {
        $per_page = $request->per_page ?  $request->per_page : 'all';
        $filter = $request->filter ? $request->filter : [];
        $sort = $request->sort ? $request->sort : 'created_at,ASC';
        $join = $request->join ? $request->join : '';
        $count = $request->count ? $request->count : '';
        $whereHas = $request->where_has ? $request->where_has : [];
        $limit = $request->limit ? $request->limit : '';
        $withTrashed = $request->with_trashed ? $request->with_trashed : false;
        $onlyTrashed = $request->only_trashed ? $request->only_trashed : false;

        $eloquent = new $model;

        if(is_array($whereHas)){
            foreach ($whereHas as $item => $value) {
                $words = explode(",",$value);
                     $eloquent = $eloquent->whereHas($words[0], function ($query) use ($words) {
                        $query->where($words[1], $words[2]);
                    });
            }
        }

        if($join !== ''){
            $join = Str::lower($join);
            $words = explode(",",$join);
            $eloquent = $eloquent->with($words);
        }

        if($count !== ''){
            $count = Str::lower($count);
            $words = explode(",",$count);
            $eloquent = $eloquent->withCount($words);
        }

        if(is_array($filter)){
            foreach ($filter as $item => $value) {
                $words = explode(",",$value);
                if(array_key_exists(2, $words)){
                    if($words[2] || $words[2] == 'AND'){
                        $eloquent = $eloquent->orWhere($words[0], 'LIKE', '%'.$words[1].'%');
                    }else{
                        $eloquent = $eloquent->where($words[0], 'LIKE', '%'.$words[1].'%');
                    }
                }else{
                    $eloquent = $eloquent->where($words[0], 'LIKE', '%'.$words[1].'%');
                }
            }
        }

        $sortItem = explode(",",$sort);
        if(strtoupper($sortItem[1]) == 'ASC' || strtoupper($sortItem[1]) == 'DESC'){
            $eloquent = $eloquent->orderBy($sortItem[0], $sortItem[1]);
        }

        if($withTrashed){
            $eloquent = $eloquent->withTrashed();
        }

        if($onlyTrashed){
            $eloquent = $eloquent->onlyTrashed();
        }

        if($limit != ''){
            $eloquent = $eloquent->limit($limit)->get();
        }else{
            if($per_page !== 'all'){
                $eloquent = $eloquent->paginate($per_page);
            }else{
                $eloquent = $eloquent->get();
            }
        }

        return $eloquent;
    }
}
