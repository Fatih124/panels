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
                    <h6 class="c-grey-900">Yeni Proje Ekle</h6>
                    <div class="mT-30">
                        <form enctype="multipart/form-data" action="{{ route('admin.project.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Proje Resim</label>
                                        <input required type="file" class="form-control" name="image" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Proje Url</label>
                                        <input type="text" name="url" class="slug-url form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Sayfa Gösterimi ?</label>
                                    <select name="isShow" id="" class="form-control">
                                        <option value="0">Evet</option>
                                        <option value="1">Hayır</option>
                                    </select>
                                </div>
                            </div>
                            @foreach(\App\Language::all() as $k => $v)
                                <div class="row" style="border: 1px solid #dddddd; margin-bottom: 5px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >Proje Adı [{{$v['name']}}]</label>
                                            <input type="text" name="name[{{$v['id']}}]" class="slug-name form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Proje Açıklama[{{$v['name']}}]</label>
                                            <input type="text" name="text[{{$v['id']}}]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="mt-3 btn btn-primary">Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection