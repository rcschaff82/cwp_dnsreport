#!/bin/bash
\rm -Rf /usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport
chattr -i /usr/local/cwpsrv/htdocs/admin/admin/
chattr -i /usr/local/cwpsrv/htdocs/admin/admin/downloadreport.php
\rm -f /usr/local/cwpsrv/htdocs/admin/admin/downloadreport.php
chattr +i /usr/local/cwpsrv/htdocs/admin/admin/
# Remove From Menu
sd=$(grep -n "<\!-- cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php | cut -f1 -d:)
ed=$(grep -n "<\!-- end cwp_dnsreport --" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php | cut -f1 -d:)
cmd="$sd"",""$ed""d"
sed -i.bak -e "$cmd" /usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php
clear
echo "cwp_dnsreport has been successfully removed from CWP"
echo "If you wish to completely remove from the server, please delete the install directory"
echo "If you wish to install a new version, use ' git pull ' and ' ./install '"
