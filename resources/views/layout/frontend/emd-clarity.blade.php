@production
    @php
        $emd_microsoft_clarity_key = @get_setting_by_key('emd_microsoft_clarity_key')->value;
    @endphp
    @if (@$clarity_check)
        @if ($clarity_check->display && $emd_microsoft_clarity_key != '' && mt_rand(1, 100) <= $clarity_check->percent)
            <script type="text/javascript">
                (function(c, l, a, r, i, t, y) {
                    c[a] = c[a] || function() {
                        (c[a].q = c[a].q || []).push(arguments)
                    };
                    t = l.createElement(r);
                    t.async = 1;
                    t.src = "https://www.clarity.ms/tag/" + i;
                    y = l.getElementsByTagName(r)[0];
                    y.parentNode.insertBefore(t, y);
                })
                (window, document, "clarity", "script", "{{ $emd_microsoft_clarity_key }}");
            </script>
        @endif
    @endif
@endproduction
