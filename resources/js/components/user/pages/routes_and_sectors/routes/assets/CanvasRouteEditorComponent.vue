<template>
    <div class="form-group clearfix row">
        <div class="col-md-12">
            <h4>Route Drawing Editor</h4>
            <p class="text-muted">Use the editor to create or modify the drawing for this route. You can select an existing sector image or upload a new one to use as a background for your drawing.</p>
        </div>

        <div class="form-group clearfix row" v-if="show_alert_prop">
          <div role="alert" class="alert alert-danger cursor_pointer">
            <div class="row">
              <div class="col-md-12">
                <p>This route doesn't have a drawing yet. You can create one by adding a new route or editing an existing one with drawing tools.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group clearfix row" v-if="sector_id_prop != ''">
          <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <button
                        type="button"
                        class="btn"
                        :class="show_editor ? 'btn-danger' : 'btn-primary'"
                        @click="toggleEditor"
                    >
                        {{ show_editor ? 'Close Editor' : 'Open Editor' }}
                    </button>
                </div>
            </div>

            <StackModal
                :show="show_editor"
                title="Editor"
                size="fullscreen"
                :saveButton="{ visible: false }"
                :cancelButton="{ visible: false }"
                @close="show_editor = false"
            >
              <div class="row" v-if="show_editor && sector_images.length > 0">
                  <div class="tabs">
                      <input
                          v-for="(image, index) in sector_images"
                          :key="'input-' + image.id + '-' + index"
                          type="radio"
                          :id="'input-' + image.id"
                          :value="image.id"
                          v-model="images_tab_num"
                          @change="updateSectorImageId"
                      />
                      <label v-for="(image, index) in sector_images" :key="'label-' + image.id + '-' + index" :for="'input-' + image.id">
                          Image ID → {{ image.id }}
                          <span v-if="image.has_original" class="badge badge-success ml-1" style="font-size:10px;">original saved</span>
                          <img
                              :src="getSectorImageThumb(image)"
                              :alt="'Sector Image ' + image.id"
                              class="img-thumbnail"
                              style="max-width: 100px; max-height: 100px; margin-left: 10px;" />
                      </label>
                  </div>

                  <div class="col-md-12 mt-2 mb-2">
                      <button
                          type="button"
                          class="btn btn-success"
                          :disabled="drawing_saving"
                          @click="saveRouteDrawing"
                      >
                          <i class="fa fa-save"></i>
                          {{ drawing_saving ? 'Saving...' : 'Save Drawing' }}
                      </button>
                      <span v-if="drawing_save_status" class="ml-2" :class="drawing_save_status === 'ok' ? 'text-success' : 'text-danger'">
                          {{ drawing_save_status === 'ok' ? '✓ Drawing saved' : '✗ Save failed' }}
                      </span>
                  </div>

                  <Editor
                    ref="editorRef"
                    :image_prop="getSectorImage()"
                    :json_prop="route_json_prop"
                    :related_jsons="related_jsons"
                    @canvas_data="handleCanvasData"
                  />
              </div>
            </StackModal>

            <div class="row" v-if="!show_editor">
                <div class="col-md-12 text-center">
                    <p>Click "Open Editor" to edit route drawing</p>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-12 text-center">
                    <p>Loading sector image...</p>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    props: {
        sector_id_prop: { default: '' },
        route_json_prop: { default: null },
        show_alert_prop: { default: false },
        sector_image_id_prop: { default: '' },
        route_id_prop: { default: null },
    },
    emits: ['update:route_json_prop', 'update:sector_image_id_prop'],
    data() {
        return {
            show_editor: false,
            sector_images: [],
            images_tab_num: '',
            related_jsons: [],
            drawing_saving: false,
            drawing_save_status: null,
        }
    },
    watch: {
        sector_id_prop(newVal) {
            if (newVal) {
                this.get_sector_images(newVal);
            }
        },
        sector_image_id_prop(newVal) {
            if (newVal && !this.images_tab_num) {
                this.images_tab_num = newVal;
            }
        },
    },
    mounted() {
        if (this.sector_image_id_prop) {
            this.images_tab_num = this.sector_image_id_prop;
        }
        if (this.sector_id_prop) {
            this.get_sector_images(this.sector_id_prop);
        }
    },
    methods: {
        toggleEditor() {
            this.show_editor = !this.show_editor;
        },
        getSectorImage() {
            if (this.sector_images.length > 0) {
                const img = this.sector_images.find(i => i.id === this.images_tab_num) || this.sector_images[0];
                const dir = img.has_original
                    ? '/public/images/sector_img/origin_img/'
                    : '/public/images/sector_img/';
                return dir + img.image;
            }
            return null;
        },
        getSectorImageThumb(image) {
            return '/public/images/sector_img/' + image.image;
        },
        handleCanvasData(canvasData) {
            this.$emit('update:route_json_prop', canvasData);
        },
        updateSectorImageId() {
            this.$emit('update:sector_image_id_prop', this.images_tab_num);
            this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
        },
        get_sector_images(sectorId) {
            axios.get('/get_sector/get_sector_images/' + sectorId)
                .then(response => {
                    this.sector_images = response.data;
                    if (!this.images_tab_num && this.sector_images.length > 0) {
                        this.images_tab_num = this.sector_images[0].id;
                        this.$emit('update:sector_image_id_prop', this.images_tab_num);
                    }
                    if (this.images_tab_num) {
                        this.get_related_routes_jsons(this.images_tab_num, this.route_id_prop);
                    }
                })
                .catch(() => {});
        },
        get_related_routes_jsons(sectorImageId, excludeRouteId) {
            axios.get('/get_route/get_related_routes_jsons', {
                params: { sector_image_id: sectorImageId, exclude_route_id: excludeRouteId }
            })
                .then(response => { this.related_jsons = response.data; })
                .catch(() => {});
        },
        getAndEmitCanvasData() {
            if (this.$refs.editorRef && typeof this.$refs.editorRef.getAndEmitCanvasData === 'function') {
                this.$refs.editorRef.getAndEmitCanvasData();
            }
        },
        async saveRouteDrawing() {
            if (!this.images_tab_num) {
                alert('Please select a sector image first');
                return;
            }
            if (!this.$refs.editorRef) {
                alert('Editor is not open');
                return;
            }

            this.drawing_saving = true;
            this.drawing_save_status = null;

            try {
                const canvasContainer = this.$refs.editorRef.$refs.canvasContainer;
                const scope = canvasContainer.getCanvasScope();

                let json = this.route_json_prop;
                if (scope) {
                    json = JSON.stringify(scope.project.exportJSON());
                    this.$emit('update:route_json_prop', json);
                }

                if (!json) {
                    alert('No drawing data found. Draw something first.');
                    return;
                }

                const paperCanvas = canvasContainer.$refs.canvasManager.$el;
                const selectedImage = this.sector_images.find(img => img.id === this.images_tab_num);
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const editedImageData = await this.createCompositeImage(bgPath, paperCanvas);

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id: this.route_id_prop,
                    sector_image_id: this.images_tab_num,
                    json: json,
                    edited_image: editedImageData,
                });

                if (response.data.success) {
                    this.drawing_save_status = 'ok';
                    if (selectedImage) selectedImage.has_original = true;
                    setTimeout(() => { this.drawing_save_status = null; }, 3000);
                } else {
                    this.drawing_save_status = 'error';
                }
            } catch (e) {
                this.drawing_save_status = 'error';
            } finally {
                this.drawing_saving = false;
            }
        },
        createCompositeImage(bgPath, paperCanvas) {
            return new Promise((resolve) => {
                const offscreen = document.createElement('canvas');
                offscreen.width = paperCanvas.width;
                offscreen.height = paperCanvas.height;
                const ctx = offscreen.getContext('2d');

                const finish = () => {
                    ctx.drawImage(paperCanvas, 0, 0);
                    resolve(offscreen.toDataURL('image/jpeg', 0.9));
                };

                if (!bgPath) {
                    finish();
                    return;
                }

                const img = new Image();
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, offscreen.width, offscreen.height);
                    finish();
                };
                img.onerror = () => finish();
                img.src = bgPath;
            });
        },
    }
}
</script>
