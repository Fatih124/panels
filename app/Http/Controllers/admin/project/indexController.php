<?php

namespace App\Http\Controllers\admin\project;

use App\Helper\imageHelper;
use App\Http\Controllers\Controller;
use App\LanguageContent;
use App\Project;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index()
    {
        return view('admin.project.index');
    }

    public function create()
    {
        return view('admin.project.create');
    }

    public function edit($id)
    {
        $c = Project::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Project::where('id',$id)->get();
            return view('admin.project.edit', ['data'=>$data, 'id'=>$id]);
        }
        else
        {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $c=Project::where('id',$id)->count();
        if ($c!=0)
        {
            $all = $request->except('_token');

            $data= Project::where('id',$id)->get();

            $array = [
                'isShow'=>$all['isShow'],
                'url'=>$all['url'],

            ];

            if (isset($all['image']))
            {
                unlink(public_path($data[0]['image']));
                $array['image'] = imageHelper::upload(rand(1,9000), "project", $all['image']);
            }

            $update = Project::where('id', $id)->update($array);

            LanguageContent::InsertorUpdate($all['name'], PROJECT_LANGUAGE, NAME_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['text'], PROJECT_LANGUAGE, TEXT_LANGUAGE, $id, 0);

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

        $image =(isset($all['image'])) ? imageHelper::upload(rand(1,9000), "project", $all['image']): "";

        $array = [
            'isShow' =>$all['isShow'],
            'url'=>$all['url'],
            'image'=>$image
        ];

        $create = Project::create($array);

        if ($create) {


            LanguageContent::InsertorUpdate($all['name'], PROJECT_LANGUAGE, NAME_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['text'], PROJECT_LANGUAGE, TEXT_LANGUAGE, $create->id, 0);
            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else{
            return redirect()->back()->with('status', 'Malesef Eklenemedi');
        }
    }

    public function data(Request $request)
    {
        $query = Project::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
        ->addColumn('name', function ($query){
            return LanguageContent::get(DEFAULT_LANGUAGE,PROJECT_LANGUAGE,NAME_LANGUAGE, $query->id);
        })

            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.project.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.project.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete', 'name'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {
        $c = Project::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Project::where('id',$id)->get();
            if ($data[0]['image']!=""){unlink(public_path($data[0]['image']));}
            LanguageContent::getDelete(PROJECT_LANGUAGE, $id);
            Project::where('id',$id)->delete();
            return redirect()->back()->with('status', 'Bilgiler Silindi');
        }
    }

    public function sortable(Request $request){
        $all = $request->except('_token');
        foreach ($all['eleman'] as $k => $v)
        {
            Project::where('id', $v)->update(['orderNumber'=>$k]);
        }
    }
}
