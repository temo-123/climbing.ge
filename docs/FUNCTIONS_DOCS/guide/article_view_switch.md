# Article pages: Text / Cards view

A switch that shows the sections of any guide article page either as flowing text (default) or as separate white cards.

## Where

- **Desktop:** under the "Navigation Menu" title in the right menu (`items/navbars/RightMenuComponent.vue`). Between 768 and 1199 px the "View" label is hidden so both buttons fit.
- **Phones:** in the "On this page" bottom sheet (`global_components/OnThisPageComponent.vue`, `:view_switch="true"` from the guide `IndexComponent.vue` only — blog pages don't get it).
- Component: `items/ArticleViewSwitchComponent.vue`, built on the shared segmented switch `items/ViewSegmentSwitchComponent.vue` (also used by the [sector views](sector_views.md)).

## How it works

- State: `services/guide/articleView.js` → `article_view.mode` (`plain` | `cards`), per device in `localStorage cg_article_view` (try/catch), applied as `<html data-article-view="…">`.
- Every section block carries `class="cg-section"`: in `ArticleTextBlocksComponent.vue` the description, best time, address, how to get there + map (one block), working hours, price, what you need, info; the routes block on outdoor / ice / mountaineering; the gallery. Plain view: the class adds nothing. Cards view (`sass/components/_article.scss`): white card, `--cg-line` border, `--cg-r-lg`, small shadow, its title at the top, smaller padding on phones.
- Not cards: the comments (a form), the massif card and the weather widget (already cards), sector blocks (own layout).
- After a switch a `resize` event is fired (next frame), so Google Charts and canvases inside the sections re-measure to the new width (the route chart used to be clipped in cards view).

## Adding a section

Give its wrapper `class="cg-section"`; the title should be its first child `h2`.

## i18n

`guide.article_view.*` (label, plain, cards) in `en.json` and `ka.json`.
