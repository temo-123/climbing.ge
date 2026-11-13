# Turn off all notification emails

One switch stops every notification email a person gets from climbing.ge. Account emails keep coming.

| Stopped (notification mail) | Still sent (account mail) |
|---|---|
| New content and update alerts, favourites, event reminders | Verify e-mail, password reset, welcome |
| Follow activity, "new follower" | Orders, payments, custom-product requests |
| Back-in-stock alerts | Tour reservations and guide mails |
| Seasonal picks ([seasonal_campaigns.md](seasonal_campaigns.md)) | Comment moderation (hidden, report decision), role changes |
| Replies to your comment (registered commenters) | Staff / admin mails |

## Where people switch it

- **Options → Notifications**: a switch at the top, "Turn off all notification emails". It saves at once. While it's on, the category choices below stay editable but look paused, and they're kept for when it's switched back. The existing **Disable all** button now also turns it on (so follow and comment-reply mails stop too). **Enable all** turns it off.
- **Every notification mail** has a footer link, "unsubscribe from all", and the headers `List-Unsubscribe` + `List-Unsubscribe-Post: List-Unsubscribe=One-Click`, so Gmail / Outlook show their own Unsubscribe button (RFC 8058).

## Storage

`user_notifications.notification_type` → `all_off: true`. `get_options/get_user_notification_data` returns it (default `false`). `update_user_notification_data` saves it, and **keeps the saved value when a client doesn't send the key**, so an older app build saving other settings can't switch mail back on. The mobile app can show the same switch from that endpoint.

## Enforcement (three points)

1. `NotificationDispatchService::sendOnce()` returns before logging or queuing anything for a muted user. That covers content alerts, favourites, event reminders, follow activity, new follower and back-in-stock.
2. `SeasonalCampaignService::audience_query()` leaves muted users out.
3. **Safety net at send time:** every notification mail carries the header `X-CG-Notification: 1` (`NotificationPreferenceService::add_headers()`: `UserNotificationMessage`, `SeasonalCampaignMessage`, `CommentAnswerNotification`). `MailGuardService` removes muted recipients from such a mail and cancels it when nobody is left. So a mail already in the queue when the person muted is still stopped. Mail without the header (account mail) is never touched.

A **new notification mail** must call `NotificationPreferenceService::add_headers($message, $manage_url, $unsubscribe_url)` in `withSymfonyMessage()`, or the switch won't stop it.

## One-click unsubscribe link

`GET|POST /api/notifications/unsubscribe/{user_id}/{hash}` (`Api\User\UnsubscribeController`, public, `throttle:30,1`, in `ALWAYS_OPEN` of the e-mail and age gates).

- `hash = HMAC-SHA256("unsubscribe|{id}|{lowercase email}", APP_KEY)`. Changing the e-mail kills old links (404).
- **GET** only shows a page ("Turn off all notification emails?" + button), because mail scanners open links.
- **POST** sets `all_off = true`. That's the page button, or a mail client's one-click POST (`List-Unsubscribe=One-Click`). `action=on` switches mail back on ("Turn them back on" on the result page).
- Page: `resources/views/notifications/unsubscribe.blade.php`, standalone (no Vue), English or Georgian by the user's `lang`, masked e-mail ("n****@gmail.com"), `noindex`, light and dark.

## Code

- `app/Services/NotificationPreferenceService.php`: `is_muted`, `muted_emails`, `set_muted`, `unsubscribe_url(_for_email)`, `add_headers`
- `app/Services/MailGuardService.php` (step 1b), `NotificationDispatchService::sendOnce()`, `SeasonalCampaignService::audience_query()`
- UI: `resources/js/components/user/items/user_options/UserNotificationsListComponent.vue`
- Tests: `tests/Feature/NotificationMuteTest.php`
