@extends('layouts.front')
@section('seo')
    <title>{{\App\LanguageContent::getTitle(BLOG_LANGUAGE,$categoryId)}}</title>
    <meta name="description" content="{{\App\LanguageContent::getDescription(BLOG_LANGUAGE,$categoryId)}}">
    <meta name="keywords" content="{{\App\LanguageContent::getKeywords(BLOG_LANGUAGE,$categoryId)}}">
@endsection
@section('content')


    <div class="pager-header">
        <div class="container">
            <div class="page-content">
                <h2>{{\App\LanguageContent::getName(BLOG_CATEGORY_LANGUAGE,$categoryId)}}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">@lang("general.home")</a></li>
                    <li class="breadcrumb-item active">@lang("general.blog")</li>
                </ol>
            </div>
        </div>
    </div><!-- /Page Header -->


    <section class="blog-section bg-grey padding">
        <div class="container">
            <div class="row right-sidebar">
                <div class="col-lg-9 xs-padding">
                    <div class="blog-items row">
                        @foreach($posts as $k => $v)
                            <div class="col-sm-6 padding-15">
                                <div class="blog-post">
                                    <img src="{{asset(\App\LanguageContent::getImage(BLOG_LANGUAGE,$v['id']))}}" alt="blog post">
                                    <div class="blog-content">
                                        <span class="date"><i class="fa fa-clock-o"></i> {{ $v['date'] }}</span>
                                        <h3><a href="{{ route('front.blog.view',['slug'=>\App\LanguageContent::getSlug(BLOG_LANGUAGE,$v['id'])]) }}">{{ \App\LanguageContent::getName(BLOG_LANGUAGE,$v['id']) }}</a></h3>
                                        <p>{!! \App\Helper\mHelper::split(\App\LanguageContent::getText(BLOG_LANGUAGE,$v['id']),100)!!}</p>
                                        <a href="#" class="post-meta">@lang("general.read_more")</a>
                                    </div>
                                </div>
                            </div><!-- Post 1 -->
                        @endforeach
                    </div>
                {!! $posts->links() !!}
                <!-- Pagination -->
                </div><!-- Blog Posts -->
                @include('front.blog.sidebar')
            </div>
        </div>
    </section><!-- /Blog Section -->
@endsection