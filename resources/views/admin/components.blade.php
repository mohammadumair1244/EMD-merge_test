@extends('admin')
@section('head')
    <style>
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                <h4 class="my-3">How To Add Components In Blade Files:</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="" class="form-label">Gallary</label>
                        <div>
                            <x-admin.gallary :images="$images"></x-admin.gallary>
                            <x-admin.media :images="$images" name="test" imageId="null"></x-admin.media>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Code</label>
                        <div>
                            <code>
                                @php
                                    echo '<h4>Add Component:</h4>';
                                    echo htmlspecialchars('<x-admin.gallary :images="$images"></x-admin.gallary>');
                                    echo '<br>';
                                    echo '<h4>Manipulation in Controller to Get Images:</h4>';
                                    $html = "
                                                                                    images = Media::get_media();
                                                                                    <br>
                                                                                    return view('view_path')->with(['images'=>images]);
                                                                                ";
                                    echo $html;
                                @endphp
                            </code>
                        </div>
                    </div>


                </div>
                <div class="row mt-5">
                    <div class="col-md-6">
                        <label for="" class="form-label">Component Preview</label>
                        <x-admin.img-preview inputName="" />
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Shortcode to add component</label>
                        <div>
                            <code>
                                @php
                                    echo htmlspecialchars('<x-admin.img-preview />');
                                @endphp
                            </code>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
@endsection
