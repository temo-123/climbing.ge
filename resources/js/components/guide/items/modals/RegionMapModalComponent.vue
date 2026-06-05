<template>
    <div class="region-map-modal-wrapper">
        <button class="btn btn-sm btn-map-open" @click="show = true">
            <i class="fa fa-map-marker"></i> {{ $t('guide.region.show_map') || 'Show Map' }}
        </button>

        <StackModal
            v-model="show"
            :title="region.name + ' — Map'"
            :size="'xxxl'"
            :save-button="false"
            @close="show = false"
        >
            <div class="region-map-content">
                <span v-html="region.map"></span>
            </div>
        </StackModal>
    </div>
</template>

<script>
    export default {
        name: 'RegionMapModalComponent',
        props: {
            region: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                show: false
            }
        }
    }
</script>

<style scoped>
    .region-map-modal-wrapper {
        display: inline-block;
    }

    .btn-map-open {
        background-color: #00b9e7;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 0.8rem;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .btn-map-open:hover {
        background-color: #009dc5;
        color: #fff;
    }

    .btn-map-open .fa {
        margin-right: 4px;
    }

    .region-map-content {
        width: 100%;
        min-height: 400px;
    }

    .region-map-content :deep(iframe),
    .region-map-content :deep(img) {
        width: 100%;
        height: 600px;
        border: none;
    }
</style>
