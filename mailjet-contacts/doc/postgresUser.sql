CREATE USER skysheriff WITH PASSWORD 'BriefFlugzeugGewehr';
GRANT CONNECT ON DATABASE mailjet TO skysheriff;
GRANT USAGE ON SCHEMA public TO skysheriff;
GRANT SELECT ON contactstatistics TO skysheriff;



// delete user
DROP OWNED BY your_user;
DROP USER your_user;


INSERT INTO cron.job (schedule, command, nodename, nodeport, database, username)
VALUES ('30 */3 * * *', 'REFRESH MATERIALIZED VIEW public.boa_sit_sumacontacts ', 'Mailjet.zeit.de', 5432, 'masiljet', 'mailjet');

INSERT INTO cron.job (schedule, command, nodename, nodeport, database, username)
VALUES ('30 */3 * * *', 'REFRESH MATERIALIZED VIEW public.zpluscontacts ', 'Mailjet.zeit.de', 5432, 'masiljet', 'mailjet');

local    all             all                         trust. (for cron jobs)