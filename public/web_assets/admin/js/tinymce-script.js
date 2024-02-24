$(document).ready(function () {
    init_tinymce();
});

function init_tinymce() {
    tinymce.init({
        selector: ".tool_textarea",
        element_format: "html",
        menubar: true,
        autoresize_bottom_margin: 5,
        relative_urls: true,
        convert_urls: false,
        paste_as_text: false,
        max_height: 300,
        setup: function (editor) {
            editor.on("blur", function () {
                $(editor.container).removeClass("show-menubar");
            });
            editor.on("init", function () {
                const observer = new MutationObserver(handleMutations);
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });
            editor.on("click", function (e) {
                $(editor.container).addClass("show-menubar");
            });
            editor.ui.registry.addButton("gallaryButton", {
                text: "Gallary",
                tooltip: "Open Gallary",
                onAction: function () {
                    $("#gallaryModalBtn").click();
                },
            });
            editor.ui.registry.addButton("codeButton", {
                text: "Code",
                tooltip: "Insert Code",
                onAction: function () {
                    var selectedNode = tinyMCE.activeEditor.selection.getNode();
                    if (selectedNode.nodeName === 'CODE' || selectedNode.closest('code')) {
                        tinyMCE.activeEditor.formatter.remove('code');
                    } else {
                        tinyMCE.activeEditor.formatter.apply('code');
                    }
                },
            });
        },
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste code help wordcount autoresize",
        ],
        toolbar:
            " formatselect | " +
            "alignleft aligncenter" +
            "alignright alignjustify | bullist numlist outdent indent | " +
            "removeformat | help | gallaryButton | codeButton",
        content_style:
            "body { font-family:Helvetica,Arial,sans-serif; font-size:14px; max-height: 300px; overflow-y: auto;}",
    });
}
function stripHtml(html) {
    return (html = html.replace(/\'/gm, "&apos;"));
}


function handleMutations(mutationsList, observer) {
    for (const mutation of mutationsList) {
        if (mutation.type === "childList") {
            const addedNodes = Array.from(mutation.addedNodes);
            for (const node of addedNodes) {
                if (node instanceof HTMLElement && node.matches(".tox-notifications-container")) {
                    var d = document.querySelectorAll('.tox-notifications-container');
                    d.forEach(element => {
                        console.log(element);
                        element.style.removeProperty("display");
                    });
                    observer.disconnect();
                }
            }
        }
    }
}