# Article comments

Comment thread under guide pages (outdoor / ice spots, articles, mountaineering, business pages, special articles).

## Files

- Frontend: `resources/js/components/guide/items/comments/CommentFormComponent.vue` (prop `article_id`; parents call `update(id)` when the article changes).
- API: `GET get_article/get_guide_comment/get_article_comments/{article_id}`, `POST set_guide_comment_by_gest/create_comment/{article_id}` (dual-flow, reCAPTCHA v3), `DELETE set_article/set_guide_comment/del_comment/{id}`, `POST set_article/set_guide_comment/add_comment_complaint/`.
- Backend: `Api\Guide\CommentController`, `App\Services\CommentService::create_comment` (validation `data.name` ≤25, `data.surname` ≤55, `data.email`, `data.text` — errors come back keyed `data.*`).
- i18n: `guide.comments.*`; report reasons reuse `shop.product.feedback.reasons.*` (the stored value stays the English sentence).

## Design concept

One white card for writing, then the thread. Nothing blocks with `alert()`: errors sit under the field, the result shows as an inline notice under the button.

## Form

- **Signed in:** a small "Commenting as <avatar> Name Surname" line; name / surname / email are sent from the profile. If the server rejects them (empty surname…), a warning links to the user page Options.
- **Guest:** a chalk strip "Log in to comment with your profile…" with a **Log in** button (`open-login-modal`, re-reads the user afterwards), then name / surname / email with labels. Email hint: not shown publicly, a confirmation link is sent (guest comments stay unpublished until confirmed).
- Textarea with a live `n / 500` counter (turns rope-orange near the limit), Ctrl/⌘+Enter sends.
- **Reply:** *Reply* (signed-in only) on a comment scrolls to the form, focuses it and shows a "Replying to <name>" chip with a close button. Replies are one level deep.
- Send button shows a spinner; reCAPTCHA v3 is loaded on demand with an 8 s timeout (ad-blockers stub `grecaptcha`), failure shows the reCAPTCHA notice.
- Client checks before sending: required fields, email shape, non-empty text.

## Thread

- Header: title + total count (comments + replies) pill + round refresh button (spins while loading).
- Each comment: avatar (photo or initials; click → user modal, guests → toast), name (links to the climber profile when registered, *Guest* badge otherwise), date, text (keeps line breaks), text actions *Reply* · *Delete* (own) / *Report* (others).
- Replies are indented with a left rule, on chalk, with "→ <parent name>".
- Empty state: "No comments yet — be the first to share beta."
- **Report** modal: radio cards with the reasons; sending without a choice shows "Please pick a reason."
