#!/bin/bash
\cp -Rf dnsreport /usr/local/cwpsrv/htdocs/resources/admin/modules/
mkdir /usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports
chattr -i /usr/local/cwpsrv/htdocs/admin/admin
\cp -f downloadreport.php /usr/local/cwpsrv/htdocs/admin/admin
chattr +i /usr/local/cwpsrv/htdocs/admin/admin
if ! grep -q "\-- cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
then
cat <<'EOF' >> /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
<!-- cwp_dnsreport -->
<noscript>
<li class="custom-menu"> <!-- this class "custom-menu" was added so you can remove the Developer Menu easily if you want -->
    <a href="?module=dnsreport/index"><span class="icon16 icomoon-icon-chrome"></span>DNS Report</a>
</li>
</noscript>
<script type="text/javascript">
        $(document).ready(function() {
                var newButtons = ''
                +' <li>'
                +' <a href="?module=dnsreport/index" class=""><span aria-hidden="true" class="icon16 icomoon-icon-right-arrow-3"></span>DNS Report</a>'
                +'</li>';
                $("ul#mn-12-sub").append(newButtons);
        });
</script>
<!-- end cwp_dnsreport -->
EOF
fi
