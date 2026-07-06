<template>
    <section class="dashboard-section">
        <header class="dashboard-section__header">
            <i class="fa" :class="icon" aria-hidden="true"></i>
            <h2>{{ title }}</h2>
        </header>
        <div class="card">
            <div class="card-body">
                <slot />
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'DashboardSection',
    props: {
        title: { type: String, required: true },
        icon: { type: String, default: 'fa-square-o' },
    },
}
</script>

<style scoped>
.dashboard-section {
    margin-bottom: 1.75rem;
}

.dashboard-section__header {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: .6rem;
}

.dashboard-section__header i {
    color: #279fbb;
    font-size: 1.05em;
}

.dashboard-section__header h2 {
    font-size: 1.15rem;
    font-weight: 600;
    margin: 0;
}
</style>
