# Localization Fixes TODO

- [x] Update resources/lang/i18n/en.json: Change "article_right_navbar" to "article_right_nabar" in the guide section.
- [x] Update resources/lang/i18n/ka.json: Fix keys 'auther' to 'author', 'creating data' to 'creating_data', 'first ascent' to 'first_ascent', 'revew' to 'review'; fix value for 'grade YDS' from 'სირთულე UDS' to 'სირთულე YDS'; change 'grade FR' to 'grade fr' and 'grade YDS' to 'grade yds' to match en.json case.
- [x] Verify translations work correctly after changes.

# Add Sectors Section to Right Menu

- [x] Add "sectors" translation to en.json and ka.json under guide.article_right_nabar. (Already exists)
- [x] Modify RightMenuComponent.vue to include a "sectors" menu item with submenu listing sector names that scroll to their sections.
- [x] Add logic to fetch sectors data (names and IDs for scrolling) via API.
- [x] Ensure sector sections in SectorComponent.vue have IDs for scrolling.
- [x] Test the new sectors menu functionality.
