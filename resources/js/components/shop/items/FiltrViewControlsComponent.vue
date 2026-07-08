<template>
    <div class="filters-bar">
        <div class="filters-left">
            <button type="button" class="btn btn-primary filter-btn" @click="open_menu()">
                <i class="fa fa-filter"></i> {{ $t('shop.filters.filter_products_btn') }}
                <span v-if="activeFilterCount > 0" class="filter-badge">{{ activeFilterCount }}</span>
            </button>

            <!-- Active filter tags -->
            <div v-if="hasActiveFilters" class="active-filters">
                <span
                    v-for="(value, key) in activeFilters"
                    :key="key"
                    class="filter-tag"
                    @click="$emit('remove-filter', key)"
                >
                    {{ formatFilterLabel(key) }}: {{ formatFilterValue(key, value) }}
                    <i class="fa fa-times remove-icon"></i>
                </span>
                <button class="btn btn-xs btn-outline-danger clear-all" @click="$emit('clear-filters')">
                    {{ $t('shop.filters.clear_all_btn') }}
                </button>
            </div>
        </div>

        <!-- View Mode Toggle -->
        <div class="filters-right">
            <div class="btn-group" role="group" :aria-label="$t('shop.filters.view_mode_aria')">
                <button
                    type="button"
                    class="btn"
                    :class="[viewMode === 'grid' ? 'btn-success active' : 'btn-secondary-custom']"
                    @click="$emit('update:viewMode', 'grid')"
                >
                    <i class="fa fa-th-large"></i>
                </button>
                <button
                    type="button"
                    class="btn"
                    :class="[viewMode === 'list' ? 'btn-success active' : 'btn-secondary-custom']"
                    @click="$emit('update:viewMode', 'list')"
                >
                    <i class="fa fa-list-ul"></i>
                </button>
            </div>
        </div>

        <productLeftMenu
            ref="left_menu"
            :activeFilters="filters"
            @apply-filters="$emit('apply-filters', $event)"
            @brands-loaded="brands = $event"
            @subcategories-loaded="subcategories = $event"
        />
    </div>
</template>

<script>
    import productLeftMenu from './navbars/LeftMenuComponent'

    export default {
        name: 'FiltrViewControlsComponent',
        components: { productLeftMenu },
        props: {
            viewMode: {
                type: String,
                required: true,
                validator: (value) => ['grid', 'list'].includes(value)
            },
            filters: {
                type: Object,
                default: () => ({
                    sale_type: null,
                    brand_id: null,
                    subcategory_id: null,
                    price_min: null,
                    price_max: null
                })
            }
        },
        data() {
            return {
                brands: [],
                subcategories: [],
            }
        },
        computed: {
            activeFilters() {
                const result = {}
                Object.keys(this.filters).forEach(key => {
                    const value = this.filters[key]
                    if (value !== null && value !== undefined && value !== 0 && value !== '') {
                        result[key] = value
                    }
                })
                return result
            },
            hasActiveFilters() {
                return Object.keys(this.activeFilters).length > 0
            },
            activeFilterCount() {
                return Object.keys(this.activeFilters).length
            }
        },
        methods: {
            open_menu() {
                this.$refs.left_menu.open_menu()
            },
            formatFilterLabel(key) {
                const labels = {
                    sale_type: this.$t('shop.filters.sale_type_label'),
                    brand_id: this.$t('shop.product.brand'),
                    subcategory_id: this.$t('common.category'),
                    price_min: this.$t('shop.filters.min_price_label'),
                    price_max: this.$t('shop.filters.max_price_label')
                }
                return labels[key] || key
            },
            formatFilterValue(key, value) {
                if (key === 'price_min' || key === 'price_max') {
                    return '₾' + value
                }
                if (key === 'brand_id') {
                    const brand = this.brands.find(b => b.id == value)
                    return brand && brand.us_brand ? brand.us_brand.title : value
                }
                if (key === 'subcategory_id') {
                    const subcat = this.subcategories.find(s => s.id == value)
                    return subcat ? subcat.us_name : value
                }
                if (key === 'sale_type') {
                    const types = {
                        online_order: this.$t('shop.filters.online_sale_option'),
                        produced_by_order: this.$t('shop.filters.produced_by_order_option'),
                        custom_production: this.$t('shop.filters.custom_production_option')
                    }
                    return types[value] || value
                }
                return value
            }
        }
    }
</script>

<style scoped>
.filters-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}
.filters-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.filter-btn {
    position: relative;
}
.filter-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff6b6b;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.active-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-tag {
    background: #e9ecef;
    color: #495057;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-tag:hover {
    background: #dee2e6;
}
.remove-icon {
    font-size: 11px;
    color: #6c757d;
}
.filter-tag:hover .remove-icon {
    color: #dc3545;
}
.clear-all {
    font-size: 13px;
    padding: 4px 10px;
}
.filters-right {
    display: flex;
    align-items: center;
}
.btn-secondary-custom {
    background: #6c757d;
    color: white;
}
.btn-secondary-custom:hover {
    background: #5a6268;
    color: white;
}
</style>
