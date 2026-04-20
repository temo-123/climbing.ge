# TODO: Apply single captcha-send button fix to comment forms

## Steps:
1. [ ] Edit resources/js/components/guide/items/comments/CommentFormComponent.vue:
   - Replace captcha + buttons with single FormCapchaComponent button that triggers add_comment on verified.
   - Remove is_verify_isset, onCaptchaVerified/Expired.
   - Import FormCapchaComponent.
   - Add recaptcha_token to data and pass to backend.

2. [ ] Check FooterComponent.vue - no form found, skip changes.

3. [ ] Test comment form.

Current progress: All changes complete for CommentFormComponent.vue. FooterComponent.vue has no form/button, skipped. Task done. Test comment form in browser.
