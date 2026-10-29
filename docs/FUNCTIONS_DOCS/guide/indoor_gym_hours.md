# Indoor gym opening hours

## Summary

Every indoor gym can show when it is open: the same hours every day, different hours per weekday, or a status (temporarily closed, coming soon…) instead of hours. The site uses them on the gym card, the gym page and the list's "open now" filter.

## At a glance

| | |
|---|---|
| Where | Guide: indoor list (cards, "open now" filter / sort), indoor gym page (article section "Opening hours") |
| Who | Admins set them; everyone sees them |
| Edited in | Admin article → **Global info** tab → **Working time** (add and edit page) |
| Files | `user/pages/articles/forms/{add,edit}_forms/GlobalDataFormComponent.vue`, `user/items/form/parts/WeeklyHoursInputComponent.vue`, `guide/items/IndoorHoursComponent.vue`, `guide/items/article/ArticleTextBlocksComponent.vue`, `guide/items/cards/IndoorCardComponent.vue`, `services/guide/gymHours.js`; backend `Admin\Guide\ArticleController` (`normalize_work_status`, `save_weekly_hours`), `App\Services\GymHoursService`, `Api\Guide\GymHoursController` |
| Technical reference | [GUIDBOOK.md](../../GUIDBOOK.md) (Indoor) |

## How it works

**Admin — one select decides everything (Working time):**
- *Status: normal working hours* → two time inputs, open / close. The gym opens every day at those times.
- *Different hours on different days* → a table, Monday to Sunday: open, close, or **Closed**, plus "Copy Monday to all days". It starts from the every-day times.
- *Temporarily closed / Closed for the sport season / Coming soon / No info* → no time inputs; the status replaces the hours, and saving clears them.
- Everything is saved with the article's **Save** button; there is no separate tab.

**Storage:**
- Every-day hours: `articles.open_time` / `closed_time`.
- Per-day hours: `article_working_hours` (one row per weekday). "Different hours on different days" is not a stored status: `work_status` stays `null`, and the rows existing is what makes the gym "per-day". Choosing any other option deletes the rows.
- With per-day hours, `open_time` / `closed_time` get the week's most common hours, so older readers still see something sensible.

**Site:**
- Gym page: an **Opening hours** section in the middle of the article (after *How to get there* / map, before *Price*): one "Every day" line, or the whole week with today highlighted and closed days marked, plus a live badge (Open now / Closes in N min / Opens in N min / Closed now). Hidden while an alert status is set (the status alert at the top says why).
- Card: today's hours (or "Closed today") and the live badge.
- List: the "open now" filter and sort use that day's hours.

## Price ("No information about prices")

The Global info tab's price uses the same pattern: a select with *Price: minimal price* (shows the minimal-price input) or *No information about prices* (`articles.price_status = 'no_info'`, the price is cleared). The site then shows "No information about prices" on the card and in the article's *Price* section.

## Rules

- Hours that close after midnight (18:00 – 01:00) belong to the day they opened.
- A day with a missing or invalid time is saved as closed.
- A status always wins over hours.
