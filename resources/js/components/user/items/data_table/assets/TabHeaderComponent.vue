<template>
    <thead>
        <tr>
            <th style="text-align: center">
                <input type="checkbox" class="all" />
            </th>

            <th
                v-for="(h, h_key) in tab_data .tab.head" :key="h_key"
                    v-if="  tab_data.tab.perm[h_key][0] == 'no' ||
                            $can(tab_data.tab.perm[h_key][1],
                                tab_data.tab.perm[h_key][0]
                            )
                    "
                >
                {{ h }}
            </th>
        </tr>
    </thead>
</template>

<script>
export default {
    props: [
        "head_data_prop"
    ],
    data(){
        return{
            tab_data: this.head_data_prop
        }
    },
    watch: {
        head_data_prop: function(){
            this.tab_data = this.head_data_prop
        },
    },
    mounted() {
        this.tab_data = this.head_data_prop
    },
}
</script>