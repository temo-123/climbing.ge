<template>
<div class="tabs">
    <input type="radio" name="tabs" id="1" checked="checked">
    <label for="1" v-if="this.table_1">{{this.table_1_name}}</label>
    <div class="tab" v-if="this.table_1">
        <div class="add_buttom">
            <a :href="table_1_add_url" class="btn btn-primary pull-left" type="submit">New </a>
        </div>
        <table class="table table-hover" id="dev-table">
            <thead>
                <tr>
                    <th style='text-align: center;'>
                        <input type="checkbox" class="all">
                        <!-- <label><input type="checkbox" class="all"></label> -->
                    </th>
                    <th>|</th>
                    <th>ID</th>
                    <th>|</th>
                    <th>Name</th>
                    <th v-if="table_1_name != 'Sector'">|</th>
                    <th style='text-align: center;' v-if="table_1_name != 'Sector'">Published</th>
                    
                    <th>|</th>
                    <th>Edit</th>
                    
                    <th>|</th>
                    <th>Delite</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr v-for="table_1_info in table_1" :key="table_1_info.id">
                    <td style='text-align: center;'>
                        <input type="checkbox">
                    </td>
                    <th>|</th>
                    <td>{{table_1_info.id}}</td>
                    <th>|</th>

                    <!-- <td>{{table_1_info.name}}</td> -->
                    <td v-if="table_1_name == 'Sector'">{{table_1_info.name}}</td>
                    <td v-else>{{table_1_info.url_title}} </td>

                    <th v-if="table_1_name != 'Sector'">|</th>
                    <td style='text-align: center;' v-if="table_1_name != 'Sector'">{{table_1_info.published}}</td>
                    <th>|</th>

                    <td>
                        <a href="#" class="btn btn-primary" type="submit">Edit</a>
                    </td>
                    
                    <th>|</th>
                    <td>
                        <form action="#" method="post" class="form-horisontal">
                            <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="radio" name="tabs" id="2">
    <label for="2" v-if="this.table_2">{{this.table_2_name}}</label>
    <div class="tab" v-if="this.table_2">
        <div class="add_buttom">
            <a :href="table_2_add_url" class="btn btn-primary pull-left" type="submit">New </a>
        </div>
         <table class="table table-hover" id="dev-table">
            <thead>
                <tr>
                    <th style='text-align: center;'>
                        <input type="checkbox" class="all">
                        <!-- <label><input type="checkbox" class="all"></label> -->
                    </th>
                    <th>|</th>
                    <th>ID</th>
                    <th>|</th>
                    <th>Name</th>

                    <th v-if="table_2_name != 'Route'">|</th>
                    <th style='text-align: center;' v-if="table_2_name != 'Route'">Published</th>
                    
                    <th>|</th>
                    <th>Edit</th>
                    
                    <th>|</th>
                    <th>Delite</th>
                    
                </tr>
            </thead>
            <tbody>
               <tr v-for="table_2_info in table_2" :key="table_2_info.id">
                    <td style='text-align: center;'>
                        <input type="checkbox">
                    </td>
                    <th>|</th>
                    <td>{{table_2_info.id}}</td>
                    <th>|</th>

                    <td v-if="table_2_name == 'Route'">{{table_2_info.name}}</td>
                    <td v-else>{{table_2_info.title}} </td>
                    <!-- <td>{{table_2_info.title}}</td> -->

                    <th v-if="table_2_name != 'Route'">|</th>
                    <td style='text-align: center;' v-if="table_2_name != 'Route'">{{table_2_info.published}}</td>
                    
                    <th>|</th>
                    <td>
                        <a  class="btn btn-primary" type="submit">Edit</a>
                    </td>
                    
                    <th>|</th>
                    <td>
                        <form action="#" method="post" class="form-horisontal">
                            <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <input type="radio" name="tabs" id="3">
    <label for="3"  v-if="this.table_3">{{this.table_3_name}}</label>
    <div class="tab" v-if="this.table_3">
        <div class="add_buttom">
            <a :href="table_3_add_url" class="btn btn-primary pull-left" type="submit">New </a>
        </div>
        <table class="table table-hover" id="dev-table">
            <thead>
                <tr>
                    <th style='text-align: center;'>
                        <input type="checkbox" class="all">
                        <!-- <label><input type="checkbox" class="all"></label> -->
                    </th>
                    <th>|</th>
                    <th>ID</th>
                    <th>|</th>
                    <th>Name</th>

                    <th v-if="table_3_name != 'Multi-pitch'">|</th>
                    <th style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">Published</th>
                    
                    <th>|</th>
                    <th>Edit</th>
                    
                    <th>|</th>
                    <th>Delite</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr v-for="table_3_info in table_3" :key="table_3_info.id">
                    <td style='text-align: center;'>
                        <input type="checkbox">
                    </td>
                    <th>|</th>
                    <td>{{table_3_info.id}}</td>
                    <th>|</th>

                    <!-- <td>{{table_3_info.title}}</td> -->
                    <td v-if="table_3_name == 'Multi-pitch'">{{table_3_info.name}}</td>
                    <td v-else>{{table_3_info.title}} /td>

                    <th v-if="table_3_name != 'Multi-pitch'">|</th>
                    <td style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">{{table_3_info.published}}</td>
                    
                    <th>|</th>
                    <td>
                        <a href="#" class="btn btn-primary" type="submit">Edit</a>
                    </td>
                    
                    <th>|</th>
                    <td>
                        <form action="#" method="post" class="form-horisontal">
                            <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="radio" name="tabs" id="4">
    <label for="4"  v-if="this.table_4">{{this.table_4_name}}</label>
    <div class="tab" v-if="this.table_4">
        <div class="add_buttom">
            <a :href="table_4_add_url" class="btn btn-primary pull-left" type="submit">New </a>
        </div>
        <table class="table table-hover" id="dev-table">
            <thead>
                <tr>
                    <th style='text-align: center;'>
                        <input type="checkbox" class="all">
                        <!-- <label><input type="checkbox" class="all"></label> -->
                    </th>
                    <th>|</th>
                    <th>ID</th>
                    <th>|</th>
                    <th>Name</th>

                    <th v-if="table_4_name != 'pitches'">|</th>
                    <th style='text-align: center;' v-if="table_4_name != 'pitches'">Published</th>
                    
                    <th>|</th>
                    <th>Edit</th>
                    
                    <th>|</th>
                    <th>Delite</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr v-for="table_4_info in table_4" :key="table_4_info.id">
                    <td style='text-align: center;'>
                        <input type="checkbox">
                    </td>
                    <th>|</th>
                    <td>{{table_4_info.id}}</td>
                    <th>|</th>

                    <!-- <td>{{table_4_info.title}}</td> -->
                    <td v-if="table_4_name == 'pitches'">{{table_4_info.name}}</td>
                    <td v-else>{{table_4_info.title}} </td>

                    <th v-if="table_4_name != 'pitches'">|</th>
                    <td style='text-align: center;' v-if="table_4_name != 'pitches'">{{table_4_info.published}}</td>
                    
                    <th>|</th>
                    <td>
                        <a href="#" class="btn btn-primary" type="submit">Edit</a>
                    </td>
                    
                    <th>|</th>
                    <td>
                        <form action="#" method="post" class="form-horisontal">
                            <button class="btn btn-danger b2" type="submit" id="51btn" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            "table_1",
            "table_2",
            "table_3",
            "table_4",

            "table_1_name",
            "table_2_name",
            "table_3_name",
            "table_4_name",

            "table_1_add_url",
            "table_2_add_url",
            "table_3_add_url",
            "table_4_add_url",
        ],
        data(){
            return {
                
            }
        },
        mounted() {
            //  console.log(this.table_1[0]);
        },
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