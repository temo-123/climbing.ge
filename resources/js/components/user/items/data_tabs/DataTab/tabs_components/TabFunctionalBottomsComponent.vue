<template>
    <span>
    <!-- <div
        class="col-md-12"
        v-for="filtr_data in table_data"
        :key="filtr_data.id"
        v-if="tab_num == filtr_data.id"
    >
        <div class="row">
            <div class="col-md-12">
                <tableFilter
                    :table_name="filtr_data.table_name"
                    @filtred="filtr"
                />
            </div>
        </div>

        <div
            class="row"
            v-if="
                filtr_data.table_name != 'Regions' &&
                filtr_data.table_name != 'Sectors' &&
                filtr_data.table_name != 'Routes' &&
                filtr_data.table_name != 'Multi-pitchs' &&
                filtr_data.table_name != 'Pitches' &&
                filtr_data.table_name != 'Images category' &&
                filtr_data.table_name != 'Orders' &&
                filtr_data.table_name != 'comments' &&
                filtr_data.table_name != 'Posts' &&
                filtr_data.table_name != 'Topics' &&
                filtr_data.table_name != 'Users' &&
                filtr_data.table_name != 'Folowing users' &&
                filtr_data.table_name != 'my comments' &&
                filtr_data.table_name != 'My Posts' &&
                filtr_data.table_name != 'Roles' &&
                filtr_data.table_name != 'Categories' &&
                filtr_data.table_name != 'Parmissions' &&
                filtr_data.table_name != 'Mounts'
            "
        >
            <div class="col-md-12">
                <button class="btn btn-primary pull-left">
                    Go to {{ filtr_data.table_name }} page
                </button>
            </div>
        </div>

        <div class="row"><hr /></div>

        <div
            class="row"
            v-if="filtr_data.table_name != 'Parmissions'"
        >
            <div class="col-md-12">
                <div class="add_buttom float-left">

                    <span
                        v-if="
                            filtr_data.table_category !== null
                        "
                    >
                        <router-link
                            class="btn btn-primary pull-left"
                            :to="{
                                name: filtr_data.table_add_url,
                                params: {
                                    article_category:
                                        filtr_data.table_category,
                                },
                            }"
                            >New
                            {{ filtr_data.name }}</router-link
                        >
                    </span>
                    <span v-else>
                        <span v-if="filtr_data.table_add_url">
                            <router-link
                                class="btn btn-primary pull-left"
                                :to="{
                                    name: filtr_data.table_add_url,
                                }"
                                >New
                                {{
                                    filtr_data.name
                                }}</router-link
                            >
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
        </div>
        <div
            class="row"
            v-if="filtr_data.table_name != 'Parmissions'"
        >
            <div class="col-md-12">
                <div class="form-groupe float-right">
                    <button class="btn btn-danger" disabled>
                        Del selected items
                    </button>
                </div>
            </div>
        </div>
    </div> -->
    </span>
</template>

<script>
    export default {
        props: [
            "data"
        ],
    }
</script>

<style>

</style>