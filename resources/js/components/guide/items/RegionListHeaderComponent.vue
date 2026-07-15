<template>
    <div class="region-list-header">
        <div class="region-header-top">
            <h2>{{ title }}</h2>
            <slot name="map-button"></slot>
        </div>
        <p v-if="description" class="region-header-desc" v-html="description"></p>
    </div>
</template>

<script>
    export default {
        name: 'RegionListHeaderComponent',
        props: {
            title: {
                type: String,
                required: true
            },
            description: {
                type: String,
                default: ''
            }
        }
    }
</script>

<style scoped>
.region-list-header {
    background: #efeff0;
    border-left: 3px solid #007bff;
    border-top: 2px solid #007bff;
    margin-bottom: 1.2rem;
    margin-top: 1.5rem;
    padding: .875rem 1rem;
    border-radius: 20px 0 0 0;
}

.region-header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.region-list-header h2 {
  margin: 0;
  text-align: left;
}
</style>
