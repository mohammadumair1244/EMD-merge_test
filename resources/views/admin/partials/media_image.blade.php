<div class="col-sm-3 position-relative">
    <div class="delete">
        <i class="fa fa-times del_image" aria-hidden="true" data-path="{{ $image }}"></i>
        <i class="fas fa-copy copy_url" data-url='{{ asset($image) }}'></i>
    </div>
    <div class="image-box">
        <img src='{{ asset("$image") }}' alt='image' data-path="{{ $image }}" class='img-fluid rounded'
            width='200'>
    </div>
</div>
