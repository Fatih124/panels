@extends('layouts.app')

@section('content')
    <div id="mainContent">
        <div class="row gap-20">
            <div class="col-md-6"></div>
            <div class="col-md-12">
                @if(session("status"))
                    <div class="alert alert-primary">
                        {{session("status")}}
                    </div>
                @endif
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Blog Yazısı Düzenle</h6>
                    <div class="mT-30">
                        <form enctype="multipart/form-data" action="{{ route('admin.blog.update', ['id'=>$data[0]['id']])}}" method="POST">
                            @csrf

                            @foreach(\App\Language::all() as $k => $v)
                                <div class="row" style="border: 1px solid #dddddd; margin-bottom: 5px;">
                                    @if(\App\LanguageContent::get($v['id'],BLOG_LANGUAGE, IMAGE_LANGUAGE,$data[0]['id'])!="")

                                        <div class="col-md-12">
                                            <img src="{{asset(\App\LanguageContent::get($v['id'], BLOG_LANGUAGE,IMAGE_LANGUAGE,$data[0]['id']))}}" style="width: 100px;">
                                        </div>

                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Blog Resim [{{$v['name']}}]</label>
                                            <input  type="file" class="form-control" name="image[{{$v['id']}}]"  >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >Blog Adı [{{$v['name']}}]</label>
                                            <input type="text" name="name[{{$v['id']}}]" class="slug-name form-control" value="{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, NAME_LANGUAGE,$data[0]['id'])}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label >Blog Url [{{$v['name']}}]</label>
                                            <input type="text" name="slug[{{$v['id']}}]" class="slug-url form-control" value="{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, SLUG_LANGUAGE,$data[0]['id'])}}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Blog Title[{{$v['name']}}]</label>
                                            <input type="text" name="title[{{$v['id']}}]" class="form-control" value="{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, TITLE_LANGUAGE,$data[0]['id'])}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Blog Description[{{$v['name']}}]</label>
                                            <input type="text" name="description[{{$v['id']}}]" class="form-control" value="{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, DESCRIPTION_LANGUAGE,$data[0]['id'])}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Blog Keywords[{{$v['name']}}]</label>
                                            <input type="text" name="keywords[{{$v['id']}}]" class="form-control" value="{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, KEYWORDS_LANGUAGE,$data[0]['id'])}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">İçerik</label>
                                            <textarea name="text[{{$v['id']}}]" id="" cols="30" rows="10" class="ckeditor">{{\App\LanguageContent::get($v['id'], BLOG_LANGUAGE, TEXT_LANGUAGE,$data[0]['id'])}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kategori Adı</label>
                                        <select name="categoryId" id="" class="form-control">
                                            @foreach($category as $k => $v)
                                                <option @if($data[0]['categoryId']==$v['id']) selected @endif value="{{$v['id']}}">{{\App\LanguageContent::get(DEFAULT_LANGUAGE,BLOG_CATEGORY_LANGUAGE,NAME_LANGUAGE,$v['id'])}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Yazı Gösterimi ?</label>
                                    <select name="isShow" id="" class="form-control">
                                        <option @if($data[0]['isShow'] == 0) selected @endif value="0">Evet</option>
                                        <option @if($data[0]['isShow'] == 1) selected @endif value="1">Hayır</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Tarih</label>
                                    <input type="text" name="date" class="form-control" value="{{$data[0]['date']}}">
                                </div>
                            </div>
                            <button type="submit" class="mt-3 btn btn-primary">Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection