<template>
    <div class="width_100">
        <hr>
        <div class="col-md-12">
            <div class="row">
                <h2>Add new gallery images</h2>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_image_value()" >Add new imagee</button>
                        <button class="btn btn-warning float-left ml-2" @click="sortImages('name')" v-if="new_images.length > 1">Sort by Name</button>
                        <button class="btn btn-warning float-left ml-2" @click="sortImages('size')" v-if="new_images.length > 1">Sort by Size</button>
                        <button class="btn btn-danger float-left ml-2" @click="bulkDelete" v-if="selectedImages.length > 0">Delete Selected ({{ selectedImages.length }})</button>
                    </div>
                </div>
            </div>

            <div class="row width_100">
                <div class="col-md-12">
                    <table class="table table-hover " id="dev-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" @change="selectAllImages" v-model="selectAll"></th>
                                <th>Image</th>
                                <th>Size</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody @drop="handleDrop" @dragover.prevent>
                            <tr v-for="(image, index) in new_images" :key="image.id"
                                draggable="true"
                                @dragstart="onDragStart($event, index)"
                                @dragend="onDragEnd"
                                @dragover="onDragOver($event, index)">
                                <td>
                                    <input type="checkbox"
                                           :checked="selectedImages.includes(image.id)"
                                           @change="toggleImageSelection(image.id)">
                                </td>
                                <td>
                                    <div v-if="image.image">
                                        <img :src="getObjectURL(image.image)"
                                             style="max-height: 100px; cursor: pointer;"
                                             @click="zoomImage(image)" />
                                        <br>
                                        <small>{{ image.image.name }}</small>
                                    </div>
                                    <form v-else ref="myForm" @drop="handleDropOnForm($event, image.id)" @dragover.prevent @dragenter="onDragEnter" @dragleave="onDragLeave" class="border border-secondary rounded p-3 text-center bg-light">
                                        <input type="file" name="image" id="image" v-on:change="onFileChange($event, image.id)">
                                        <div class="text-muted small">Drop image here or click to select</div>
                                    </form>
                                </td>
                                <td>
                                    <small v-if="image.image">{{ formatFileSize(image.image.size) }}</small>
                                    <small v-else>N/A</small>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" @click="del_image(image.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_image_value()">Add new imagee</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    // import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            // editor_config
        ],
        props: [
            // 'old_images_prop',
            'image_path_prop',
        ],
        data(){
            return {
                new_images: [],
                old_images: [],
                selectedImages: [],
                selectAll: false,
                draggedIndex: null,
                zoomedImage: null,
            }
        },
        mounted() {
            // this.old_images = old_images_prop
        },
        methods: {
            getObjectURL(file) {
                return window.URL.createObjectURL(file);
            },
            onFileChange(event, item_id){
                let image = event.target.files[0]

                if (!this.validateFile(image)) {
                    return;
                }

                let id = item_id - 1
                this.new_images[id]['image'] = image
                this.new_images[id]['caption'] = this.new_images[id]['caption'] || ''

                this.update_perent_component_data()
            },
            handleDrop(event) {
                event.preventDefault();
                const files = Array.from(event.dataTransfer.files);
                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        var new_item_id = this.new_images.length + 1;
                        this.new_images.push({
                            id: new_item_id,
                            image: file,
                        });
                    }
                });
                this.update_perent_component_data();
            },
            handleDropOnForm(event, item_id) {
                event.preventDefault();
                event.stopPropagation();
                const files = Array.from(event.dataTransfer.files);
                files.forEach(file => {
                    if (this.validateFile(file)) {
                        var new_item_id = this.new_images.length + 1;
                        this.new_images.push({
                            id: new_item_id,
                            image: file,
                            caption: '',
                        });
                    }
                });
                this.update_perent_component_data();
            },
            onDragEnter(event) {
                event.currentTarget.classList.remove('border-secondary', 'bg-light');
                event.currentTarget.classList.add('border-primary', 'bg-primary', 'text-white');
            },
            onDragLeave(event) {
                event.currentTarget.classList.remove('border-primary', 'bg-primary', 'text-white');
                event.currentTarget.classList.add('border-secondary', 'bg-light');
            },
            add_new_image_value(){
                var new_item_id = this.new_images.length+1

                this.new_images.push(
                    {
                        id: new_item_id,
                        image: '',
                        caption: '',
                    }
                );
            },
            del_image(id){
                const objWithIdIndex = this.new_images.findIndex((obj) => obj.id === id);
                this.new_images.splice(objWithIdIndex, 1);

                this.update_perent_component_data()
            },
            update_perent_component_data(){
                this.$emit("update_gallery_images", this.new_images);
            },
            validateFile(file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Please select a JPEG, PNG, GIF, or WebP image.');
                    return false;
                }

                if (file.size > maxSize) {
                    alert('File size too large. Please select an image smaller than 5MB.');
                    return false;
                }

                return true;
            },
            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },
            toggleImageSelection(imageId) {
                const index = this.selectedImages.indexOf(imageId);
                if (index > -1) {
                    this.selectedImages.splice(index, 1);
                } else {
                    this.selectedImages.push(imageId);
                }
                this.updateSelectAllState();
            },
            selectAllImages() {
                if (this.selectAll) {
                    this.selectedImages = this.new_images.map(img => img.id);
                } else {
                    this.selectedImages = [];
                }
            },
            updateSelectAllState() {
                this.selectAll = this.selectedImages.length === this.new_images.length && this.new_images.length > 0;
            },
            bulkDelete() {
                if (confirm(`Are you sure you want to delete ${this.selectedImages.length} selected images?`)) {
                    this.new_images = this.new_images.filter(img => !this.selectedImages.includes(img.id));
                    this.selectedImages = [];
                    this.selectAll = false;
                    this.update_perent_component_data();
                }
            },
            sortImages(criteria) {
                this.new_images.sort((a, b) => {
                    if (!a.image || !b.image) return 0;

                    switch (criteria) {
                        case 'name':
                            return a.image.name.localeCompare(b.image.name);
                        case 'size':
                            return a.image.size - b.image.size;
                        default:
                            return 0;
                    }
                });
                this.update_perent_component_data();
            },
            onDragStart(event, index) {
                this.draggedIndex = index;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/html', event.target.outerHTML);
                event.target.style.opacity = '0.5';
            },
            onDragEnd(event) {
                event.target.style.opacity = '1';
                this.draggedIndex = null;
            },
            onDragOver(event, index) {
                event.preventDefault();
                if (this.draggedIndex === null || this.draggedIndex === index) return;

                const draggedItem = this.new_images[this.draggedIndex];
                this.new_images.splice(this.draggedIndex, 1);
                this.new_images.splice(index, 0, draggedItem);
                this.draggedIndex = index;
                this.update_perent_component_data();
            },
            zoomImage(image) {
                this.zoomedImage = image;
            },
            closeZoom() {
                this.zoomedImage = null;
            }
        }
    }
</script>

<style scoped>
.drop-zone {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
    position: relative;
}

.drop-zone.drag-over {
    border-color: #007bff;
    background-color: #e3f2fd;
    transform: scale(1.02);
}

.drop-message {
    color: #666;
    font-size: 14px;
    margin-top: 10px;
}

.drop-zone.drag-over .drop-message {
    color: #007bff;
    font-weight: bold;
}
</style>
