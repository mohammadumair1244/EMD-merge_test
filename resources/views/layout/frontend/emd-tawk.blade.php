@production
    @php
        $emd_tawk_chat_url = @get_setting_by_key('emd_tawk_chat_url')->value;
        $emd_chat_status = @get_setting_by_key('emd_chat_status')->value;
    @endphp
    @if ($emd_tawk_chat_url != '' && (int) $emd_chat_status == 1)
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var tawk_chat_url = "{{ $emd_tawk_chat_url }}";
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = tawk_chat_url;
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                setTimeout(() => {
                    s0.parentNode.insertBefore(s1, s0);
                }, 300);
            })
            ();
        </script>
        <!--End of Tawk.to Script-->
    @endif
@endproduction
