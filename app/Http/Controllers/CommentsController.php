<?php

namespace App\Http\Controllers;

use App\Comment;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\StoreCommentRequest;

class CommentsController extends Controller
{
    
    public function index()
    {
        $comment=Comment::allFor(Input::get('id'),Input::get('type'));
        return Response::json($comment,200,[],JSON_NUMERIC_CHECK);
        
    }

    public function store(StoreCommentRequest $request)
    {
        $model=Input::get('commenta_type');
        // dd($model);
        $model_id=Input::get('commenta_id');
     if(Comment::isCommentable($model,$model_id))   
         {  
             
              $comment=Comment::create(
            [
                'commenta_id'=>$model_id,
                'commenta_type'=>$model,
                'username'=>Input::get('username'),
                'content'=>Input::get('content'),
                'email'=>Input::get('email'),
                'reply'=>Input::get('reply',0),
                'ip'=>$request->ip()
            ]
        );
        return Response::json($comment,200,[],JSON_NUMERIC_CHECK);
    }
    else{
        return Response::json('ya un petit souci',422);
    }        
    }

    public function destroy($id)
    {
        $comment=Comment::find($id);
        if($comment->ip=Request::ip()){
            Comment::where('reply','=',$comment->id)->delete();
            $comment->delete();
            return Response::json($comment,200,[],JSON_NUMERIC_CHECK);
        }
        else {
            return Response::json("petit soucis");
        }
    }
}
