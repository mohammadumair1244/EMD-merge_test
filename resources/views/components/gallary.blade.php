@props(['images'])
<button type="button" class="btn btn-primary d-none" id="gallaryModalBtn" data-bs-toggle="modal"
    data-bs-target="#full-width-modal">Gallary</button>
<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Gallary</h4>
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
                        <div class="row" id="gallary_img_stats">
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
                            <div class="col-sm-12 d-none" style="text-align: right">
                                <button type="button" class="btn btn-primary copy_link_btn">Copy Link</button>
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
        $('label').on('click', function() {
            $(this).next().focus();
        });
        $(".modal_close_btn").on('click', function() {
            clear_inputs();
        });
        $("#gallaryModalBtn").on("click", function() {
            clear_inputs();
        })
        $(".copy_link_btn").on('click', function() {
            var url = $("#gallary_img_stats #image_url").val();
            navigator.clipboard.writeText(url);
        });
        $(document).on("click", ".gallary_img", function(e) {
            var img = $(this)[0];
            $('.image-box').removeClass('selected-box');
            $(this).parent('.image-box').addClass('selected-box');
            $("#gallary_img_stats #image_size").val(($(this).data('size') / 1024).toFixed(2) + " KB");
            $("#gallary_img_stats #image_url").val($(this).attr('src'));
            $("#gallary_img_stats #dimensions").val(img.naturalWidth);
            $("#gallary_img_stats #image_path").val($(this).data('path'));
        });
    });

    function clear_inputs() {
        $("#gallary_img_stats #image_size").val("");
        $("#gallary_img_stats #image_url").val("");
        $("#gallary_img_stats #dimensions").val("");
        $("#gallary_img_stats #image_path").val("");
    }
</script>
