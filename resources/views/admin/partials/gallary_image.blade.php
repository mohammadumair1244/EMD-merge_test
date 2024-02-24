<div class='col-sm-3'>
    <div class='image-box'>
        <img src='{{ asset("$image") }}' alt="{{ @$image }}" data-path="{{ @$image }}"
            data-size="{{ @File::size(@$image) }}" data-id="{{ @$id }}" class='img-fluid rounded gallary_img'
            width='200'>
    </div>
</div>
