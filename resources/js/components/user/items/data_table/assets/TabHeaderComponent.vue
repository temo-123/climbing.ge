<template>
    <thead>
        <tr>
            <th style="text-align: center">
                <input type="checkbox" class="all" ref="selectAllCheckbox" :checked="isAllSelected" :indeterminate.prop="isIndeterminate" @change="toggleSelectAll" />
            </th>

            <th
                v-for="(h, h_key) in (tab_data?.tab?.head || [])"
                :key="h_key"
                @click="$emit('sort', h_key)"
                style="cursor: pointer; user-select: none; white-space: nowrap;"
            >
                {{ h }}
                <span v-if="sortColIndex === h_key" style="font-size: 11px;">
                    {{ sortDir === 'asc' ? '▲' : '▼' }}
                </span>
            </th>
        </tr>
    </thead>
</template>

<script>
export default {
    emits: ['toggle-select-all', 'sort'],
    props: {
        head_data_prop: {
            type: Object,
            default: () => ({ tab: { head: [] } })
        },
        selectedItems: {
            type: Array,
            default: () => []
        },
        totalItems: {
            type: Number,
            default: 0
        },
        sortColIndex: {
            type: Number,
            default: null
        },
        sortDir: {
            type: String,
            default: 'asc'
        }
    },
    data(){
        return{
            tab_data: this.head_data_prop
        }
    },
    computed: {
        isAllSelected() {
            return this.selectedItems.length === this.totalItems && this.totalItems > 0;
        },
        isIndeterminate() {
            return this.selectedItems.length > 0 && this.selectedItems.length < this.totalItems;
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
    methods: {
        toggleSelectAll() {
            this.$emit('toggle-select-all');
        }
    }
}
</script>