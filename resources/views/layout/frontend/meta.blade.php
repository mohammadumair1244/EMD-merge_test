@if (!isset($show_main))
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="_token" content="{{ csrf_token() }}" />
    @if (config('constants.nofollow_noindex') == 'yes')
        <meta name="robots" content="noindex , nofollow" />
    @endif
    <title>{!! @$meta_title !!}</title>
    <meta name="description" content="{!! @$meta_description !!}" />
    {{-- HOME TOOL --}}
    @if (isset($show_canonicals))
        @if ($is_home)
            <link rel="canonical" href="{{ urldecode(request()->url()) }}" />
            <link rel="alternate" hreflang="x-default" href="{{ route('home') }}" />
            <link rel="alternate" hreflang="{{ config('constants.native_languge') }}" href="{{ route('home') }}" />
            @foreach ($links as $item)
                @if ($item->lang != config('constants.native_languge'))
                    <link rel="alternate" hreflang="{{ $item->lang }}"
                        href="{{ urldecode(route('other_language_tool', ['lang' => $item->lang, 'slug' => $item->slug])) }}" />
                @endif
            @endforeach
        @else
            {{-- FOR OTHER TOOL --}}
            <link rel="canonical" href="{{ urldecode(request()->url()) }}" />
            <link rel="alternate" hreflang="x-default"
                href="{{ urldecode(route('native_language_tool', ['slug' => $parent_slug])) }}" />
            <link rel="alternate" hreflang="{{ config('constants.native_languge') }}"
                href="{{ urldecode(route('native_language_tool', ['slug' => $parent_slug])) }}" />
            @foreach ($links as $item)
                @if ($item->lang != config('constants.native_languge'))
                    <link rel="alternate" hreflang="{{ $item->lang }}"
                        href="{{ urldecode(route('other_language_tool', ['lang' => $item->lang, 'slug' => $item->slug])) }}" />
                @endif
            @endforeach
        @endif
    @else
        @if (!isset($show_blog_canonicals))
            <link rel="canonical" href="{{ urldecode(request()->url()) }}" />
        @endif
    @endif
@endif
@if (isset($show_blog_canonicals))
    <link rel="canonical" href="{{ urldecode(route('page.single_blog', ['slug' => $blog['slug']])) }}" />
    <link rel="alternate" hreflang="x-default"
        href="{{ urldecode(route('page.single_blog', ['slug' => $parent->slug])) }}" />
    <link rel="alternate" hreflang="{{ config('constants.native_languge') }}"
        href="{{ urldecode(route('page.single_blog', ['slug' => $parent->slug])) }}" />
    @foreach ($children as $item)
        @if ($item->lang_key != config('constants.native_languge'))
            <link rel="alternate" hreflang="{{ $item->lang_key }}"
                href="{{ urldecode(route('single_blog_other_language', ['lang' => $item->lang_key, 'slug' => $item->slug])) }}" />
        @endif
    @endforeach
@endif
