<template>
    <div class="form-group clearfix row">
        <div class="col-md-12">
            <h4>Route Drawing Editor</h4>
            <p class="text-muted">Use the editor to create or modify the drawing for this route. You can select an existing sector image or upload a new one to use as a background for your drawing.</p>
        </div>
        
        <div class="form-group clearfix row" v-if="show_alert_modal">
          <div role="alert" class="alert alert-danger cursor_pointer">
            <div class="row">
              <div class="col-md-12">
                <p>This route doesn't have a drawing yet. You can create one by adding a new route or editing an existing one with drawing tools.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group clearfix row" v-if="data.sector_id != ''">
          <div class="col-md-12">
            <!-- Toggle Editor Button -->
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

                  <!-- Save Drawing button -->
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
                    ref="editorComponent"
                    :image_prop="getSectorImage()"
                    :json_prop="data.route_json"
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
</script>