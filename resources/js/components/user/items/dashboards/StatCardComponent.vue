<template>
    <router-link v-if="to" :to="to" class="stat-card-link">
        <div class="card-counter" :class="color">
            <i class="fa" :class="icon" aria-hidden="true"></i>
            <span class="count-numbers">
                <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                <template v-else>{{ value ?? 0 }}</template>
            </span>
            <span class="count-name">{{ label }}</span>
        </div>
    </router-link>

    <div v-else class="card-counter card-counter-info" :class="color">
        <i class="fa" :class="icon" aria-hidden="true"></i>
        <span class="count-numbers">
            <i v-if="loading" class="fa fa-spinner fa-spin"></i>
            <template v-else>{{ value ?? 0 }}</template>
        </span>
        <span class="count-name">{{ label }}</span>
    </div>
</template>

<script>
export default {
    name: 'StatCard',
    props: {
        icon: { type: String, required: true },
        value: { type: [Number, String], default: 0 },
        label: { type: String, required: true },
        color: { type: String, default: 'primary' },
        to: { type: Object, default: null },
        loading: { type: Boolean, default: false },
    },
}
</script>

<style scoped>
.stat-card-link {
    text-decoration: none;
    display: block;
}

.card-counter {
    position: relative;
    box-shadow: 2px 2px 10px #DADADA;
    margin-bottom: 10px;
    padding: 18px 14px;
    background-color: #fff;
    min-height: 100px;
    border-radius: 8px;
    transition: .2s ease-out transform, .2s ease-out box-shadow;
    cursor: pointer;
}

.stat-card-link:hover .card-counter {
    box-shadow: 4px 6px 20px #cfcfcf;
    transform: translateY(-2px);
}

.card-counter.card-counter-info {
    cursor: default;
    opacity: 0.9;
}

.card-counter.card-counter-info:hover {
    box-shadow: 2px 2px 10px #DADADA;
    transform: none;
}

.card-counter.primary { background-color: #007bff; color: #FFF; }
.card-counter.secondary { background-color: #6c757d; color: #FFF; }
.card-counter.light { background-color: #a0a1a1; color: #FFF; }
.card-counter.danger { background-color: #ef5350; color: #FFF; }
.card-counter.success { background-color: #66bb6a; color: #FFF; }
.card-counter.sectors { background-color: #279fbb; color: #FFF; }
.card-counter.warning { background-color: #ffc107; color: #FFF; }
.card-counter.info { background-color: #26c6da; color: #FFF; }
.card-counter.dark { background-color: #343a40; color: #FFF; }

.card-counter i:first-child {
    font-size: 3.2em;
    opacity: 0.25;
}

.card-counter .count-numbers {
    position: absolute;
    right: 18px;
    top: 16px;
    font-size: 28px;
    font-weight: 600;
    display: block;
}

.card-counter .count-name {
    position: absolute;
    right: 18px;
    left: 14px;
    top: 58px;
    text-align: right;
    font-style: italic;
    opacity: 0.85;
    display: block;
    font-size: 15px;
    line-height: 1.2;
}
</style>
