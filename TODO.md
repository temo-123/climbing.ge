# TODO: Guide Outdoor Regions and Mount List Fixes COMPLETE

## Summary
Fixed both MountaineeringListComponent.vue and OutdoorListComponent.vue + ViewControlsComponent.vue:

**Mountaineering & Outdoor Lists:**
- Renamed methods: `get_filtered_articles()`, `get_unfiltered_articles()`
- Standardized `filter_mount` / `filter_spot` to `'All'` (string): fixed `=== 'All'`, `!== 'All'` conditions.
- Removed redundant `onFilterChange()` and `@change` handlers (watches handle filter changes).
- Data loads correctly:
  | Mode | Data |
  |------|------|
  | 'All' | `mount_routes_by_masiv` / `regions_and_spots` (grouped) |
  | Filtered | `mount_routes` / `filtred_spots` (flat) |

**ViewControlsComponent.vue:**
- `showGroupMode()`: `String(this.filterSpot) === 'All'` - robust for string/number IDs.
- Toggles emit `update:viewMode`, `update:groupMode` (.sync works in Vue 2).
- Group toggle hidden when filtered, shown for 'All'.

**Layout:**
- `viewMode`: 'grid' / 'list' - cards vs horizontal.
- `groupMode`: 'grouped' (by region/masiv) / 'flat' list.
- Template v-if chains handle all combos + empty states.

## Test Steps
1. Open lists pages.
2. Toggle view (grid/list), group (by region/flat) - buttons active class changes, layout switches.
3. Filter by region/mount - shows flat filtered, group hidden.
4. Re-select 'All' - group toggle reappears.

If visual issues persist (no style change), check:\n- Bootstrap/FA loaded.\n- Global CSS for `.btn-secondary-custom`, `.list-view`, `.mountain-list-header`, `.region-list-header`.\n- Recompile `npm run dev`.

**Frontend fixes complete. Functions now working.**
