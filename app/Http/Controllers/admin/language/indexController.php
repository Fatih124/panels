<?php

namespace App\Http\Controllers\admin\language;

use App\Http\Controllers\Controller;
use App\Language;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class indexController extends Controller
{
    public  function index(){
        return view('admin.language.index');
    }

    public function create()
    {
        return view('admin.language.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required', 'code'=>'required']);
        $all = $request->except('_token');
        $insert = Language::create($all);
        if ($insert)
        {
            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else
        {
            return redirect()->back()->with('status', 'Eklenemedi.');
        }
    }

    public function data(Request $request)
    {
        $query = Language::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır

            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.language.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.language.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function edit($id)
    {

        $c=Language::where('id',$id)->count();
        if ($c!=0)
        {
            $data=Language::where('id',$id)->get();
            return view('admin.language.edit',['data'=>$data]);
        }
        else{
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $id=$request->route('id');
        $all = $request->except('_token');
        Language::where('id',$id)->update($all);
        return redirect()->back()->with('status', 'Bilgiler Düzenlendi');
    }

    public function delete($id)
    {

        $c=Language::where('id',$id)->count();
        if ($c!=0)
        {
           Language::where('id',$id)->delete();
           return redirect()->back();
        }
        else{
            return abort(404);
        }
    }
}
