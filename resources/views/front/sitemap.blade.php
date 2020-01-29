<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{$now}}</lastmod>
        <changefreg>Daily</changefreg>
    </url>

    @foreach($service as $k => $v)
    <url>
        <loc>{{ route('front.service.index',['slug'=>$v['value']]) }}</loc>
        <lastmod>{{$now}}</lastmod>
        <changefreg>Daily</changefreg>
    </url>
    @endforeach

    @foreach($page as $k => $v)
        <url>
            <loc>{{ route('front.page.index',['slug'=>$v['value']]) }}</loc>
            <lastmod>{{$now}}</lastmod>
            <changefreg>Daily</changefreg>
        </url>
    @endforeach

    @foreach($blog as $k => $v)
        <url>
            <loc>{{ route('front.blog.index',['slug'=>$v['value']]) }}</loc>
            <lastmod>{{$now}}</lastmod>
            <changefreg>Daily</changefreg>
        </url>
    @endforeach
</urlset>