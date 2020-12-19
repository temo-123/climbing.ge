<template>
<div class="tabs">
    <input type="radio" name="tabs" id="1" checked="checked">
    <label for="1" v-if="this.table_1">{{this.table_1_name}}</label>
    <div class="tab" v-if="this.table_1">
        <div class="row">
            <div class="mb-3">
                <button @click="get_all_comments_data" class="btn main-btn " v-if="!table_1_is_refresh">Refresh ({{table_1_reset_id}})</button>
                <span class="badge badge-primare" v-if="table_1_is_refresh">Updating...</span>
            </div>
        </div>
        <div class="row">
            <ul class="list-group col-md-12">
                <li class="list-group-item" v-for="table_1_info in table_1" :key="table_1_info.id">
                    <div class="row">
                        <div class="col-xs-10 col-md-10">
                            <div>
                                <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{table_1_info.name}} {{table_1_info.surname}} -- ({{table_1_info.email}})</h3></a>
                            </div>
                            <div class="comment-text">
                                {{table_1_info.text}}
                            </div>
                            <div class="action">
                                <form method="post" @submit.prevent="del_comment(table_1_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group pull-right">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <input type="radio" name="tabs" id="2">
    <label for="2" v-if="this.table_2">{{this.table_2_name}}</label>
    <div class="tab" v-if="this.table_2">
        <div class="row">
            <div class="mb-3">
                <button @click="get_my_comments_data" class="btn main-btn " v-if="!table_2_is_refresh">Refresh ({{table_2_reset_id}})</button>
                <span class="badge badge-primare" v-if="table_2_is_refresh">Updating...</span>
            </div>
        </div>
        <div class="row">
            <ul class="list-group col-md-12">
                <li class="list-group-item" v-for="table_2_info in table_2" :key="table_2_info.id">
                    <div class="row">
                        <div class="col-xs-10 col-md-10">
                            <div>
                                <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{table_2_info.name}} {{table_2_info.surname}} -- ({{table_2_info.email}}) </h3></a>
                            </div>
                            <div class="comment-text">
                                {{table_2_info.text}}
                            </div>
                            <div class="action">
                                <button type="button" class="btn btn-primary btn-xs" title="Edit">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                                <form method="post" @submit.prevent="del_comment(table_1_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group pull-right">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            "table_1_name",
            "table_2_name",
        ],
        data(){
            return {
                table_1: [],
                table_2: [],

                table_1_is_refresh: false,
                table_2_is_refresh: false,

                table_1_reset_id: 0,
                table_2_reset_id: 0,
            }
        },
        mounted() {
            this.get_my_comments_data()
            this.get_all_comments_data()
        },
        methods: {
            get_all_comments_data: function(){
                this.table_1_is_refresh = true

                axios
                .get("/reviews_and_comments/get_all_comment_data/")
                .then(response => {
                    this.table_1 = response.data
                    this.table_1_is_refresh = false
                    this.table_1_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            
            get_my_comments_data: function(){
                this.table_2_is_refresh = true

                axios
                .get("/reviews_and_comments/get_my_comment_data/")
                .then(response => {
                    this.table_2 = response.data
                    this.table_2_is_refresh = false
                    this.table_2_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },

            del_comment(itemId) {
                axios
                .post('/reviews_and_comments/delete_comment/' + itemId, {
                    comment_id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_my_comments_data()
                    this.get_all_comments_data()
                })
                .catch(
                    error => console.log(error)
                )
            },
        }
    }
</script>

<style scoped>
/**
 * Tabs
 */
.tabs {
	display: flex;
	flex-wrap: wrap; /* make sure it wraps */
}
.tabs label {
/* .tabs  { */
	order: 1; /* Put the labels first */
	display: block;
	padding: 1rem 2rem;
	margin-right: 0.2rem;
	cursor: pointer;
  background: #ccced0;
  font-weight: bold;
  transition: background ease 0.2s;
}
.tabs .tab {
  order: 99; /* Put the tabs last */
  flex-grow: 1;
	width: 100%;
	display: none;
  padding: 1rem;
  background: #fff;
}
.tabs input[type="radio"] {
	display: none;
}
.tabs input[type="radio"]:checked + label {
	background: #fff;
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