<?php

namespace App\Http\Controllers\admin\referans;

use App\Helper\imageHelper;
use App\Http\Controllers\Controller;
use App\Referans;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index()
    {
        return view('admin.referans.index');
    }

    public function create()
    {
        return view('admin.referans.create');
    }

    public function edit($id)
    {
        $c = Referans::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Referans::where('id',$id)->get();
            return view('admin.referans.edit', ['data'=>$data, 'id'=>$id]);
        }
        else
        {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $request->validate(['image'=>'required']);
        $id = $request->route('id');
        $c=Referans::where('id',$id)->count();
        if ($c!=0)
        {
            $all = $request->except('_token');

            $data= Referans::where('id',$id)->get();

            $array = [];

            if (isset($all['image']))
            {
                unlink(public_path($data[0]['image']));
                $array['image'] = imageHelper::upload(rand(1,9000), "referans", $all['image']);
            }

            $update =Referans::where('id', $id)->update($array);

            return redirect()->back()->with('status','Bilgiler Düzenlendi');
        }
        else
        {
            return abort(404);
        }


    }

    public function store(Request $request)
    {
        $request->validate(['image'=>'required']);

        $all = $request->except('_token');

        $image =(isset($all['image'])) ? imageHelper::upload(rand(1,9000), "referans", $all['image']): "";

        $array = [
            'image'=>$image
        ];

        $create = Referans::create($array);

        if ($create) {
            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else{
            return redirect()->back()->with('status', 'Malesef Eklenemedi');
        }
    }

    public function data(Request $request)
    {
        $query = Referans::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
        ->addColumn('image', function ($query){
            return '<img src="'.asset($query->image).'" style="width:100px;">';
        })

            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.referans.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.referans.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete', 'image'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {
        $c = Referans::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Referans::where('id',$id)->get();
            if ($data[0]['image']!=""){unlink(public_path($data[0]['image']));}
            Referans::where('id',$id)->delete();
            return redirect()->back()->with('status', 'Bilgiler Silindi');
        }
    }

    public function sortable(Request $request){
        $all = $request->except('_token');
        foreach ($all['eleman'] as $k => $v)
        {
            Referans::where('id', $v)->update(['orderNumber'=>$k]);
        }
    }
}
