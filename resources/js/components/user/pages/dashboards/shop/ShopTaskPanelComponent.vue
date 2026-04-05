<template>
  <div>
    <h2>Shop Task Panel (Table Test)</h2>
    <tabs-component :table_data="sampleTableData" @update="loadData" />
  </div>
</template>

<script>
import TabsComponent from '../../../items/data_table/TabsComponent.vue';

export default {
  components: { TabsComponent },
  data() {
    return {
      sampleTableData: [
        {
          id: 1,
          table_name: 'Sample Tasks',
          tab_data: {
            data: [
              { id: 1, name: 'Task 1', status: 'pending', actions: 'edit' },
              { id: 2, name: 'Task 2', status: 'done', actions: 'view' },
              { id: 3, name: 'Task 3', status: 'pending', actions: 'delete' }
            ],
            head: [
              ['data', ['name'], 'text'],
              ['data', ['status'], 'bool'],
              ['action_router', 'editTask', 'btn btn-primary', 'Edit']
            ]
          }
        }
      ]
    };
  },
  methods: {
    loadData() {
      console.log('Table refreshed');
    }
  }
};
</script>
