<?php

namespace App\Http\Controllers\admin\slider;

use App\Http\Controllers\Controller;
use App\LanguageContent;
use App\Slider;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{

    public function index()
    {
        return view('admin.slider.index');
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function edit($id)
    {
        $c = Slider::where('id',$id)->count();
        if ($c!=0)
        {
            return view('admin.slider.edit', ['id'=>$id]);
        }
        else
        {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $c=Slider::where('id',$id)->count();
        if ($c!=0)
        {
            $all = $request->except('_token');
            $update = Slider::where('id', $id)->update([]);
            LanguageContent::InsertorUpdate($all['title'], SLIDER_LANGUAGE, TITLE_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['description'], SLIDER_LANGUAGE, DESCRIPTION_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['url'], SLIDER_LANGUAGE, URL_LANGUAGE, $id, 0);

            if (isset($all['image'])){
                LanguageContent::InsertorUpdate($all['image'], SLIDER_LANGUAGE, IMAGE_LANGUAGE, $id, 1,"slider");
            }
            return redirect()->back()->with('status','Bilgiler Düzenlendi');
        }
        else
        {
            return abort(404);
        }


    }

    public function store(Request $request)
    {
        $all = $request->except('_token');

        $create = Slider::create([]);

        if ($create) {
            LanguageContent::InsertorUpdate($all['title'], SLIDER_LANGUAGE, TITLE_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['description'], SLIDER_LANGUAGE, DESCRIPTION_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['url'], SLIDER_LANGUAGE, URL_LANGUAGE, $create->id, 0);

            if (isset($all['image'])){
                LanguageContent::InsertorUpdate($all['image'], SLIDER_LANGUAGE, IMAGE_LANGUAGE, $create->id, 1,"slider");
            }

            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else{
            return redirect()->back()->with('status', 'Malesef Eklenemedi');
        }
    }

    public function data(Request $request)
    {
        $query = Slider::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
            ->addColumn('image', function ($query){
                return '<img src="'.asset(LanguageContent::get(1,SLIDER_LANGUAGE,IMAGE_LANGUAGE, $query->id)).'" style="width:100px;">"';
        })
            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.slider.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.slider.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete', 'image'])
                ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function sortable(Request $request){
        $all = $request->except('_token');
        foreach ($all['eleman'] as $k => $v)
        {
            Slider::where('id', $v)->update(['orderNumber'=>$k]);
        }
    }

    public function delete($id)
    {
        $c = Slider::where('id',$id)->count();
        if ($c!=0)
        {
            LanguageContent::getDelete(SLIDER_LANGUAGE, $id);
            Slider::where('id',$id)->delete();
            return redirect()->back()->with('status', 'Bilgiler Silindi');
        }
    }
}
