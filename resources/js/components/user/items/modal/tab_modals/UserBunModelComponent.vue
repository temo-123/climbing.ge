<template>
    <stack-modal
            :show="is_ban_modal"
            title="Create ban on this user"
            @close="close_create_ban_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <select class="form-control" name="comment delete cause" > 
                <option value="Ban time" disabled>Ban time</option>
                <option value="0.5 year">0.5 year</option>
                <option value="1 year">1 year</option>
                <option value="2 year">2 year</option>
            </select> 
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="create_ban()"
                >
                Create ban
                </button>
            </div>
        </div>
    </stack-modal>
</template>


<script>
    // import tabsComponent  from '../items/data_table/TabsComponent.vue'

    // import breadcrumb from '../items/BreadcrumbComponent.vue'
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            // tabsComponent ,
            // breadcrumb,
        },
        data(){
            return {
                data_for_tab: [],
                is_ban_modal: false,
                user_id: 0
            }
        },
        mounted() {
            // this.get_users()
        },
        watch: {
            // '$route' (to, from) {
            //     this.data_for_tab = [],
            //     // this.get_users()
            //     window.scrollTo(0,0)
            // }
        },
        methods: {
            show_modal(id){
                this.user_id = id
                this.is_ban_modal = true
            },
            
            close_modal(){
                this.is_ban_modal = false
            },

            create_ban(){
                alert('Commitng soon! User ID ->' + this.user_id)
                this.close_modal()
            }
        }
    }
</script>