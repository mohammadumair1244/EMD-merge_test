$(document).ready(function () {
    $.getJSON("https://api.ipify.org?format=json", function (data) {
        var ip = data.ip;
        $.ajax({
            type: "POST",
            url: "/contact",
            data: {
                ip,
                _token: $('meta[name="_token"]').attr("content"),
            },
            success: function (response) {},
        });
    });
});
