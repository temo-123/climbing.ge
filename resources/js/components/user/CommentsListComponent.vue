<template>
<div class="tabs">
    <input type="radio" name="tabs" id="1" checked="checked">
    <label for="1" v-if="this.table_1">{{this.table_1_name}}</label>
    <div class="tab" v-if="this.table_1">
        <ul class="list-group">
            <li class="list-group-item" v-for="table_1_info in table_1" :key="table_1_info.id">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <div>
                            <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{table_1_info.name}} {{table_1_info.surname}} </h3></a>
                        </div>
                        <div class="comment-text">
                            {{table_1_info.text}}
                        </div>
                        <div class="action">
                            <!-- <button type="button" class="btn btn-primary btn-xs" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button> -->
                            <!-- <button type="button" class="btn btn-danger btn-xs" title="Delete">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button> -->
                            <form action="#" method="post" class="form-horisontal">
                                <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <input type="radio" name="tabs" id="2">
    <label for="2" v-if="this.table_2">{{this.table_2_name}}</label>
    <div class="tab" v-if="this.table_2">
        <ul class="list-group">
            <li class="list-group-item" v-for="table_2_info in table_2" :key="table_2_info.id">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <div>
                            <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{table_2_info.name}} {{table_2_info.surname}} </h3></a>
                        </div>
                        <div class="comment-text">
                            {{table_2_info.text}}
                        </div>
                        <div class="action">
                            <button type="button" class="btn btn-primary btn-xs" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" title="Delete">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            <form action="#" method="post" class="form-horisontal">
                                <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            "table_1",
            "table_2",

            "table_1_name",
            "table_2_name",
        ],
        data(){
            return {
                
            }
        },
        mounted() {
            //  console.log(this.addRoute);
        },
        methods: {
            table_1_del(itemId) {
                axios
                .post('/articles/del/' + itemId, {
                    global_id: itemId,
                })
                .then(Response => {
                    console.log(response)
                })
                .catch(error => console.log(error))
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