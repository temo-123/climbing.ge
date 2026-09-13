// Shared "extra drawing" state machine — a general annotation layer tied only
// to the shared background IMAGE (not to any one sector/route/pitch drawn on
// it), toggled on/off via a button next to the item picker. Originally built
// for sectorLocaleImageEditorComponent.vue (SectorLocalImageExtraDrawing),
// then copied into sectorRouteDrawingsEditorComponent.vue and
// CanvasRouteEditorComponent.vue (SectorImageExtraDrawing) with the same
// shape. Extracted here so every host shares one implementation of the
// toggle/save/delete flow; each host still owns its OWN REST endpoints
// (the three backend controllers are intentionally separate, per-entity
// copies — see docs/BACKEND/CANVAS.md) and its own composite-image build
// step, since those differ per context.
//
// HOST CONTRACT — a component using this mixin MUST provide:
//
// Data:
//   - `extraDrawingEndpoints: { getForEditor: (id) => url, save: (id) => url, delete: (id) => url }`
//   - `extraDrawingAlertKeys: { noResourceSelected, noDrawingData, confirmDelete,
//     confirmSaveBeforeSwitch }` — full i18n key paths (this host's own
//     translation namespace).
//   - `saving` (Boolean), `saveStatus` (String|null) — shared with the host's
//     own main save flow if it has one; declared here too as a safe default
//     for hosts that don't otherwise have them (e.g. a modal with only an
//     extra-drawing save button).
//
// Methods:
//   - `_extraDrawingResourceId()` — returns the id to interpolate into the
//     three endpoint URLs above (often `this.$route.params.id`, but a modal
//     fed by a prop must return that prop instead).
//   - `_saveMainDrawing()` (optional) — the host's own "Save" button method
//     (e.g. `saveChanges`/`saveRouteDrawing`/`savePitchDrawing`). Every host
//     already guards that method with `if (this.extra_drawing_mode) return
//     this.saveExtraDrawing();`, so calling it here (only ever done while
//     extra_drawing_mode is still false, right before the switch) safely
//     runs just the main-drawing save path. Omit to skip the confirm/autosave
//     flow below entirely (the toggle then behaves as before). MUST resolve
//     to exactly `false` on any failure OR early validation-guard return
//     (never just throw, and never resolve `undefined` on a failure path) —
//     the confirm-before-switch flow in toggleExtraDrawingMode() uses this to
//     abort the switch instead of proceeding to discard the unsaved edits it
//     was just asked to protect. Resolving `true` on success is enough;
//     `undefined` is treated as success too (existing hosts predate this
//     contract and return nothing on their success path).
//   - `_buildExtraDrawingComposite(json)` (optional) — async, returns
//     `{ editedImageData, canvasWidth, canvasHeight, bgBoundsPayload }` for
//     the save POST. Each host already has its own composite-rendering
//     helper (background photo + every sibling's + this drawing's own
//     strokes) — this hook just lets the mixin call it without needing to
//     know how it works. Omit entirely to save without a composite image.
//   - `_onExtraDrawingSaved(responseData)` (optional) — post-save side
//     effects specific to the host (e.g. flipping to the backed-up original
//     image URL once `has_original` is confirmed).
//   - `_onExtraDrawingDeleted()` (optional) — post-delete side effects
//     specific to the host (e.g. emitting a bus event so a sibling component
//     refreshes its own view of this same background image).
//   - `_layoutMeta(drawing)` (optional) — if the host already has this
//     helper (sector_local_image/spot_rock_image do, for the
//     canvas_width/height + bg_* fields), it's reused for the loaded extra
//     drawing's meta; otherwise meta is left null.
//
// Dirty tracking (for the confirm-before-switch prompt): the host must call
// `this.markDrawingDirty()` as the first line of its `handleCanvasData()`
// handler — unconditionally, regardless of which mode is active. This mixin
// reads `this.extra_drawing_mode` at that moment to file the edit under the
// right flag (`_mainDrawingDirty` / `_extraDrawingDirty`). Both flags default
// to false and are cleared automatically by this mixin's own save/delete/load
// methods; a host whose OWN load methods reset `canvasData`/`pitch_json`/etc.
// (switching route/pitch/image) should also clear `this._mainDrawingDirty`
// there, so stale edits from a previously viewed item don't trigger a
// pointless confirm later.
export default {
    data() {
        return {
            // "Extra drawing" mode: a general annotation layer tied only to
            // the shared image, not to any one item drawn on it.
            extra_drawing_mode:    false,
            extra_drawing_json:    null,
            extra_drawing_meta:    null,
            extra_drawing_loading: false,
            deletingExtraDrawing:  false,
            saving:     false,
            saveStatus: null,
            // Has the drawing currently on screen been edited since it was
            // last loaded/saved? Tracked separately per side of the toggle —
            // see the "Dirty tracking" note above.
            _mainDrawingDirty:  false,
            _extraDrawingDirty: false,
        };
    },

    methods: {
        // HOST CONTRACT — call unconditionally from handleCanvasData(). Files
        // the edit under whichever side of the toggle is currently active.
        markDrawingDirty() {
            if (this.extra_drawing_mode) this._extraDrawingDirty = true;
            else this._mainDrawingDirty = true;
        },

        // Loaded eagerly (not just on first toggle) so it's already available
        // to show as a reference overlay in normal mode from the start.
        loadExtraDrawing() {
            const id = this._extraDrawingResourceId();
            if (!id) return;
            axios.get(this.extraDrawingEndpoints.getForEditor(id))
                .then(response => {
                    const drawing = response.data && response.data.extra_drawing;
                    this.extra_drawing_json = drawing ? drawing.json : null;
                    this.extra_drawing_meta = typeof this._layoutMeta === 'function' ? this._layoutMeta(drawing) : null;
                    this._extraDrawingDirty = false;
                })
                .catch(error => console.log(error));
        },

        // General-purpose guard: is it OK to abandon/reload whatever is
        // CURRENTLY shown (either side of the extra-drawing toggle)? If it
        // has edits never saved to the server, confirms with the user and
        // saves first — returns `false` (the CALLER must abort whatever
        // selection change it was about to make: picking a different
        // route/pitch/sector/image, or toggling the extra-drawing mode
        // itself) on Cancel or a failed save, `true` otherwise.
        //
        // HOST CONTRACT: every host method that's about to swap
        // `activeJsonProp` to a different item's json — picking a different
        // route/pitch/sector/image, not just the extra-drawing toggle button
        // — must `await` this and bail out early on `false`. Bug fixed
        // (September 2026): only the toggle button itself had this
        // protection; selecting a different route/pitch/sector while
        // editing a dirty extra drawing silently discarded it, reported as
        // "select extra drawing, make some changes, select another
        // route/sector — it switches to normal drawing and loses my extra
        // drawing."
        //
        // `_saveMainDrawing()`/`saveExtraDrawing()` must resolve `false` on
        // any failure or validation-guard early-return (not just throw) — a
        // falsy result here means the save did NOT actually happen. An
        // earlier version of this flow `await`ed the save and proceeded
        // regardless of the outcome, so a save that failed still let the
        // switch through — the Editor immediately reimports the OTHER
        // side's/item's json onto the same canvas, permanently discarding
        // the very edits the confirm prompt was supposed to protect.
        async confirmSaveIfDirty() {
            const dirty = this.extra_drawing_mode ? this._extraDrawingDirty : this._mainDrawingDirty;
            if (!dirty) return true;
            const shouldSave = confirm(this.$t(this.extraDrawingAlertKeys.confirmSaveBeforeSwitch));
            if (!shouldSave) return false;
            const saved = this.extra_drawing_mode
                ? await this.saveExtraDrawing()
                : (typeof this._saveMainDrawing === 'function' ? await this._saveMainDrawing() : true);
            return saved !== false;
        },

        // Switches the editor between "the selected item's own drawing" and
        // the image's general extra-info layer, shared by every item using
        // this image — (re)fetched fresh whenever entering the mode, in case
        // it changed since the eager load above. See confirmSaveIfDirty()
        // above for the unsaved-changes protection this relies on.
        async toggleExtraDrawingMode() {
            if (!(await this.confirmSaveIfDirty())) return;
            const turningOn = !this.extra_drawing_mode;

            if (turningOn) {
                const id = this._extraDrawingResourceId();
                this.extra_drawing_loading = true;
                try {
                    const response = await axios.get(this.extraDrawingEndpoints.getForEditor(id));
                    const drawing = response.data && response.data.extra_drawing;
                    this.extra_drawing_json = drawing ? drawing.json : null;
                    this.extra_drawing_meta = typeof this._layoutMeta === 'function' ? this._layoutMeta(drawing) : null;
                    this._extraDrawingDirty = false;
                } catch (e) {
                    this.extra_drawing_json = null;
                    this.extra_drawing_meta = null;
                } finally {
                    this.extra_drawing_loading = false;
                }
            }
            this.extra_drawing_mode = !this.extra_drawing_mode;
        },

        // Returns `true`/`false` — the confirm-before-switch flow in
        // toggleExtraDrawingMode() relies on this to know whether the save
        // actually happened before proceeding to switch modes; see its own
        // comment for the bug this return value fixes.
        async saveExtraDrawing() {
            const id = this._extraDrawingResourceId();
            if (!id) { alert(this.$t(this.extraDrawingAlertKeys.noResourceSelected)); return false; }

            this.saving     = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent && this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.extra_drawing_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.extra_drawing_json = json; }
                }
                if (!json) { alert(this.$t(this.extraDrawingAlertKeys.noDrawingData)); return false; }

                const composite = typeof this._buildExtraDrawingComposite === 'function'
                    ? await this._buildExtraDrawingComposite(json)
                    : { editedImageData: null, canvasWidth: 0, canvasHeight: 0, bgBoundsPayload: {} };

                const response = await axios.post(this.extraDrawingEndpoints.save(id), {
                    json,
                    edited_image:  composite.editedImageData,
                    canvas_width:  composite.canvasWidth,
                    canvas_height: composite.canvasHeight,
                    ...composite.bgBoundsPayload,
                });

                const ok = !!response.data.success;
                this.saveStatus = ok ? 'ok' : 'error';
                if (ok) this._extraDrawingDirty = false;
                if (typeof this._onExtraDrawingSaved === 'function') this._onExtraDrawingSaved(response.data);
                setTimeout(() => { this.saveStatus = null; }, 3000);
                return ok;
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
                return false;
            } finally {
                this.saving = false;
            }
        },

        async deleteExtraDrawing() {
            if (!confirm(this.$t(this.extraDrawingAlertKeys.confirmDelete))) return;

            const id = this._extraDrawingResourceId();
            this.deletingExtraDrawing = true;
            try {
                await axios.delete(this.extraDrawingEndpoints.delete(id));
                this.extra_drawing_json = null;
                this._extraDrawingDirty = false;
                this.saveStatus = 'ok';
                if (typeof this._onExtraDrawingDeleted === 'function') this._onExtraDrawingDeleted();
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                this.saveStatus = 'error';
            } finally {
                this.deletingExtraDrawing = false;
            }
        },
    },
};
