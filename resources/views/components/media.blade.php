@props(['images', 'name', 'imageId'])
@php
if (!is_null($imageId)) {
    $path = App\Models\Media::get_column($imageId, 'path');
}
@endphp
<div class="col-12 my-2 text-center">
    <input type="hidden" id="media_img_id" name="{{ $name }}" value="{{ @$imageId }}">
    <div class="border radius p-2">
        <img src="{{ asset($path) }}" id="{{ 'image_' . $name }}" alt="" class="img-fluid">
    </div>
    <button type="button" class="btn col-12 btn-primary" data-bs-toggle="modal"
        data-bs-target="#{{ 'model-' . $name }}">Insert Media</button>
</div>
<div id="{{ 'model-' . $name }}" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="fullWidthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Media</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row modal_images_row">
                            @if (isset($images))
                                {!! $images !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row" id="{{ 'media-stats-' . $name }}">
                            <div class="col-sm-12 mb-3">
                                <label for="" class="form-label">URL</label>
                                <input type="url" class="form-control" id="image_url" placeholder="image url"
                                    readonly value="">
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="" class="form-label">Path</label>
                                <input type="text" class="form-control" id="image_path" placeholder="image path"
                                    readonly value="">
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="" class="form-label">Size</label>
                                <input type="text" class="form-control" id="image_size" placeholder="image size"
                                    readonly value="">
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="" class="form-label">Width</label>
                                <input type="text" class="form-control" id="dimensions"
                                    placeholder="image dimensions" readonly value="">
                            </div>
                            <div class="col-sm-12" style="text-align: right">
                                <button type="button" class="btn btn-primary" id="insertImageBtn"
                                    data-media-name="{{ $name }}">Insert Image</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light modal_close_btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        let parent_id = '#media-stats-' + '{{ $name }}';
        $('label').on('click', function() {
            $(this).next().focus();
        });
        $(".modal_close_btn").on('click', function() {
            $(parent_id + " " + "#image_size").val("");
            $(parent_id + " " + "#image_url").val("");
            $(parent_id + " " + "#dimensions").val("");
            $(parent_id + " " + "#image_path").val("");
        });
        $(".copy_link_btn").on('click', function() {
            var url = $("#image_url").val();
            navigator.clipboard.writeText(url);
        });

        $(document).on("click", ".gallary_img", function(e) {
            var img = $(this)[0];
            $('.image-box').removeClass('selected-box');
            $(this).parent('.image-box').addClass('selected-box');
            $(parent_id + " " + "#image_size").val(($(this).data('size') / 1024).toFixed(2) + " KB");
            $(parent_id + " " + "#image_url").val($(this).attr('src'));
            $(parent_id + " " + "#dimensions").val(img.naturalWidth);
            $(parent_id + " " + "#image_path").val($(this).data('path'));
        })

        $("#insertImageBtn").on("click", function(e) {
            var imageID = $('#model-' + '{{ $name }}').find('.image-box.selected-box').find(
                "img").data('id');
            $("#media_img_id").val(imageID);
            var imageSrc = $('#model-' + '{{ $name }}').find('.image-box.selected-box').find(
                "img").attr('src');
            $('#image_' + '{{ $name }}').attr('src', imageSrc);
            $('#model-' + '{{ $name }}').modal('toggle');
        });
    });
</script>
