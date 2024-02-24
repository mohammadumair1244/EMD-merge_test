@php
    header('Content-Type: text/xml');
@endphp
{!! '<' . '?xml version="1.0"?>' !!}
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if (count($links) > 0)
        @foreach ($links as $link)
            <url>
                <loc> {{ $link }}</loc>
            </url>
        @endforeach
    @endif
</urlset>
