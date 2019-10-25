#!/bin/sh

Lockfile=/tmp/refreshViews.lock

if [ -f $Lockfile ]
then
  echo "Vorheriger Prozess läuft noch - Exit"
  exit
fi

echo >$Lockfile

PGPASSWORD=mailjet2019! psql -U mailjet -d mailjet -c 'REFRESH MATERIALIZED VIEW contactstatistics'

PGPASSWORD=mailjet2019! psql -U mailjet -d mailjet -c 'REFRESH MATERIALIZED VIEW public.boa_sit_sumacontacts' 

PGPASSWORD=mailjet2019! psql -U mailjet -d mailjet -c 'REFRESH MATERIALIZED VIEW public.zpluscontacts' 


rm $Lockfile
