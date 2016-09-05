# Freshdesk-Glip Notifier #

* Uses Freshdesk API to get all tickets with open status that were created for the past minute.
* Uses Glip Webhooks to post notifications of said tickets creation to specific conversation.

### Cron Setup ###

> * * * * * wget -O - http://burgosoft.com/tsd/support/freshdesk-glip.php >/dev/null 2>&1