#!/bin/sh

Lockfile=/tmp/mailjetContactSync.lock

if [ -f $Lockfile ]
then
  echo "Vorheriger Prozess läuft noch - Exit"
  exit
fi

echo >$Lockfile
cd /srv/mailJet-contacts/mailjet-contacts/public
/usr/bin/php snyc_mailjetContacts.php.php  >> /var/log/mailjet/mailjetCOntactSync.log

rm $Lockfile
