<?php

namespace App\Http\Controllers\admin\services;

use App\Http\Controllers\Controller;
use App\LanguageContent;
use App\Services;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index()
    {
        return view('admin.services.index');
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function edit($id)
    {
        $c = Services::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Services::where('id',$id)->get();
            return view('admin.services.edit', ['data'=>$data, 'id'=>$id]);
        }
        else
        {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $c=Services::where('id',$id)->count();
        if ($c!=0)
        {
            $all = $request->except('_token');
            $update = Services::where('id', $id)->update(['isHome'=>$all['isHome'],'icon'=>$all['icon']]);
            LanguageContent::InsertorUpdate($all['text'], SERVICE_LANGUAGE, TEXT_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['home_text'], SERVICE_LANGUAGE, HOME_DESCRIPTION_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['slug'], SERVICE_LANGUAGE, SLUG_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['name'], SERVICE_LANGUAGE, NAME_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['title'], SERVICE_LANGUAGE, TITLE_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['description'], SERVICE_LANGUAGE, DESCRIPTION_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['keywords'], SERVICE_LANGUAGE, KEYWORDS_LANGUAGE, $id, 0);


            if (isset($all['image'])){
                LanguageContent::InsertorUpdate($all['image'], SERVICE_LANGUAGE, IMAGE_LANGUAGE, $id, 1, "services");
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



        $create = Services::create(['isHome'=>$all['isHome'], 'icon'=>$all['icon']]);

        if ($create) {

            LanguageContent::InsertorUpdate($all['text'], SERVICE_LANGUAGE, TEXT_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['home_text'], SERVICE_LANGUAGE, HOME_DESCRIPTION_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['slug'], SERVICE_LANGUAGE, SLUG_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['name'], SERVICE_LANGUAGE, NAME_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['title'], SERVICE_LANGUAGE, TITLE_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['description'], SERVICE_LANGUAGE, DESCRIPTION_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['keywords'], SERVICE_LANGUAGE, KEYWORDS_LANGUAGE, $create->id, 0);


            if (isset($all['image'])){
                LanguageContent::InsertorUpdate($all['image'], SERVICE_LANGUAGE, IMAGE_LANGUAGE, $create->id, 1, "services");
            }

            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else{
            return redirect()->back()->with('status', 'Malesef Eklenemedi');
        }
    }

    public function data(Request $request)
    {
        $query = Services::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
            ->addColumn('name', function ($query){
                return LanguageContent::get(DEFAULT_LANGUAGE,SERVICE_LANGUAGE,NAME_LANGUAGE, $query->id);
            })
            ->addColumn('slug', function ($query){
                return LanguageContent::get(DEFAULT_LANGUAGE,SERVICE_LANGUAGE,SLUG_LANGUAGE, $query->id);
            })
            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.services.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.services.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete', 'name'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {
        $c = Services::where('id',$id)->count();
        if ($c!=0)
        {
            LanguageContent::getDelete(SERVICE_LANGUAGE, $id);
            Services::where('id',$id)->delete();
            return redirect()->back()->with('status', 'Bilgiler Silindi');
        }
    }

    public function sortable(Request $request){
        $all = $request->except('_token');
        foreach ($all['eleman'] as $k => $v)
        {
            Services::where('id', $v)->update(['orderNumber'=>$k]);
        }
    }
}
