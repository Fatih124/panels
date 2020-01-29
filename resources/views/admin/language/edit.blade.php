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
                    <h6 class="c-grey-900">Dil Düzenle</h6>
                    <div class="mT-30">
                        <form action="{{ route('admin.language.update',['id'=>$data[0]['id']]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Dil Adı</label>
                                <input required type="text" class="form-control" name="name" value="{{$data[0]['name']}}" id="exampleInputEmail1">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Code</label>
                                <input required type="text" class="form-control" name="code" value="{{$data[0]['code']}}" id="exampleInputPassword1">
                            </div>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection