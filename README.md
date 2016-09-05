# Freshdesk-Glip Notifier #

* Uses Freshdesk API to get all tickets with open status that were created for specific period.
* Uses Glip Webhooks to post notifications of said tickets created to specific conversation.

### Cron Setup ###

```
* * * * * wget -O - http://burgosoft.com/tsd/support/freshdesk-glip.php >/dev/null 2>&1
```