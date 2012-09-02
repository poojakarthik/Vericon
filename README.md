#VeriCon Readme

##Crontabs
```
* * * * * php /var/vericon/cron/lock_clear.php &> /dev/null
0 0 * * * php /var/vericon/cron/nightly_clear.php &> /dev/null
5 0 * * * php /var/vericon/cron/sbt_dsr.php Business &> /dev/null
5 0 * * * php /var/vericon/cron/sbt_dsr.php Residential &> /dev/null
5 0 * * * php /var/vericon/cron/zen_dsr.php Business &> /dev/null
5 0 * * * php /var/vericon/cron/zen_dsr.php Residential &> /dev/null
0 1 * * * /var/vericon/cron/db_backup.sh
```
##Other
###Additional Libraries
```
apt-get install dos2unix
```
###Directories
```
/var
drwxr-xr-x  www-data www-data  dsr
drwxr-xr-x  www-data www-data  leads
drwxr-xr-x  www-data www-data  rec

/var/leads
drwxr-xr-x  www-data www-data  archive
drwxr-xr-x  www-data www-data  log
```
###/etc/fstab
```
tmpfs   /var/vtmp   tmpfs   size=1G   0   0
```