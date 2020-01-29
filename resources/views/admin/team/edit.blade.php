@extends('layouts.app')

@section('content')
    <div id="mainContent">
        <div class="row gap-20 masonry pos-r">
            <div class="masonry-sizer col-md-6"></div>
            <div class="masonry-item col-md-12">
                @if(session("status"))
                    <div class="alert alert-primary">
                        {{session("status")}}
                    </div>
                @endif
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Takım Üyesi Düzenle</h6>
                    <div class="mT-30">
                        <form enctype="multipart/form-data" action="{{ route('admin.team.update',['id'=>$data[0]['id']]) }}" method="POST">
                            @csrf
                            @if($data[0]['image']!="")
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="{{asset($data[0]['image'])}}" style="width: 120px;">
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Profil Resim</label>
                                        <input  type="file" class="form-control" name="image" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Adı Soyadı</label>
                                        <input type="text" name="name" class="slug-url form-control" value="{{$data[0]['name']}}">
                                    </div>
                                </div>
                            </div>
                            @foreach(\App\Language::all() as $k => $v)
                                <div class="row" style="border: 1px solid #dddddd; margin-bottom: 5px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >Pozisyon [{{$v['name']}}]</label>
                                            <input type="text" name="position[{{$v['id']}}]" class="form-control" value="{{\App\LanguageContent::get($v['id'], TEAM_LANGUAGE,POSITION_LANGUAGE, $data[0]['id'])}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Açıklama[{{$v['name']}}]</label>
                                            <input type="text" name="text[{{$v['id']}}]" class="form-control" value="{{\App\LanguageContent::get($v['id'], TEAM_LANGUAGE,TEXT_LANGUAGE, $data[0]['id'])}}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="mt-3 btn btn-primary">Düzenle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection