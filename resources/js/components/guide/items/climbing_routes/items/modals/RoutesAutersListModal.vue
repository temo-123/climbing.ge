<template>
    <span>
        <!-- {{ $t('guide.sector.authers_list') }} <a @click="show_modal"> {{ $t('guide.sector.authers_list_button') }}</a> -->
        <div>
            <button type="bottom" class="btn btn-default btn-send main-btn" @click="show_modal">{{ $t('guide.sector.authers_list_button') }}</button>
        </div>

        <stack-modal
            :show="is_show_modal"
            :title="$t('guide.sector.routes_authers_list_title')"
            @close="is_show_modal=false"
            :saveButton="{ visible: false, title: $t('global.form.save'), btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: $t('guide.route.close_modal'), btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <div v-if="Object.keys(authers).length === 0" class="text-center p-4">
                    <h2>{{ $t('guide.sector.no_authors_found') }}</h2>
                </div> <div v-else>

                    <input type="text" v-model="filterAuthor" :placeholder="$t('guide.sector.filter_by_author_name')" class="form-control ">
                    <select v-model="sortBy" class="form-control ">
                        <option value="total">{{ $t('guide.sector.sort_by_total_routes') }}</option>
                        <option value="author">{{ $t('guide.sector.sort_by_author_name') }}</option>
                        <option v-for="category in route_categories" :key="category.value" :value="category.value">
                            {{ $t('guide.sector.sort_by') }} {{ $t(category.label) }}
                        </option>
                    </select>
                    <select v-model="sortOrder" class="form-control ">
                        <option value="desc">{{ $t('guide.sector.sort_descending') }}</option>
                        <option value="asc">{{ $t('guide.sector.sort_ascending') }}</option>
                    </select>

                    <div class="table-responsive">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>{{ $t('guide.sector.auther_name') }}</th>
                                <!-- <th>{{ $t('guide.sector.auther_routes_quantity') }}</th> -->
                                <th>{{ $t('guide.sector.total_routes_header') }}</th>
                                <th v-for="category in route_categories" :key="category.value"> {{ $t(category.label) }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(categories, author) in filteredAndSortedAuthors" :key="author">
                                <td>{{author}}</td>
                                <td>{{ getTotalRoutes(categories) }}</td>
                                <td v-for="category in route_categories" :key="category.value"> {{ categories[category.value] || 0 }} </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </pre>
        </stack-modal>
    </span>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
        },
        data(){
            return{
                authers: [],
                is_show_modal: false,
                // MIX_SITE_URL: process.env.MIX_SITE_URL,
                // MIX_APP_SSH: process.env.MIX_APP_SSH,
                route_categories: [],
                filterAuthor: '',
                sortBy: 'total',
                sortOrder: 'desc'
            }
        },

        props: {
            route_categories_prop: {
                type: Array,
                default: () => [
                    { value: 'sport', label: 'guide.sector.sport_climbing' },
                    { value: 'boulder', label: 'guide.sector.bouldering' },
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' }
                ]
            }
        },

        mounted(){
            this.route_categories = this.route_categories_prop
        },

        watch: {
            route_categories_prop: function(newVal, oldVal) { 
                this.route_categories = this.route_categories_prop
            }
        },
        
        computed: {
            filteredAndSortedAuthors() {
                let result = { ...this.authers };
                
                // Filter by author name
                if (this.filterAuthor) {
                    const filter = this.filterAuthor.toLowerCase();
                    result = Object.fromEntries(
                        Object.entries(result).filter(([author]) => 
                            author.toLowerCase().includes(filter)
                        )
                    );
                }
                
                // Filter out authors who don't have routes in the selected category (when sorting by category)
                if (this.sortBy !== 'author' && this.sortBy !== 'total') {
                    result = Object.fromEntries(
                        Object.entries(result).filter(([author, categories]) => {
                            const count = categories[this.sortBy] || 0;
                            return count > 0;
                        })
                    );
                }
                
                // Sort authors
                const sortedEntries = Object.entries(result).sort((a, b) => {
                    let valueA, valueB;
                    
                    if (this.sortBy === 'author') {
                        valueA = a[0].toLowerCase();
                        valueB = b[0].toLowerCase();
                    } else if (this.sortBy === 'total') {
                        valueA = this.getTotalRoutes(a[1]);
                        valueB = this.getTotalRoutes(b[1]);
                    } else {
                        // Sort by specific category
                        valueA = a[1][this.sortBy] || 0;
                        valueB = b[1][this.sortBy] || 0;
                    }
                    
                    if (this.sortOrder === 'asc') {
                        return valueA > valueB ? 1 : -1;
                    } else {
                        return valueA < valueB ? 1 : -1;
                    }
                });
                
                return Object.fromEntries(sortedEntries);
            }
        },
        
        methods: {
            show_modal(){
                // Reset filter and sort values
                this.filterAuthor = '';
                this.sortBy = 'total';
                this.sortOrder = 'desc';
                
                axios
                .post('/get_route/routes_authers_by_categories/', {
                    route_categories: this.route_categories.map(cat => cat.value)
                })
                .then(response => {
                    this.authers = response.data
                    this.is_show_modal = true
                })
                .catch(error =>{
                })
            },
            getTotalRoutes(categories) {
                // Sum only the categories that are in route_categories prop
                return this.route_categories.reduce((sum, category) => {
                    return sum + (categories[category.value] || 0);
                }, 0);
            }
        }
    }
</script>

<style>
.language-vue{
    margin: 10px 0 0 0;
    width: 95%;

    position: absolute;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    overflow-x: auto;
}
.modal-body{
    height: 600px;
}
th:nth-child(2n+1) {
  background: #d7b396;
}

.table-responsive {
    overflow-x: auto;
    width: 100%;
}

.table-responsive table {
    min-width: 800px;
}

/* Mobile button centering */
@media (max-width: 767px) {
    div > .main-btn {
        display: block;
        margin: 1em auto;
        float: none !important;
        width: fit-content;
    }
}
</style>
