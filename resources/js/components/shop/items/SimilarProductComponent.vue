<template>
    <div class="similar-section" v-if="!loading && products.length">
        <div class="similar-header">
            <h2 class="similar-title">{{ $t('shop.title.similar.products') }}</h2>
            <div class="similar-title-line"></div>
        </div>
        <div class="similar-grid">
            <analogProduct
                v-for="product in products"
                :key="product.global_product.id"
                :product="product"
            />
        </div>
    </div>

    <div class="similar-skeleton" v-else-if="loading">
        <div class="skeleton-card" v-for="n in 4" :key="n"></div>
    </div>
</template>

<script>
    import analogProduct from './cards/AnalogProductCardComponent.vue'

    export default {
        components: { analogProduct },
        props: ['activ_product_id_prop'],
        data() {
            return {
                products: [],
                loading: false,
            }
        },
        watch: {
            activ_product_id_prop(newId) {
                if (newId) this.load(newId);
            }
        },
        mounted() {
            if (this.activ_product_id_prop) this.load(this.activ_product_id_prop);
        },
        methods: {
            load(id) {
                this.loading = true;
                this.products = [];
                axios
                    .get('/get_product/get_similar_product/' + localStorage.getItem('lang') + '/' + id)
                    .then(r => { this.products = r.data })
                    .catch(() => {})
                    .finally(() => { this.loading = false });
            }
        }
    }
</script>

<style scoped>
    .similar-section { padding: 40px 0 20px; }

    .similar-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .similar-title {
        font-size: 1.5em;
        font-weight: 700;
        color: #222;
        margin-bottom: 10px;
        display: inline-block;
    }
    .similar-title-line {
        width: 50px;
        height: 3px;
        background: #27bb7d;
        margin: 0 auto;
        border-radius: 2px;
    }

    .similar-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    /* Skeleton loader */
    .similar-skeleton {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding: 40px 0 20px;
    }
    .skeleton-card {
        height: 260px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e4e4e4 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 8px;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    @media (max-width: 992px) {
        .similar-grid,
        .similar-skeleton { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .similar-grid,
        .similar-skeleton { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .similar-title { font-size: 1.2em; }
    }
</style>
