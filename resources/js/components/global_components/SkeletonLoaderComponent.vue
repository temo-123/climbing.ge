<template>
    <div class="skeleton-loader-wrapper">
        <div class="skeleton-pulse"></div>
    </div>
</template>

<script>
    export default {
        name: 'SkeletonLoader',
        props: ['viewBox', 'primaryColor', 'secondaryColor']
    }
</script>

<style scoped>
.skeleton-loader-wrapper {
    width: 100%;
    padding: 10px 0;
}
.skeleton-pulse {
    height: 120px;
    background: linear-gradient(90deg, #f3f3f3 25%, #e8e8e8 50%, #f3f3f3 75%);
    background-size: 200% 100%;
    animation: skeleton-pulse 1.5s infinite;
    border-radius: 4px;
}
@keyframes skeleton-pulse {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
