#VeriCon Readme

##Crontabs
###Nightly Clear
```
0 0 * * * /home/odai/nightly_clear.php
```

```php
<?php
###############################################
###     Midnight CronTab	    ###
###############################################

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

//log everyone out
mysql_query("DELETE FROM currentuser") or die(mysql_error());

//clear sales customers temporary
mysql_query("DELETE FROM sales_customers_temp") or die(mysql_error());

//clear sales temporary packages
mysql_query("DELETE FROM sales_packages_temp") or die(mysql_error());
?>
```
###DB Backup
```
0 3 * * * /home/odai/db-backup.sh
```

```bash
#!/bin/bash

mysqldump -u vericon -p18450be vericon > /home/odai/vericon.sql
mysqldump -u vericon -p18450be leads > /home/odai/leads.sql
```

##Other
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