#!/bin/bash
\cp -Rf dnsreport /usr/local/cwpsrv/htdocs/resources/admin/modules/
chattr -i /usr/local/cwpsrv/htdocs/admin/admin
\cp -f downloadreport.php /usr/local/cwpsrv/htdocs/admin/admin
chattr +i /usr/local/cwpsrv/htdocs/admin/admin
if ! grep -q "\-- cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
then
cat <<'EOF' >> /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
<!-- cwp_icecast -->
<noscript>
</ul>
<li class="custom-menu"> <!-- this class "custom-menu" was added so you can remove the Developer Menu easily if you want -->
    <a href="?module=icecast"><span class="icon16 icomoon-icon-volume-high"></span>DNS Report</a>
</li>
<li style="display:none;"><ul>
</noscript>
<script type="text/javascript">
        $(document).ready(function() {
                var newButtons = ''
                +' <li>'
                +' <a href="?module=icecast" class=""><span aria-hidden="true" class="icon16 icomoon-icon-volume-high"></span>DNS Report</a>'
                +'</li>';
                $("li#mn-3").before(newButtons);
        });
</script>
<!-- end cwp_dnsreport -->
EOF
fi
