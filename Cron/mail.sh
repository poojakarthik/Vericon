#!/bin/bash

if [ -f /var/vc_tmp/new_email ]
then
    while read line
    do
        IFS=' ' read -a array <<< "$line";
        pass=$(perl -e 'print crypt($ARGV[0], "password")' ${array[1]});
        /usr/sbin/useradd -s /usr/sbin/nologin -M -d /home/mail -g vcmail -p $pass ${array[0]};
    done < /var/vc_tmp/new_email
    rm /var/vc_tmp/new_email
fi

if [ -f /var/vc_tmp/edit_email ]
then
    while read line
    do
        IFS=' ' read -a array <<< "$line";
        pass=$(perl -e 'print crypt($ARGV[0], "password")' ${array[1]});
        /usr/sbin/usermod -p $pass ${array[0]};
    done < /var/vc_tmp/edit_email
    rm /var/vc_tmp/edit_email
fi