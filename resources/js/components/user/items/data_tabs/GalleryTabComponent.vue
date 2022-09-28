<template>
    <!-- <div class="panel panel-default target">
                <div class="panel-body"> -->
    <div class="tabs">
        <div class="col-md-12">
            <div class="row">
                <div class="col">
                    <input type="radio" id="1" :value="1" v-model="tab_num" />

                    <label for="1">Gallery images</label>
                </div>
                <div class="col">
                    <input type="radio" id="2" :value="2" v-model="tab_num" />

                    <label for="2">Images category</label>
                </div>
            </div>
        </div>

        <div class="row" v-if="tab_num == 1">
            <div class="col-md-12">
                <!-- <div class="row"> -->
                <galleryComponent />
                <!-- </div> -->
            </div>
        </div>

        <div class="row" v-if="tab_num == 2">
            <div class="col-md-12">
                <div class="row">
                    <div class="add_buttom float-left">
                        <span>
                            <span>
                                <button class="btn btn-primary pull-left">
                                    New image category
                                </button>
                            </span>
                        </span>
                    </div>

                    <div class="form-groupe float-right">
                        <button
                            class="btn btn-success"
                            @click="update(filtr_data.id)"
                        >
                            refresh
                        </button>
                    </div>
                </div>

                <div class="row">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th style="text-align: center">
                                    <input type="checkbox" class="all" />
                                </th>
                                <th>|</th>
                                <th>ID</th>

                                <th>|</th>
                                <th>Name</th>

                                <th>|</th>
                                <th>Edit</th>

                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="table_info in categories"
                                :key="table_info.id"
                            >
                                <td style="text-align: center">
                                    <input type="checkbox" />
                                </td>
                                <td>|</td>
                                <td>{{ table_info.id }}</td>

                                <td>|</td>
                                <td>{{ table_info.id }}</td>

                                <td>|</td>
                                <td>edit</td>

                                <td>|</td>
                                <td>del</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- </div>
            </div> -->
</template>

<script>
import galleryComponent from "../GalleryComponent.vue";
export default {
    data() {
        return {
            categories: [],
            tab_num: 1,
        };
    },
    components: {
        galleryComponent,
    },
    mounted() {
        this.get_images_categories();
    },
    watch: {
        $route(to, from) {
            (this.categories = []), this.get_images_categories();
            window.scrollTo(0, 0);
        },
    },
    methods: {
        get_images_categories() {
            axios
                .get("../api/gallery_image_category/")
                .then((response) => {
                    this.categories = response.data;
                })
                .catch((error) => console.log(error));
        },
    },
};
</script>

<style scoped>
.tabs {
    /* display: flex; */
    flex-wrap: wrap;
}
.tabs label {
    order: 1;
    display: block;
    padding: 1rem 2rem;
    margin-right: 0.2rem;
    cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
}

.tabs input[type="radio"]:checked + label[data-v-5add7a37] {
    background: #fff;
    border: 1px solid #ccc !important;
}
.tabs .tab {
    order: 99;
    flex-grow: 1;
    width: 100%;
    display: none;
    padding: 1rem;
    background: #fff;
    /* border: 1px solid #ccc!important; */
}
.tabs input[type="radio"] {
    display: none;
}
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
.tabs input[type="radio"]:checked + label + .tab {
    display: block;
}

@media (max-width: 45em) {
    .tabs .tab,
    .tabs label {
        order: initial;
    }
    .tabs label {
        width: 100%;
        margin-right: 0;
        margin-top: 0.2rem;
    }
}
</style>
