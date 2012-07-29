#!/bin/bash

mysqldump -u vericon -p18450be vericon > /home/odai/vericon.sql
mysqldump -u vericon -p18450be leads > /home/odai/leads.sql