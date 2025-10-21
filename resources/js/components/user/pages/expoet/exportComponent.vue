<template>
    <div>
        <div class="row">
            <!-- <div class="col-sm-3"> -->
                <left-menu />
            <!-- </div> -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <breadcrumb />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button
                                @click="openExportModal"
                                class="btn btn-primary btn-lg"
                            >
                                Export Articles to PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <export_articles_modal
            :showModal="showExportModal"
            @close="closeExportModal"
            ref="exportModal"
        />
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import export_articles_modal from '../../items/modal/ExportArticlesModalComponent.vue'

export default {
    components: {
        breadcrumb,
        export_articles_modal,
    },
    data() {
        return {
            showExportModal: false,
        }
    },
    methods: {
        openExportModal() {
            this.showExportModal = true
        },
        closeExportModal() {
            this.showExportModal = false
        },
    }
}
</script>

<style scoped>
.text-center {
    margin-top: 50px;
}
</style>
