<template>
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
                <div class="col-sm-12">
                    <tabsComponent 
                        :table_data="this.data_for_tab"

                        @update="get_sectors"

                        @del_sector="del_sector"
                        @del_route="del_route"
                        @del_multi_pitch="del_multi_pitch"
                        @del_pitch="del_pitch"

                        @sector_modal="show_sector_model"
                        @mtp_modal="show_mtp_model"
                    />
                </div>
            </div>
        </div>

        <sectorModal ref="sector_modal" />
        <mtpModal ref="mtp_modal" />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import sectorModal from "../../items/modal/tab_modals/SectorModalComponent.vue";
    import mtpModal from "../../items/modal/tab_modals/MTPModalComponent.vue";
    export default {
        components: {
            tabsComponent,
            breadcrumb,
            sectorModal,
            mtpModal
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],

                sectors: {},
                routes: {},
                mtp: {},
                mtp_pitchs: {}
            }
        },
        mounted() {
            this.get_sectors()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_sectors()
                window.scrollTo(0,0)
            }
        },
        methods: {
            filtr(event){
                if(event.filtr_category == "sector_filtr"){
                    this.filtr_sector(event.filtr_id)
                }
                else if(event.filtr_category == "route_filtr"){
                    this.filtr_route(event.filtr_id)
                }
                else if(event.filtr_category == "mtp_filtr"){
                    this.filtr_mtp(event.filtr_id)
                }
                else if(event.filtr_category == "mtp_pitch_filtr"){
                    this.filtr_mtp_pitch(event.filtr_id)
                }
            },

            filtr_sector(region_id){
                if (region_id == 'all' || region_id == 'All' || region_id == 0 || region_id == null) {
                    this.data_for_tab[0]['data'] = this.sectors
                }
                else{
                    this.data_for_tab[0]['data'] = this.sectors.filter(function (item){
                        return item.article_id == region_id
                    })
                }
            },
            get_sectors(){
                axios
                .get("../api/sector/")
                .then(response => {
                    this.data_for_tab = [];

                    this.sectors = response.data

                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Sectors', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'sectorAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Public',
                                                        'Edit routes',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['name'], 'sector_modal'],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_fun_id', 'sector_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'],
                                                        ['action_router', 'sectorEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['sector', 'edit'],
                                                        ['sector', 'edit'],
                                                        ['sector', 'del'],
                                                    ]
                                                }
                                            },
                                        });

                    this.get_routes()
                })
                .catch(
                    error => console.log(error)
                );
            },

            filtr_route(sector_id){
                if (sector_id == 'all' || sector_id == 'All' || sector_id == 0 || sector_id == null) {
                    this.data_for_tab[1]['data'] = this.routes
                }
                else{
                    this.data_for_tab[1]['data'] = this.routes.filter(function (item){
                        return item.sector_id == sector_id
                    })
                }
            },
            get_routes(){
                axios
                .get("route/get_all_routes/")
                .then(response => {
                    this.routes = response.data

                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Routes', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'routeAdd', 
                                                'class': 'btn btn-primary',
                                                'name': 'Add Route'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Grade',
                                                        'Height',
                                                        'Bolts',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['name']],
                                                        ['data', ['grade'], ['or_grade']],
                                                        ['data', ['height']],
                                                        ['data', ['bolts']],
                                                        ['action_router', 'routeEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_route', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['route', 'edit'],
                                                        ['route', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_mtp()
                })
                .catch(
                    error => console.log(error)
                );
            },

            filtr_mtp(sector_id){
                if (sector_id == 'all' || sector_id == 'All' || sector_id == 0 || sector_id == null) {
                    this.data_for_tab[2]['data'] = this.mtp
                }
                else{
                    this.data_for_tab[2]['data'] = this.routes.filter(function (item){
                        return item.sector_id == sector_id
                    })
                }
            },
            get_mtp(){
                axios
                .get("../api/mtp/")
                .then(response => {
                    this.mtp = response.data

                    this.data_for_tab.push({
                                            'id': 3,
                                            'table_name': 'Multi-pitchs', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'MTPAdd', 
                                                'class': 'btn btn-primary',
                                                'name': 'Add Route'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Height',
                                                        'Edit pitchs',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['name'], 'mtp_modal'],
                                                        ['data', ['height']],
                                                        ['action_fun_id', 'mtp_modal', 'btn btn-success', '<i aria-hidden="true" class="fa fa-list-ol"></i>'],
                                                        ['action_router', 'MTPEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_multi_pitch', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['mtp', 'edit'],
                                                        ['mtp', 'edit'],
                                                        ['mtp', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_mtp_pitch()
                })
                .catch(
                    error => console.log(error)
                );
            },

            filtr_mtp_pitch(mtp_id){
                if (mtp_id == 'all' || mtp_id == 'All' || mtp_id == 0 || mtp_id == null) {
                    this.data_for_tab[3]['data'] = this.mtp_pitchs
                }
                else{
                    this.data_for_tab[3]['data'] = this.mtp_pitchs.filter(function (item){
                        return item.mtp_id == mtp_id
                    })
                }
            },
            get_mtp_pitch(){
                axios
                .get("../api/mtp/mtp_pitch/")
                .then(response => {
                    this.mtp_pitchs = response.data

                    this.data_for_tab.push({
                                            'id': 4,
                                            'table_name': 'Pitches', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'MTPPitchAdd', 
                                                'class': 'btn btn-primary',
                                                'name': 'Add Route'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Grade',
                                                        'Height',
                                                        'Bolts',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['name']],
                                                        ['data', ['grade'], ['or_grade']],
                                                        ['data', ['height']],
                                                        ['data', ['bolts']],
                                                        ['action_router', 'MTPPitchEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_pitch', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['mtp_pitch', 'edit'],
                                                        ['mtp_pitch', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            // update(id){
            //     this.get_sectors()
            // },

            del_sector(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/sector/del_sector_sector/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_sector_model(sector_id){
                this.$refs.sector_modal.show_sector_modal(sector_id)
            },
            del_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/route/del_route/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.update(this.tab_num)

                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            del_multi_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('mtp/del_mtp/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_mtp_model(sector_id){
                this.$refs.mtp_modal.show_mtp_modal(sector_id)
            },
            del_pitch(pitch_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/mtp/mtp_pitch/del_pitch/'+pitch_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>