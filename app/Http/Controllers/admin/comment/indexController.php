<?php

namespace App\Http\Controllers\admin\comment;

use App\Comment;
use App\CommentAnswer;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class indexController extends Controller
{
    public  function index(){
        return view('admin.comment.index');
    }

    public function answer($id)
    {
        $c = Comment::where('id',$id)->count();
        if ($c!=0)
        {
            $w =Comment::where('id',$id)->get();
            return view('admin.comment.answer',['data'=>$w]);
        }
        else
        {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $id = $request->route('id');
        $c = Comment::where('id',$id)->count();
        if ($c!=0)
        {
            if (CommentAnswer::getMessageControl($id))
            {
                CommentAnswer::where('commentId',$id)->update(['text'=>$request->get('text')]);
            }
            else
            {
                $array = [
                    'commentId'=>$id,
                    'userId'=>Auth::id(),
                    'text'=>$request->get('text')
                ];
                CommentAnswer::create($array);
            }
            return redirect()->back();
        }
        else
        {
            return abort(404);
        }
    }

    public function data(Request $request)
    {
        $query = Comment::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır

        ->addColumn('answer', function ($query){//datatable kolon olarak eklenir
            return '<a href="'.route('admin.comment.answer',['id'=>$query->id]).'">Cevapla</a>';//buton basılır
        })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.comment.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['answer', 'delete'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {

        $c=Comment::where('id',$id)->count();
        if ($c!=0)
        {
            Comment::where('id',$id)->delete();
            CommentAnswer::where('commentId',$id)->delete();
            return redirect()->back();
        }
        else{
            return abort(404);
        }
    }
}
