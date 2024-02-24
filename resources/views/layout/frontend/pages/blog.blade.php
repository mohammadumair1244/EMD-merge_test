@extends('main')
@section('content')
    <section class="banner">
        <div class="container blog-home-page">
            <div class="p-20 border-bottom">
                <h1>Blog</h1>
            </div>
            @if (empty($blogs))
                <div>
                    <div class="section-container text-center" style="padding: 20px 0px;text-align: center;font-size: 20px;">
                        {{ 'No se encontró ningún blog' }}
                    </div>
                </div>
            @else
            <div class="d-flex-s blog-main-flex">
                @foreach ($blogs as $blog)
                
                <div class="flex-item-blog">
                        <div class="flex-item-blog-a">
                            <a href="{{ route('page.single_blog', ['slug'=> $blog['slug'] ]) }}" class="flex-item-blog-a-img">
                            
                                    <img src="{{ asset(@$blog['images']['original']) }}" alt="{{ $blog['title'] }}">
                                
                            </a>
                            <p>
                                {{ $blog['title'] }}
                            </p>
                            <div class="mt-5">
                                <a class="btn btn-white" href="{{ route('page.single_blog', ['slug'=> $blog['slug'] ]) }}">Aprende más</a>
                            </div>
                        </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
    </section>
@endsection
