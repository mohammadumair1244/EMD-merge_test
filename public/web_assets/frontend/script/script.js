var acc = document.getElementsByClassName("accordion");
var i;
var countercol = 0;
var countersample = 0;
var clrsarr = ["blue", "green", "red", "olive", "orange", "violet"];
for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}

$(document).ready(function () {
    var currentURL = window.location.href;
    var parts = currentURL.split("/"); // Split the URL by "/"
    var lastPart = parts[parts.length - 1]; // Get the last part of the URL path

    if (lastPart === "contacto") {
        addShadowRootElement();
    } else if (
        lastPart !== "politica-de-privacidad" &&
        lastPart !== "terminos-y-condiciones"
    ) {
        addShadowRootElement2();
    }

    // document.querySelectorAll(".faq .c-pointer").forEach(function (element) {
    //     element.addEventListener("click", function () {
    //         var pElement = this.parentElement.querySelector("p");
    //         var imgElement = this.querySelector("img");

    //         if (window.getComputedStyle(pElement).display === "none") {
    //             pElement.style.display = "block";
    //             imgElement.src = "/web_assets/frontend/img/upward_arrow.png";
    //         } else {
    //             pElement.style.display = "none";
    //             imgElement.src = "/web_assets/frontend/img/downward_arrow.png";
    //         }
    //     });
    // });

    $(".faq .c-pointer").click(function() {
        var pElement = $(this).parent().find("p");
        var imgElement = $(this).find("img");
    
        pElement.slideToggle();
        imgElement.attr("src", function(index, attr) {
            if (attr === "/web_assets/frontend/img/downward_arrow.png") {
                return "/web_assets/frontend/img/upward_arrow.png"; // Change to upward arrow
            } else {
                return "/web_assets/frontend/img/downward_arrow.png"; // Change to downward arrow
            }
        });
    });
    
});

$(".toggle-btn").on("click", function () {
    if ($(".navbar").css("display") == "none") {
        $(".navbar").css("display", "block");
        $(".language-div").css("display", "block");
    } else {
        $(".navbar").css("display", "none");
        $(".language-div").css("display", "none");
    }
});
$("#file_upload").on("click", function (e) {
    $("#upload_file").click();
});
$("#upload_file").on("change", function (e) {
    $("#upload_file_form").click();
});
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$("#gettext-form").on("submit", function (e) {
    e.preventDefault();

    run_loader();
    var fd = new FormData();
    if ($("#upload_file").val() != "") {
        var files = $("#upload_file")[0].files[0];
        fd.append("file", files);
    } else {
        alert("js");
    }

    $.ajax({
        type: "POST",
        url: "/gettext",
        data: fd,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#input").val(response);
            totwords();
            stop_loader();
            var wordCountDisplay = document.getElementById("footer-hide");
            wordCountDisplay.style.display = "none";
            var totalwordscount = document.getElementById("total-words-count");
            totalwordscount.style.display = "block";
        },
        error: function (error) {
            alert_box(error.responseJSON.error, '/web_assets/frontend/img/error_1.svg');
            stop_loader();
        },
    });
});

const addShadowRootElement2 = () => {
    const wrap = document.createElement("div");
    wrap.textContent = "Submit";
    const btnShadowRoot = document
        .querySelector("#jsShadowRoot2")
        .attachShadow({ mode: "closed" });
    const btnWrap = document.createElement("button");

    // Create text node for button label
    const buttonText = document.createTextNode("PARAFRASEO ");

    const imgElement = document.createElement("img");
    imgElement.src = "web_assets/frontend/img/arrow.png?v=1";
    imgElement.alt = "arrow icon";

    // Apply styles to the image
    imgElement.style.width = "20px";
    imgElement.style.marginLeft = "5px";

    // Append the text and image to the button
    btnWrap.appendChild(buttonText);
    btnWrap.appendChild(imgElement);

    const btnStyle = {
        borderRadius: "30px",
        padding: "6px 16px",
        background: "var(--blue-color)",
        color: "#fff",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-evenly",
        border: "none",
        cursor: "pointer",
    };
    Object.assign(btnWrap.style, btnStyle);
    btnShadowRoot.appendChild(btnWrap);

    btnWrap.addEventListener("click", () => {
        var input = escapeHtml($("#input").val());
        // var length_ = $("#total_words").html();
        // if (length_ < 20) {
        //     alert_box("Length must be greater than 20!");
        //     return false;
        // } else if (length_ > 2000) {
        //     alert_box("Length must be smaller than 2000!");
        //     return false;
        // } else {
        //   $("#loader").css("display", "block");

        var length_ = $("#total_words").html();
        if (length_ == 0) {
            alert_box("No se encontró texto!",'/web_assets/frontend/img/no_text.svg');
            return false;
        }
        showLoadingState();
        $.ajax({
            url: "/parafraseo",
            type: "POST",
            data: {
                data: input,
            },
            success: function (res) {
                // $(".result_alternate").css("display", "none");
                $(".result-sec").css("display", "block");
                var words_total = res.replace(/\n/g, "<br>")
                $("#output").html(words_total);
                const text = words_total.replace(/<[^>]*>/g, "");
                const words = text.split(" ");
                const wordCount = words.length;
                document.getElementById("total_words_response").innerHTML =
                    wordCount;
                $("#result_tools").css(
                    "display",
                    "flex"
                );
                $("#resultado, #palabras").css(
                    "visibility",
                    "visible"
                );
                hideLoadingState(); // Hide loading state after response is received
                suggestions();
            },
        });
        // }
    });
};

$("#email").on("keyup", function () {
    $("#email-error").hide();
});

$("#input").on("keyup", function () {
    $("#input-error").hide();
});
$("#username").on("keyup", function () {
    $("#username-error").hide();
});

const addShadowRootElement = () => {
    const wrap = document.createElement("div");
    wrap.textContent = "Submit";
    const btnShadowRoot = document
        .querySelector("#jsShadowRoot")
        .attachShadow({ mode: "closed" });
    const btnWrap = document.createElement("button");

    // Create text node for button label
    const buttonText = document.createTextNode("Aplique Ahora");

    const imgElement = document.createElement("img");
    imgElement.src = "web_assets/frontend/img/arrow.png?v=1"; // Use the correct relative path
    imgElement.alt = "arrow icon";

    // Apply styles to the image
    imgElement.style.width = "20px";
    imgElement.style.marginLeft = "5px";

    // Append the text and image to the button
    btnWrap.appendChild(buttonText);
    btnWrap.appendChild(imgElement);

    const btnStyle = {
        borderRadius: "30px",
        padding: "6px 16px",
        background: "var(--blue-color)",
        color: "#fff",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-evenly",
        border: "none",
        cursor: "pointer",
    };
    Object.assign(btnWrap.style, btnStyle);
    btnShadowRoot.appendChild(btnWrap);

    btnWrap.addEventListener("click", () => {
        var name = $("#username").val();
        var email = $("#email").val();
        var message = $("#input").val();
        var formIsValid = true;
        if (name.trim() === "") {
            $("#username-error").text("Por favor, introduzca su nombre").show();
            formIsValid = false;
        }
        if (email.trim() === "") {
            $("#email-error")
                .text(
                    "Por favor, introduzca su dirección de correo electrónico"
                )
                .show();
            formIsValid = false;
        }
        if (message.trim() === "") {
            $("#input-error").text("Por favor, introduzca su mensaje").show();
            formIsValid = false;
        }

        if (formIsValid) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
                },
            });
            showLoadingState();
            $.ajax({
                type: "POST",
                url: "/contact",
                data: {
                    name: name,
                    email: email,
                    message: message,
                },
                success: function (response) {
                    console.log("Form submitted successfully");
                    // Display success message
                    $(".success-message").show();
                    $(".error-message").hide();
                    setTimeout(function () {
                        $(".success-message").hide();
                    }, 5000);
                },
                error: function (xhr, status, error) {
                    // Display error message
                    $(".success-message").hide();
                    $(".error-message").show();
                    setTimeout(function () {
                        $(".error-message").hide();
                    }, 5000);
                },
                complete: function () {
                    $("#username").val("");
                    $("#email").val("");
                    $("#input").val("");
                    hideLoadingState(); // Hide loading state after response is received
                },
            });
        }
    });
};
function showLoadingState() {
    $("#loading").show();
}

function hideLoadingState() {
    $("#loading").hide();
}

// $("#form").on("submit", function (e) {
//     e.preventDefault();
//     // run_loader();
//     var input = escapeHtml($("#input").val());
//     var length_ = $("#total_words").html();
//     if (length_ < 20) {
//         alert_box("Length must be greater than 20!");
//         return false;
//     } else if (length_ > 2000) {
//         alert_box("Length must be smaller than 2000!");
//         return false;
//     } else {
//         //   $("#loader").css("display", "block");

//         $.ajax({
//             url: "/parafraseo",
//             type: "POST",
//             data: {
//                 data: input,
//                 lang: "en",
//             },
//             success: function (res) {
//                 $(".result_alternate").css("display", "none");
//                 $(".result-sec").css("display", "block");
//                 $("#output").html(res);
//                 // stop_loader();
//                 suggestions();

//             },
//         });
//     }
// });
function reset_form() {
    $("#output").html("");
    $("#input").val("");
    // $(".result_alternate").css("display", "block");
    // $(".result-sec").css("display", "none");

    // Set the total_words span content to 0
    $("#total_words").text("0");
    $("#result_tools").css("display", "none");
    $("#resultado, #palabras").css("visibility", "hidden");
}

// function download_form(format) {

//     var copyText = $("#output").find("span");
//     var text = "";
//     for (var i = 0; i < copyText.length; i++) {
//         text += copyText[i].innerText + " ";
//     }

//     $temp = $("<input>");
//     $("body").append($temp);
//     var all_text = $temp.val(text);
//     var file_content = all_text.val();
//     var blob;
//     var filename;

//     if (format === "txt") {
//         blob = new Blob([file_content], { type: "text/plain" });
//         filename = "file.txt";
//     }  else if (format === "pdf") {
//         var pdf = new jsPDF();
//         var lines = pdf.splitTextToSize(file_content, pdf.internal.pageSize.width - 20); // Split text into lines
//         pdf.text(10, 10, lines); // Add text to PDF
//         blob = pdf.output("blob"); // Convert PDF to blob
//         filename = "file.pdf";
//     } else if (format === "docx") {
//         var blob = new Blob([file_content], {
//             type: "application/msword",
//         });
//         filename = "file.docx";
//     }

//     if (blob) {
//         var link = document.createElement("a");
//         link.href = URL.createObjectURL(blob);
//         link.download = filename;
//         document.body.appendChild(link);
//         link.click();
//         document.body.removeChild(link);
//     }
// }


function download_form(format) {
    var copyText = $("#output").find("span");
    var text = "";
    for (var i = 0; i < copyText.length; i++) {
        text += copyText[i].innerText.replace(/<br>/g, "\n") + " ";
    }

    var blob;
    var filename;

    if (format === "txt") {
        blob = new Blob([text], { type: "text/plain" });
        filename = "file.txt";
    } else if (format === "pdf") {
        var pdf = new jsPDF();
        pdf.text(10, 10, text);
        blob = pdf.output("blob");
        filename = "file.pdf";
    } else if (format === "docx") {
        // You need to handle conversion to docx separately as it's a bit complex
        // You may use libraries like Mammoth.js for this purpose
        // Here, I'll simply treat it as a text file
        blob = new Blob([text], { type: "application/msword" });
        filename = "file.docx";
    }

    if (blob) {
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}


function escapeHtml(text) {
    var map = {
        "<": "&lt;",
        ">": "&gt;",
    };

    return text.replace(/[<>]/g, function (m) {
        return map[m];
    });
}

function suggestions() {
    tooltip();
    //show_error_box("tipper called");
    $(".qtiperar").each(function (index) {
        if (countercol < 5) {
            countercol++;
        } else {
            countercol = 0;
        }
        $(this).css("color", clrsarr[countercol]);
        var orgt = $(this).attr("data-title");

        //console.log(orgt+"title attr");
        var piecest = orgt.split("|");
        var originalw =
            '<a class="wrsug" href="javascript:;" onclick="dowordreplace(\'' +
            escape(piecest[0]) +
            "')\">" +
            piecest[0] +
            "</a>";

        var sugst = "";
        var templastelem = $(this);
        for (var t = 1; t < piecest.length; t++) {
            sugst +=
                '<a class="wrsug" href="javascript:;" onclick="dowordreplace(\'' +
                escape(piecest[t]) +
                "')\">" +
                piecest[t] +
                "</a>";
        }
        $(this).qtip({
            api: {
                onRender: function () {
                    lastelem = templastelem;
                },
                beforeHide: function () {
                    //$('.qtip-content').remove();
                    //$('#customexcerpt').remove();
                    //lastelem=null;
                },
                onContentUpdate: function () {
                    //show_error_box("content update");
                    $(".sbtexceptr").click(function () {
                        //var neww=$('#customexcerpt').val();
                        var neww = $(this)
                            .siblings(".customexcerpt")
                            .attr("value");
                        neww = $.trim(neww);
                        //show_error_box(neww);
                        if (neww.length < 1) {
                            return;
                        }
                        dowordreplace(escape(neww));
                    });
                },
            },
            content:
                "<b>Original word:</b> " +
                originalw +
                "<br />" +
                "<b>Word Suggestion(s):</b>" +
                sugst +
                '<br /><b>Or add your own:</b><br /><div><input class="customexcerpt" type="text" /><input class="sbtexceptr" type="button" value="Use" /></div>' +
                '<hr class="allm2" /><b>Click on original word(above) to restore.<br />Click on any alternate words for replacement.<br />Click outside the balloon to close it.</b>',
            show: {
                when: "click", // Show it on click
                solo: true, // And hide all other tooltips
            },
            hide: "unfocus",
            style: {
                width: 320,
                padding: 5,
                background: "#A2D959",
                color: "black",
                textAlign: "left",
                border: {
                    width: 7,
                    radius: 5,
                    color: "#A2D959",
                },
                tip: "topLeft",
                name: "dark", // Inherit the rest of the attributes from the preset dark style
            },
        });
    });
    //
}

function tooltip() {
    $(".qtiperar").each(function (index) {
        var ip = index + 1;
        $(this).attr("id", "tip_" + ip);
    });
    var current = 0;
    $(".qtiperar").click(function () {
        if ($(this).hasClass("active")) {
            $(".tooltip_main").hide();
            $(".qtiperar").removeClass("active");
            $(this).removeClass("active");
            $(this).removeClass("activeTip");
        } else {
            $(".qtiperar").removeClass("active");
            $(this).addClass("active");
            $(".qtiperar").removeClass("activeTip");
            $(this).addClass("activeTip");
            $(".tooltip_main").show();
        }
        current = $(this).index();

        var offset = $(this).offset();
        var topoff = parseInt(offset.top) + 33;

        if ($(window).width() < 500) {
            $(".tooltip_main").css("min-width", "241px");
            $(".tooltip_innerbox").css("min-width", "245px");
            offset.left = 50;
        }
        var topoff = parseInt(offset.top) + 33;
        $(".tooltip_main").css({
            left: offset.left + "px",
            top: topoff + "px",
        });
        $(".tooltip_main").attr("no", current);
        $("#useword").attr("tip", current);
        var tipword = $(this);
        var ownW = tipword.attr("own");
        if (typeof ownW !== typeof undefined && ownW !== false) {
            $("#ownword").val(ownW);
        } else {
            $("#ownword").val("");
        }
        var title = tipword.attr("data-title").split("|");
        $("#orgWord").html('<span class="word">' + title[0] + "</span> ");
        $("#sugest").html("");
        for (var i = 1; i < title.length; i++) {
            var hword = '<span class="word">' + title[i] + "</span> ";
            $("#sugest").append(hword);
        }
        $(".word").click(function () {
            tipword.html($(this).html());
            $(".tooltip_main").hide();
            $(".qtiperar").removeClass("active");
        });
        $("#useword").click(function () {
            var valw = $("#ownword").val();
            if (valw.length > 0) {
                $(".activeTip").html(valw);
                $(".activeTip").attr("own", valw);
            }
        });
    });
}

$("#crossTip").click(function () {
    $(".tooltip_main").hide();
    $(".qtiperar").removeClass("active");
});

//  var currentCallback;
function alert_box(msg,imgPath,callback) {
    $("#overlay").show();
    $(".message").text(msg);
    $('.img_place').attr('src',imgPath)
    $(".customAlert").css({
        "display": "inline",
        "background": "#fff",
        "animation": "fadeIn 0.4s linear",
        "borderRadius": "9px",
        'z-index': '3'
    });
  
    setTimeout(function () {
        $(".customAlert").css("animation", "none");
    }, 300);
    // currentCallback = callback;
}

$(function () {
    $(".rab").click(function () {
        alert_box(
            "If you think about anything, you are actually doing a recursive function which resolves your neurons into a deep pi calculation. You are then executing about 4294 threads in your brain, which do a parallel computation.",
            function () {
                //   console.log("Callback executed");
            }
        );
    });

    $(".confirmButton").click(function () {
        $(".customAlert").css("animation", "fadeOut 0.3s linear");
        // $('body').css('background', 'none');
        $("#overlay").hide();
        setTimeout(function () {
            $(".customAlert").css("animation", "none");
            $(".customAlert").css("display", "none");
        }, 300);
        // currentCallback();
    });
});

function totwords(obj = "") {
    obj = document.getElementById("input");
    if (obj.value == "") {
        document.getElementById("total_words").innerHTML = 0;
    } else {
        var resp = obj.value;
        var tot = 0;
        words = resp.split(" ");
        words.forEach((w) => {
            if (w.trim() != "") {
                tot++;
            }
        });
        document.getElementById("total_words").innerHTML = tot;
    }
}

function run_loader() {
    $(".loader-div").css("display", "flex");
}
function stop_loader() {
    $(".loader-div").hide();
}
function run_loader_response() {
    $(".loader-div-response").css("display", "flex");
}
function stop_loader_response() {
    $(".loader-div-response").hide();
}
function copy_result(e, t) {
    "Copiar" == e.getAttribute("data-title") &&
        (e.setAttribute("data-title", "Copiada"),
        setTimeout(function () {
            e.setAttribute("data-title", "Copiar");
        }, 1e3));
    var a = document.createRange();
    a.selectNode(document.getElementById(t)),
        window.getSelection().removeAllRanges(),
        window.getSelection().addRange(a),
        document.execCommand("copy"),
        window.getSelection().removeAllRanges();
}

$(document).on("click", "#sugest li", function () {
    console.log($(this).text());
    console.log(current);
    current.replaceWith(
        '<span class="sw rw" data-orignal="' +
            current.text() +
            '">' +
            $(this).html() +
            "</span>"
    );
    // Hide it AFTER the action was triggered
    $(".tooltip_main").hide(100);
});

$(document).on("click", "#list-syn .ro", function () {
    console.log($(this).text());
    console.log(current);
    current.replaceWith('<span class="sw">' + $(this).text() + "</span>");
    $(".tooltip_main").hide(100);
});

var current = "";
var orignal = "";
var current2 = "";
$("#output").on("click", ".sw", function (event) {
    event.stopPropagation();
    var text = $(this).text();

    if (!text) return;

    const last = text.charAt(text.length - 1);
    console.log(last);

    var urlRequest = "/seggetions";

    text = text.replace(/\.$|\,$|\!$|\?$|\:$/, "");
    // text = text.replace(/\,$/, "");
    // text = text.replace(/\!$/, "");
    // text = text.replace(/\?$/, "");
    // text = text.replace(/\:$/, "");
    // $(".tooltip_main").css('padding','5px');
    $(document).find("#orgWord").empty();
    $(document).find("#sugest").empty();

    console.log(text);
    orignal = text;
    current = $(this);
    var offset = event.pageX - 20;
    var topoff = event.pageY + 10;
    $(".tooltip_main").css({
        left: offset + "px",
        top: topoff + "px",
    });
    $(".tooltip_main").show();
    token = $('meta[name="_token"]').attr("content");
    run_loader_response();
    $.ajax({
        type: "get",
        url: urlRequest,
        dataType: "json",
        data: {
            word: text,
            _token: token,
        },
        success: function (data) {
            if (typeof data == "object") {
                // $(".tooltip_main").css('padding','0px');
                $(".tooltip_main").css("border", "1px solid #eee");
                $(document).find("#list-syn").empty();
                let app = $(document).find("#list-syn");
                if (typeof current.data("orignal") !== "undefined") {
                    // console.log('asdd');
                    app.prepend(
                        '<li class="ro">' + current.data("orignal") + "</li>"
                    );
                }
                var end = "";
                if (
                    last == "." ||
                    last == "?" ||
                    last == "," ||
                    last == ":" ||
                    last == "!"
                ) {
                    end = last;
                }
                let langs = data;
                let nodes = "";
                if (langs.length > 0) {
                    nodes = langs.map((lang) => {
                        if (lang != "array") {
                            let li = document.createElement("li");
                            li.textContent = lang + end;
                            return li;
                        }
                    });
                }
                $("#orgWord").empty(); // Clear previous suggestions
                $("#orgWord").append(text);
                $("#sugest").empty(); // Clear previous suggestions
                $("#sugest").append(nodes);
            } else {
                throw new Exception("seggetion not found");
            }
            stop_loader_response();
        },
        error: function (xhr, textStatus, errorThrown) {
            // console.log(xhr, textStatus, errorThrown)
            var msg = "Somthing Went wrong, Please try again!";
            if (xhr.status == 422) {
                msg =
                    xhr.responseJSON.errors.data[0] ||
                    xhr.responseJSON.message ||
                    "The given data was invalid.";
            }
            stop_loader_response();
            alert(msg);
        },
        complete: function () {
            console.log("suggetion complete");
        },
    });
});

// check input value is it null or not
function CheckInputVal() {
    var wordCountDisplay = document.getElementById("footer-hide");
    wordCountDisplay.style.display = "none";
    var totalwordscount = document.getElementById("total-words-count");
    totalwordscount.style.display = "block";
    var inputValue = document.getElementById("input").value;
    if (inputValue === null || inputValue.trim() === "") {
        wordCountDisplay.style.display = "flex";
        totalwordscount.style.display = "none";
    }
}
