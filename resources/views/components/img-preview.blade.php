@props(['url', 'inputName'])
<div>
    <div class="input-group">
        <input type="url" placeholder="image url to preview" id="url-to-preview" name="{{ $inputName }}"
            class="form-control featured_img" value="{{ @$url }}" aria-label="Recipient's username">
        <button class="btn input-group-text btn-dark waves-effect waves-light" id="check-btn"
            type="button">Check</button>
    </div>
</div>
{{-- <div class="mt-3 mb-3"> --}}
<div class="p-3 border text-center">
    <img src="{{ @$url }}" alt="image" id="preview-of-img" class="img-fluid">
</div>

<script>
    var base_url = "{{ URL::to('/') }}/";
    $(document).ready(function() {
        $("#check-btn").on("click", function() {
            let url = $("#url-to-preview").val();
            if (url == "") {
                $("#preview-of-img").hide();
                return;
            }
            $("#preview-of-img").attr('src', base_url + url)
            $("#preview-of-img").show();
        });
        let url = $("#url-to-preview").val();
        if (url == "") {
            $("#preview-of-img").hide();
        }
    });
</script>
