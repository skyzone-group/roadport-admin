<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Botuser extends Model
{
    protected $table = 'botusers';

    protected $sorting = ['name', 'created_at'];

    public static function deepFilters(){


        $tiyin = [
        ];

        $obj = new self();
        $request = request();

        $query = self::where('id','!=','0');

        foreach ($obj->sorting as $item) {
            //request operator key
            $operator = $item.'_operator';

            if ($request->has($item) && $request->$item != '')
            {
                if (isset($tiyin[$item])){
                    $select = $request->$item * 100;
                    $select_pair = $request->{$item.'_pair'} * 100;
                }else{
                    $select = $request->$item;
                    $select_pair = $request->{$item.'_pair'};
                }
                //set value for query
                if ($request->has($operator) && $request->$operator != '')
                {
                    if (strtolower($request->$operator) == 'between' && $request->has($item.'_pair') && $request->{$item.'_pair'} != '')
                    {
                        $value = [
                            $select,
                            $select_pair];

                        $query->whereBetween($item,$value);
                    }
                    elseif (strtolower($request->$operator) == 'wherein')
                    {
                        $value = explode(',',str_replace(' ','',$select));
                        $query->whereIn($item,$value);
                    }
                    elseif (strtolower($request->$operator) == 'like')
                    {
                        if (strpos($select,'%') === false)
                            $query->where($item,'like','%'.$select.'%');
                        else
                            $query->where($item,'like',$select);
                    }
                    else
                    {
                        $query->where($item,$request->$operator,$select);
                    }
                }
                else
                {
                    $query->where($item,$select);
                }
            }
        }
        if ($request->has('user_type') && $request->user_type != '')
        {
            $botusers = Botuser::select('tg_user_id')
                ->where('user_type','like',"%$request->user_type%")
                ->get();
            //dd($botusers);
            if ($botusers->isNotEmpty())
            {
                $query->whereIn('tg_user_id',$botusers->map(function ($item){
                    return $item->tg_user_id;
                }));

            }

        }

        return $query;
    }

    public function orders()
    {
        return $this->hasMany(Orders::class,'tg_user_id','tg_user_id');
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class,'tg_user_id','tg_user_id');
    }

    public function car()
    {
        return $this->belongsTo(Cars::class, 'car_id', 'id');
    }
}
