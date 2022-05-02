<template>
    <div class="tabs"> 
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" v-for="but_data in table_data" :key="but_data.id">
                        <input type="radio" :id="but_data.id" :value="but_data.id" v-model="tab_num">
                        
                        <label :for="but_data.id" >{{but_data.table_name}}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" v-for="filtr_data in table_data" :key="filtr_data.id" v-if="tab_num == filtr_data.id">
                        <div class="row">
                            <tableFilter :table_name="filtr_data.table_name" />
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <!-- <a :href="table_edit_url" class="btn btn-primary pull-left" type="submit">New</a> -->
                                    <!-- <a class="btn btn-primary" type="submit">New {{filtr_data.table_name}}</a> -->
                                    <!-- :href="table_1_add_url"  -->
                                    <!-- <router-link class="btn btn-primary pull-left" :to="'../article/add/'+filtr_data.table_name" exact>New {{filtr_data.table_name}}</router-link> -->

                                    <span v-if="filtr_data.table_category !== NULL">
                                        <router-link class="btn btn-primary pull-left" :to="{ name: filtr_data.table_add_url, params: { article_category: filtr_data.table_category } }">New {{filtr_data.table_name}}</router-link>
                                    </span>
                                    <span v-else>
                                        <span v-if="filtr_data.table_add_url">
                                            <router-link class="btn btn-primary pull-left" :to="{ name: filtr_data.table_add_url }">New {{filtr_data.table_name}}</router-link>
                                        </span>
                                    </span>
                                </div>

                                <div class="form-groupe float-right">
                                    <button class="btn main-btn" @click="update(filtr_data.id)">refresh</button>

                                    <!-- <button class="btn main-btn" >Refresh ({{2}})</button>
                                    <span class="badge badge-primare mb-1">Updating...</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover" id="dev-table"  v-for="data in table_data" :key="data.id" v-if="tab_num == data.id">
                            <thead>
                                <tr>
                                    <th style='text-align: center;'>
                                        <input type="checkbox" class="all">
                                    </th>
                                    <th>|</th>
                                    <th>ID</th>
                                    <th>|</th>
                                    <th v-if="data.table_name != 'Orders'">Name</th>

                                    <th v-if="data.table_name == 'Orders'">Status</th>

                                    <!-- <th v-if="data.table_name != 'Products'">|</th> -->

                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">Region</th>

                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">Price</th>

                                    <!-- <td v-if="data.table_name == 'Products' && data.table_name != 'Orders'">|</td> -->

                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">Quantity</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">|</th>

                                    <td                             v-if="data.table_name != 'Products' &&
                                                                            data.table_name != 'Routes' && 
                                                                            data.table_name != 'Multi-pitchs' && 
                                                                            data.table_name != 'Regions' && 
                                                                            data.table_name != 'Pitches' && 
                                                                            data.table_name != 'Users' &&
                                                                            data.table_name != 'Categories' && 
                                                                            data.table_name != 'Orders'">|</td>

                                    <th style='text-align: center;' v-if="  data.table_name != 'Routes' && 
                                                                            data.table_name != 'Multi-pitchs' && 
                                                                            data.table_name != 'Regions' && 
                                                                            data.table_name != 'Pitches' && 
                                                                            data.table_name != 'Users' &&
                                                                            data.table_name != 'Categories' && 
                                                                            data.table_name != 'Orders'">Public</th>

                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">email</th>
                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">Role</th>
                                    
                                    <td v-if="data.table_name == 'Products'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">Colors</th>

                                    <th                             v-if="data.table_name == 'Routes' || data.table_name == 'Pitches'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Routes' || data.table_name == 'Pitches'">Grade</th>

                                    <th v-if="data.table_name != 'Roles'" >|</th>
                                    <th v-if="data.table_name != 'Roles'" >Edit</th>
                                    
                                    <th v-if="data.table_name != 'Roles'" >|</th>
                                    <th v-if="data.table_name != 'Roles'" >Delite</th>
                                    
                                </tr>
                            </thead>
                            
                            <tbody v-if="data.table_name == 'Mount routes'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <!-- <td v-if="data.table_name == 'Sector'">|</td> -->
                                    <!-- <td v-if="data.table_name == 'Sector'"><button class="btn btn-primary" @click="show=true">+</button></td> -->
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Sector'" >
                                        <a @click="show_sector_model(table_info.id)" href="#">{{table_info.name}}</a>
                                        <!-- <button class="btn btn-info" @click="show_parmission_edit_madssssel(table_info.id)">{{table_info.name}}</button> -->
                                    </td>
                                    <td v-else-if="data.table_name == 'Users'">{{table_info.name}} {{table_info.surname}}</td>
                                    <td v-else>{{table_info.url_title}} </td>

                                    <td  v-if="data.table_name != 'Users' || data.table_name != 'Orders'">|</td>

                                    <th style='text-align: center;' v-if="data.table_name == 'Sector'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th>

                                    <td style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">{{table_info.category_id}}</td>
                                    <td v-if="data.table_name == 'Products'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Sector' && data.table_name != 'Users'">{{table_info.published}}</td>

                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">
                                        {{ get_user_role(table_info.id) }}
                                        {{ user_role }}
                                    </th>
                                    
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Users'">
                                        <button class="btn btn-primary" @click="show_parmission_edit_madel(table_info.id)">Edit roles</button>
                                    </td>
                                    <td v-else>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_1_del(table_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>

                            <tbody v-else-if="data.table_name == 'Products'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_info.url_title}} </td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_info.price}} {{table_info.currency}}</td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_info.quantity}}</td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_info.published}}</td>
                                    <td>|</td>
                                    <td>
                                        <a href="#" class="btn btn-warning" type="submit">Colors</a>
                                    </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_products(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Regions'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_info.region_name_us}} </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_products(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Categories'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_info.us_name}} </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_categories(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Sectors'" >
                                        <a @click="show_sector_model(table_info.id)" href="#">{{table_info.name}}</a>
                                    </td>
                                    <td>|</td>

                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <td>|</td>

                                    <td>{{table_info.published}}</td>
                                    <td>|</td>

                                    <td >
                                        <a :to="{ name: 'routeedit', params: { id: table_info.id } }" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_sector(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name === 'Routes'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Routes'">{{table_info.name}}</td>
                                    <td v-else-if="data.table_name == 'Categories'">{{table_info.us_name}}</td>
                                    <td v-else>{{table_info.name}} </td>


                                    <td v-if="data.table_name == 'Routes'">|</td>
                                    <td v-if="data.table_name == 'Routes'">
                                                                    <div v-if="table_info.or_grade != NULL">{{table_info.grade}} / {{ table_info.or_grade }}</div>
                                                                    <div v-else>{{table_info.grade}}</div>
                                    </td>

                                    <td>|</td>
                                    <td v-if="table_1_name != 'Users'">
                                        <a :href="table_2_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td v-if="table_1_name != 'Users'">|</td>
                                    <td v-if="table_1_name != 'Users'">
                                        
                                        <button type="submit" class="btn btn-danger" @click="del_route(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Multi-pitchs'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <!-- <td v-if="data.table_name == 'Multi-pitch'">{{table_info.name}}</td> -->
                                    <td v-if="data.table_name == 'Multi-pitchs'">
                                        <a @click="show_mtp_model(table_info.id)" href="#">{{table_info.name}}</a>
                                    </td>
                                    <td v-else>{{table_info.title}} </td>

                                    <td v-if="data.table_name != 'Multi-pitchs'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Multi-pitchs'">{{table_info.published}}</td>
                                    
                                    <td>|</td>
                                    <td>
                                        <a :href="table_3_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_multi_pitch(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Pitches'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>

                                    <td>|</td>

                                    <td v-if="data.table_name == 'Pitches'">
                                        {{table_info.name}}
                                    </td>

                                    <td v-else>{{table_info.title}} </td>

                                    <td v-if="data.table_name != 'Pitches'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Pitches'">{{table_info.published}}</td>
                                    
                                    <td>|</td>
                                    <td v-if="data.table_name == 'Pitches'">
                                                                    <div v-if="table_info.or_grade != NULL">{{table_info.grade}} / {{ table_info.or_grade }}</div>
                                                                    <div v-else>{{table_info.grade}}</div>
                                    </td>

                                    <td>|</td>
                                    <td>
                                        <a :href="table_4_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_pitch(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Users'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <td>{{table_info.name}} {{table_info.surname}}</td>

                                    <td>|</td>

                                    <td>{{table_info.email}}</td>
                                    <td>|</td>

                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">
                                        <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_info.id'>
                                            <div v-if="data.table_name == 'Roles'">
                                                <div v-for="table_info in table_2" :key="table_info.id" v-if='user_role.role_id == table_info.id'>
                                                    {{ table_info.name }}
                                                </div>
                                            </div>
                                            <div v-else>
                                                {{ user_role.role_id }}
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <td>|</td>

                                    <td>
                                        <button class="btn btn-primary" @click="show_parmission_edit_madel(table_info.id)">Edit roles</button>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Roles'">
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <td>{{table_info.name}}</td>

                                    <td>|</td>
                                    
                                    <td>{{table_info.description}}</td>
                                </tr>
                            </tbody>

                            <tbody v-else>
                                <tr v-for="table_info in data.data" :key="table_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>

                                    <!-- <div v-if="data.table_name != 'Orders'"> -->
                                        <!-- <td v-if="data.table_name == 'Sector'" > -->
                                        <!-- <a v-if="data.table_name != 'Orders' && data.table_name == 'Sector'" @click="show_sector_model(table_info.id)" href="#">{{table_info.name}}</a>
                                        </td>
                                        <td v-else-if="data.table_name != 'Orders' && data.table_name == 'Users'">{{table_info.name}} {{table_info.surname}}</td> -->
                                        <!-- <td v-if="data.table_name == 'Users'">{{table_info.name}}</td> -->
                                        <td>{{table_info.url_title}} <!-- <a @click="s" ><i class="fa fa-pencil" aria-hidden="true"></i></a> --></td>
                                    <!-- </div> -->

                                    <td  v-if="data.table_name != 'Users' && data.table_name != 'Orders'">|</td>

                                    <!-- <th style='text-align: center;' v-if="data.table_name == 'Sector'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th> -->

                                    <!-- <td style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">{{table_info.category_id}}</td>
                                    <td v-if="data.table_name == 'Products'">|</td> -->
                                    <td style='text-align: center;' v-if="data.table_name != 'Sector' && data.table_name != 'Users' && data.table_name != 'Orders'">{{table_info.published}}</td>
                                    <!-- <td v-if="data.table_name == 'Orders'">{{table_info.status}}</td>
                                    <td v-if="data.table_name == 'Users'">|</td> -->

                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">
                                        <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_info.id'>
                                            <div v-if="data.table_name == 'Roles'">
                                                <div v-for="table_info in table_2" :key="table_info.id" v-if='user_role.role_id == table_info.id'>
                                                    {{ table_info.name }}
                                                </div>
                                            </div>
                                            <div v-else>
                                                {{ user_role.role_id }}
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Users'">
                                        <button class="btn btn-primary" @click="show_parmission_edit_madel(table_info.id)">Edit roles</button>
                                    </td>
                                    <td v-if="data.table_name == 'Orders'">
                                        <button class="btn btn-primary" @click="show_order_status_edit_madel(table_info.id)">Edit Status</button>
                                    </td>
                                    <td v-else>
                                        <router-link class="btn btn-primary" :to="{ name: 'articleEdit', params: { id: table_info.id } }">Edit</router-link>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <stack-modal
            :show="show_sector_modal"
            title="Sector"
            @close="show_sector_modal=false"
            :modal-class="{ [SectorModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        <!-- <editor /> -->
                        <div class="row">
                            <img v-for="sector_image in sector_images" :key="sector_image.id" :src="'/public/images/sector_img/'+sector_image.image" alt="image" :style="'width:' + sector_images_size + '%'">
                        </div>
                        <div class="row">
                            <h2 v-if="sector_routes != ''">Sport climbing routes</h2>
                            <SlickList lockAxis="y" v-model="sector_routes" v-if="sector_routes != ''" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                    <td>Grade</td>
                                    <td>Height</td>
                                    <td>Bolts</td>
                                    <td>Bolter</td>
                                    <td>First ascent</td>
                                    <td>Edit</td>
                                </tr>
                                <SlickItem v-for="(route, index) in sector_routes" :index='index' :key="index" tag="tr">
                                    <td>{{ route.id }}</td>
                                    <td>{{ route.num }}</td>
                                    <td>{{ route.name }}</td>
                                    <!-- <td>{{ route.grade }}</td> -->
                                    <td v-if="route.or_grade != NULL">{{route.grade}} / {{ route.or_grade }}</td>
                                    <td v-if="route.or_grade == NULL">{{route.grade}}</td>
                                    <td>{{ route.height }}</td>
                                    <td>{{ route.bolts }}</td>
                                    <td>{{ route.bolter }}</td>
                                    <td>{{ route.first_ascent }}</td>
                                    <td><a :href="table_2_edit_url+route.id" class="btn btn-primary" type="submit">Edit</a></td>
                                </SlickItem>
                            </SlickList>

                            <h2 v-if="sector_mtps != ''">Mupli pitchs</h2>

                            <SlickList lockAxis="y" v-model="sector_mtps" v-if="sector_mtps != ''" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                </tr>
                                <SlickItem v-for="(mtp, index) in sector_mtps" :index='index' :key="index" tag="tr">
                                    <td>{{ mtp.id }}</td>
                                    <td>{{ mtp.num }}</td>
                                    <td>{{ mtp.name }}</td>
                                </SlickItem>
                            </SlickList>
                        </div>
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="save_routes_sequence()"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
            :show="show_mtp_modal"
            title="Multy-pitch"
            @close="show_mtp_modal=false"
            :modal-class="{ [SectorModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <SlickList lockAxis="y" v-model="sector_mtp_pitchs_for_modal" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                    <td>Grade</td>
                                    <td>Height</td>
                                    <td>Bolts</td>
                                    <td>Bolter</td>
                                    <td>First ascent</td>
                                </tr>
                                <SlickItem v-for="(pitch, index) in sector_mtp_pitchs_for_modal" :index='index' :key="index" tag="tr">
                                    <td>{{ pitch.id }}</td>
                                    <td>{{ pitch.num }}</td>
                                    <td>{{ pitch.name }}</td>
                                    <td>{{ pitch.grade }}</td>
                                    <td>{{ pitch.height }}</td>
                                    <td>{{ pitch.bolts }}</td>
                                    <td>{{ pitch.bolter }}</td>
                                    <td>{{ pitch.first_ascent }}</td>
                                </SlickItem>
                            </SlickList>
                        </div>
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="save_pitchs_sequence()"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="roles_modal"
                title="Edit roles"
                @close="roles_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form>
                    <select class="form-control" v-model="user_new_parmission"> 
                        <option value="user">User</option> 
                        <option value="ru_menager">English contrnt menager</option> 
                        <option value="ru_menager">Russian contrnt menager</option> 
                        <option value="ka_menager">Georgian contrnt menager</option> 
                        <option value="manager">Content manager</option>
                        <option value="seller">Seller</option>  
                        <option value="admin">Admin</option> 
                    </select>
                    <!-- <div class="alert alert-danger" role="alert" v-if="is_parmision_error"> -->
                    <p class="alert alert-danger" role="alert" v-if="is_parmision_error">
                        {{ parmision_error.parmission[0] }}
                    </p>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="edit_order_modal"
                title="Edit order status"
                @close="edit_order_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="container">
                    <div class="clearfix row">
                        <p>
                            Order ID - {{  modal_order.id }}
                            Shiping data - {{  modal_order.order_data }}
                            Status - {{ modal_order.status }}
                            User Id - {{  modal_order.user_id }}
                        </p>
                    </div>
                    <hr>
                    <div class="clearfix row">
                        <p>
                            Adres ID -
                            Address N1 - 
                            Address N2 - 
                            ZIP - 
                            Quntry - 
                            City -
                        </p>
                    </div>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>test</td>
                                <td>5</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"> </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">25</th>
                            </tr>
                        </tfoot>
                    </table>
                    <form ref="myForm">
                            <div class="clearfix row">
                                <div class="col-md-12 image_add_modal_form">
                                    <select class="form-control" name="status" id="status">
                                            <option value="1">Order Processed</option> 
                                            <option value="2">Order Shipped</option>
                                            <option value="3">Order En Route</option>
                                            <option value="4">Order Arrived</option>
                                    </select> 
                                </div>
                        </div>
                    </form>
                    <form >
                        <div class="clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="email" class="form-control" v-model="email" name="email" id="email" placeholder="email" title="enter your email.">
                            </div>
                        </div>
                        <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                        Send invois to mail
                        </button>
                    </form>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="order_detals_modal"
                title="Order status"
                @close="order_detals_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre>
                <div class="container">
                    <p>
                        Order ID - #
                        Status - 
                        Shiping data - 
                    </p>
                    <hr>
                    <p>
                        Address N1 - 
                        Address N2 - 
                        ZIP - 
                        Quntry - 
                        City -
                    </p>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>test</td>
                                <td>5</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"> </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">25</th>
                            </tr>
                        </tfoot>
                    </table>
                    <p>
                        Delivery asushistvitsa in the next 7-10 days.
                        Delivery times - 
                    </p>
                </div>
            </pre>
            <div slot="modal-footer">
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import editor from '../../../items/canvas/EditorComponent.vue'

    import tableFilter from '../../filters/FiltersComponent.vue'

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,

            tableFilter,

            editor,
        },

        props: [
            'table_data',
        ],

        data(){
            return {
                tab_num: 1,
                picked: '',
                // id: 0,
                // num: 1,


                modal_order: "",
                show_sector_modal: false,
                show_mtp_modal: false,
                order_detals_modal: false,
                roles_modal: false,
                edit_order_modal: false,
                SectorModalClass: 'modal-xxxl',
                modalClass: '',

                
            }
        },

        mounted() {
            this.tab_num = 1
            // this.num = this.table_data.length
            // alert(this.table_data.length)
            if (this.table_1_name == 'Sector') {
                this.get_sectors_data();
                this.get_region_data();
                this.get_routes_data();
                this.get_MTP_data()
                this.get_MTP_pitch_data()
            }
            if (this.table_1_name == 'Products') {
                this.get_product_category_data();
                this.get_product_data();
            }
            if (this.table_1_name == 'Mount routes') {
                this.get_mount_route_data();
                this.get_mount_data();
            }
            if(this.table_1_name == 'Users'){
                this.get_user_role()
            }
        },

        methods: {
            update(id){
                this.$emit('update-data', [id])
            },
            del_article(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/article/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },

            del_sector(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/sector/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
            del_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/route/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
            del_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/MTPPitch/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
            del_multi_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/MTP/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },

            show_parmission_edit_madel(user_id){
                this.roles_modal=true;
                this.user_id_for_rditing_parmission = user_id
            },
            edit_permission(id) {
                axios
                .post('users/edit_user_permission/' + id, {
                    parmission: this.user_new_parmission,
                })
                .then((response)=> { 
                    this.roles_modal = false
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.parmision_error = error.response.data.errors
                    }
                    this.is_parmision_error = true
                })
            },
            get_user_role: function(user_id){
                axios
                .get('users/get_role/', {

                })
                .then(Response => {
                    console.log(Response.data);
                    this.user_roles = Response.data
                })
                .catch(error => {
                    // this.user_role = "error"
                })
            },



            show_order_status_edit_madel(order_id){
                this.edit_order_modal = true
                this.get_orders_data(order_id)
            },
            show_order_detals_madel(order_id){
                this.order_detals_modal = true
                this.get_orders_data(order_id)
            },

            show_sector_model(sector_id){
                this.show_sector_modal=true

                // if (this.show_sector_modal==true) {
                //     axios
                //     .get('/routes_and_sectors/get_routes_for_model/'+ sector_id)
                //     .then(response => {
                //         this.sector_routes = response.data
                //     })
                //     .catch(
                //         error => console.log(error)
                //     );

                //     axios
                //     .get('/routes_and_sectors/get_sector_image/'+ sector_id)
                //     .then(response => {
                //         this.sector_images = response.data.sector_images
                //         this.sector_images_size = response.data.sector_images_size
                //     })
                //     .catch(
                //         error => console.log(error)
                //     );

                //     axios
                //     .get('/routes_and_sectors/get_mtp_for_model/'+ sector_id)
                //     .then(response => {
                //         this.sector_mtps = response.data
                //     })
                //     .catch(
                //         error => console.log(error)
                //     );
                // }
                // else{
                //     this.sector_routes = ""
                //     this.sector_images = ""
                //     this.sector_mtp = ""
                // }
            },
            show_mtp_model(mtp_id){
                this.show_mtp_modal=true

                if (this.show_mtp_modal==true) {
                    axios
                    .get('/routes_and_sectors/get_mtp_pitchs_for_model/'+ mtp_id)
                    .then(response => {
                        this.sector_mtp_pitchs_for_modal = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_mtp_pitchs_for_modal = ""
                }
            },



            save_pitchs_sequence(){
                axios
                .post('../routes_and_sectors/pitchs_sequence/', {
                    pitchs_sequence: this.sector_mtp_pitchs_for_modal,
                })
                .then((response)=> { 
                    this.show_mtp_modal = false
                })
                .catch(error =>{
                })
            },
            save_routes_sequence(){
                axios
                .post('../routes_and_sectors/routes_sequence/', {
                    routes_sequence: this.sector_routes,
                    mtp_sequence: this.sector_mtps,
                })
                .then((response)=> { 
                    this.show_sector_modal = false
                })
                .catch(error =>{
                })
            },
        }
    }
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