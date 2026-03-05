<template>
    <div class="col-md-12">
        <table class="table table-hover" id="dev-table">
            <thead>
                <tr>
                    <th style="text-align: center">
                        <input type="checkbox" class="all" />
                    </th>
                    <th>|</th>

                    <th>User ID</th>
                    <th>|</th>

                    <th>User name</th>
                    <th>|</th>

                    <th>Email</th>
                    <th>|</th>

                    <th>Task count</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="worker in workers" :key="worker.id">
                    <td style='text-align: center;'>
                        <input type="checkbox">
                    </td>
                    <td>|</td>
                    <td>{{worker.id}}</td>
                    <td>|</td>

                    <td @click="check_user_task_list(worker.id)" title="Click for quick wiev" style="cursor: zoom-in;">
                        {{worker.name}} {{worker.surmane}}
                    </td>

                    <td>|</td>
                    <td>{{worker.email}}</td>

                    <td>|</td>
                    <td>?</td>
                </tr>

            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        components: {
            //
        },
        data(){
            return{
                workers: [],
            }
        },
        mounted(){
            this.get_workers()
        },
        methods: {
            get_workers(){
                axios
                .get("/get_user/get_worker_users/")
                .then(response => {
                    this.workers = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }, 
            check_user_task_list(){
                alert('Worker task list show function comming soon')
            }
        }
    }
</script>