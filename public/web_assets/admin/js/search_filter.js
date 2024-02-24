function boldString(e, t) {
    t = new RegExp(t, "gi");
    return e.replace(t, function (e, t, n) {
        return "<b>" + e + "</b>"
    })
}
function autocomplete(o, i) {
    var l;
    function c(e) {
        for (var t = document.getElementsByClassName("autocomplete-items"), n = 0; n < t.length; n++) e != t[n] && e != o && t[n].parentNode.removeChild(t[n])
    }
    o.addEventListener("input", function (e) {
        var t, n, o, a = this.value;
        if (c(), !a) return !1;
        for (l = -1, (t = document.createElement("DIV")).setAttribute("id", this.id + "autocomplete-list"), t.setAttribute("class", "autocomplete-items"), this.parentNode.appendChild(t), n = 0; n < i.length; n++) {
            var r = i[n][1].toUpperCase().indexOf(a.toUpperCase());
            r < 0 && (r = 0), i[n][1].substr(r, a.length).toUpperCase() == a.toUpperCase() && ((r = document.createElement("a")).innerHTML += boldString(i[n][1], a), r.setAttribute('href', window.location.origin + "/admin/" + i[n][0]), r.innerHTML += "<input type='hidden' value='" + i[n][0] + "'>", r.addEventListener("click", function (e) {
                window.location = "/admin/" + this.getElementsByTagName("input")[0].value, c()
            }), t.appendChild(r));
        } (o = document.querySelector("#search_queryautocomplete-list")).innerHTML.length <= 0 && (o.innerHTML = '<span style="display:block;width:100%;text-align:center;"><strong>Nothing Found</strong></span>')
        arrow_up_down_run();
    }), document.addEventListener("click", function (e) {
        c(e.target)
    })
}


document.addEventListener('keydown', function (event) {
    if (event.altKey && event.key === 's') {
        document.getElementById('search_query').focus();
    }
});

function arrow_up_down_run() {
    const anchors = document.querySelectorAll('#search_queryautocomplete-list a');
    document.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
            event.preventDefault();
            const focusedAnchor = document.activeElement;
            const currentIndex = Array.from(anchors).indexOf(focusedAnchor);
            const nextIndex = (event.key === 'ArrowDown') ? currentIndex + 1 : currentIndex - 1;
            if (nextIndex >= 0 && nextIndex < anchors.length) {
                focusedAnchor.classList.remove('focused');
                anchors[nextIndex].focus();
                anchors[nextIndex].classList.add('focused');
            }
        }
    });
}