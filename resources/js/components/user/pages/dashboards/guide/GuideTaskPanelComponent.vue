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
        <div class="col-md-12">
          <tam_members_tasks_panel />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import breadcrumb from '../../../items/BreadcrumbComponent.vue'
import tam_members_tasks_panel from './assets/TeamMembersTaskControlComponent.vue'

export default {
  components: {
    breadcrumb,
    tam_members_tasks_panel,
  },
  data() {
    return {
      // No tabs needed, direct to tasks
    }
  },
  mounted() {
    //
  }
}
</script>

<style scoped>
/* Styles if needed */
</style>

