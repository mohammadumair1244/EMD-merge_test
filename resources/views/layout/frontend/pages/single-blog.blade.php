@extends('main')
@section('content')
    <section>
        <div class="container blog-single-page">
            <div class="d-flex flex-start">
                <div class="item1">
                    @php
                        $path_ = $blog['images']['300xauto'];
                    @endphp
                    <div class="flex-item-blog-a-img-all">
                        <img src="{{ asset("$path_") }}" alt="{{ $blog['title'] }}">
                    </div>
                    <div class="single-blog-page">
                        <h1>{{ $blog['title'] }}</h1>
                        @php
                            $created_at = $blog['created_at'];
                            $created_at = strtotime($created_at);
                            $new_date = date('d M, Y', $created_at); 
                        @endphp
                    </div>
                    <div class="time_section border-bottom mb-1">
                        <time>Publish Date: {{  $new_date }}</time>
                    </div>
                    <div class="content-section">
                        {!! $blog['detail'] !!} 
                    </div>
                </div>
                <div class="item2">
                    
                    @if  (count(@$custom) < 1)
                    {{ 'No se encontró ningún blog' }}
                    @else
                    <div class="sidebar-blogs">
                        <h2>Recent Post</h2>
                        @foreach ($custom as $cs)
                        <div class="flex-item-blog-side border-bottom">
                            <div class="flex-item-blog-a-side">
                                <a href="{{ route('page.single_blog', ['slug'=> $cs['slug'] ]) }}" class="flex-item-blog-a-img-side">
                                    <img src="{{ asset(@$cs['images']['original']) }}" alt="{{ $cs['title'] }}">
                                </a>
                                <p>
                                    {{ $cs['title'] }}
                                </p>
                                <div class="mt-5">
                                    <a class="btn btn-white" href="{{ route('page.single_blog', ['slug'=> $cs['slug'] ]) }}">Aprende más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
