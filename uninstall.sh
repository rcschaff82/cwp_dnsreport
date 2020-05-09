#!/bin/bash
\rm -Rf /usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport
\rm -f /usr/local/cwpsrv/htdocs/admin/admin/downloadreport.php
# Remove From Menu
sd=$(grep -n "<\!-- cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php | cut -f1 -d:)
ed=$(grep -n "<\!-- end cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php | cut -f1 -d:)
cmd="$sd"",""$ed""d"
sed -i.bak -e "$cmd" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php

