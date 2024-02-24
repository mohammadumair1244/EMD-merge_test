$(document).ready(function () {
    init_tinymce();
});

function init_tinymce() {
    tinymce.init({
        selector: '.tool_textarea',
        plugins: 'preview searchreplace autolink code visualblocks fullscreen image link media table anchor insertdatetime advlist lists wordcount help charmap',
        // imagetools_cors_hosts: ['picsum.photos'],
        // menubar: 'file edit view insert format tools table help',
        menubar: true,
        // toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar: " formatselect | " +
            "alignleft aligncenter" +
            "alignright alignjustify | bullist numlist outdent indent | " +
            "removeformat | help | gallaryButton | codeButton",
        // toolbar_sticky: true,
        // autosave_ask_before_unload: true,
        // autosave_interval: "30s",
        // autosave_prefix: "{path}{query}-{id}-",
        // autosave_restore_when_empty: false,
        // autosave_retention: "2m",
        // image_advtab: true,


        element_format: "html",
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


        content_css: '//www.tiny.cloud/css/codepen.min.css',
        // link_list: [
        //     { title: 'My page 1', value: 'http://www.tinymce.com' },
        //     { title: 'My page 2', value: 'http://www.moxiecode.com' }
        // ],
        // image_list: [
        //     { title: 'My page 1', value: 'http://www.tinymce.com' },
        //     { title: 'My page 2', value: 'http://www.moxiecode.com' }
        // ],
        // image_class_list: [
        //     { title: 'None', value: '' },
        //     { title: 'Some class', value: 'class-name' }
        // ],
        // importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
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