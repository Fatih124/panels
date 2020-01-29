<?php

namespace App\Http\Controllers\admin\team;

use App\Helper\imageHelper;
use App\Http\Controllers\Controller;
use App\LanguageContent;
use App\Team;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index()
    {
        return view('admin.team.index');
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function edit($id)
    {
        $c = Team::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Team::where('id',$id)->get();
            return view('admin.team.edit', ['data'=>$data, 'id'=>$id]);
        }
        else
        {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $c=Team::where('id',$id)->count();
        if ($c!=0)
        {
            $all = $request->except('_token');

            $data= Team::where('id',$id)->get();

            $array = [
                'name'=>$all['name'],

            ];

            if (isset($all['image']))
            {
                unlink(public_path($data[0]['image']));
                $array['image'] = imageHelper::upload(rand(1,9000), "team", $all['image']);
            }

            $update = Team::where('id', $id)->update($array);

            LanguageContent::InsertorUpdate($all['text'], TEAM_LANGUAGE, TEXT_LANGUAGE, $id, 0);
            LanguageContent::InsertorUpdate($all['position'], TEAM_LANGUAGE, POSITION_LANGUAGE, $id, 0);

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

        $image =(isset($all['image'])) ? imageHelper::upload(rand(1,9000), "team", $all['image']): "";

        $array = [
            'name'=>$all['name'],
            'image'=>$image
        ];

        $create = Team::create($array);

        if ($create) {


            LanguageContent::InsertorUpdate($all['position'], TEAM_LANGUAGE, POSITION_LANGUAGE, $create->id, 0);
            LanguageContent::InsertorUpdate($all['text'], TEAM_LANGUAGE, TEXT_LANGUAGE, $create->id, 0);
            return redirect()->back()->with('status', 'Başarıyla Eklendi');
        }
        else{
            return redirect()->back()->with('status', 'Malesef Eklenemedi');
        }
    }

    public function data(Request $request)
    {
        $query = Team::query();//query olusturlur verıler alınır
        $data = DataTables::of($query)//datatablesa atanır
        ->addColumn('position', function ($query){
            return LanguageContent::get(DEFAULT_LANGUAGE,TEAM_LANGUAGE,POSITION_LANGUAGE, $query->id);
        })

            ->addColumn('edit', function ($query){//datatable kolon olarak eklenir
                return '<a href="'.route('admin.team.edit',['id'=>$query->id]).'">Düzenle</a>';//buton basılır
            })
            ->addColumn('delete', function ($query){
                return '<a href="'.route('admin.team.delete',['id'=>$query->id]).'">Sil</a>';//silme butonu basılır
            })
            ->rawColumns(['edit', 'delete', 'name'])
            ->make(true);//*html kodları datatable da raw text olarak doner ama rawcolumn sayesinde bu engellenır
        return $data;
    }

    public function delete($id)
    {
        $c = Team::where('id',$id)->count();
        if ($c!=0)
        {
            $data = Team::where('id',$id)->get();
            if ($data[0]['image']!=""){unlink(public_path($data[0]['image']));}
            LanguageContent::getDelete(TEAM_LANGUAGE, $id);
            Team::where('id',$id)->delete();
            return redirect()->back()->with('status', 'Bilgiler Silindi');
        }
    }

    public function sortable(Request $request){
        $all = $request->except('_token');
        foreach ($all['eleman'] as $k => $v)
        {
            Team::where('id', $v)->update(['orderNumber'=>$k]);
        }
    }
}
