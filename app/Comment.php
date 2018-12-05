<?php

namespace App;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded=[];
    static $commentable_for=['post'];

    protected $hidden=['email','ip'];

    protected $appends=['email_md5','ip_md5'];

    public function getEmailMd5attribute()
    {
        return md5($this->attributes['email']);
    }

    public function getIpMd5Attribute()
    {
        return md5($this->attributes['ip']);
        
    }

    public static function allFor($id,$type)
    {
       $records=self::where(['commenta_id'=>$id,'commenta_type'=>$type])->orderBy('created_at','ASC')->get();
        $comments=[];
        $by_id=[];

        foreach ($records as $record) 
        {
            if($record->reply)
            {
                $by_id[$record->reply]->attributes['replies'][]=$record;
            }
            else
            {
                $record->attributes['replies']=[];
                $by_id[$record->id]=$record;
                $comments[]=$record;
            }
        }

        return array_reverse($comments);
    }

    public static function isCommentable($model,$model_id)
    {
        
        if(!in_array($model,self::$commentable_for))
        {
            return false;
        }
        else
        {
             
            $model="\\App\\".ucfirst($model);
        
            
            return $model::where(['id'=>$model_id])->exists();
        }
    }




}
