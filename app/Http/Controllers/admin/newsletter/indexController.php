<?php

namespace App\Http\Controllers\admin\newsletter;

use App\Http\Controllers\Controller;
use App\Newsletter;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public  function index(){
        return view('admin.newsletter.index');
    }

    public function data(Request $request)
    {
        $query = Newsletter::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.newsletter.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['delete'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {

        $c=Newsletter::where('id',$id)->count();
        if ($c!=0)
        {
            Newsletter::where('id',$id)->delete();
            return redirect()->back();
        }
        else{
            return abort(404);
        }
    }
}
