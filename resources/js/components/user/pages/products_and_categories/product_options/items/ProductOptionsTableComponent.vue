<template>
    <table class="table table-hover" id="dev-table">
        <thead>
            <tr>
                <th>Option id</th>
                <th>image</th>
                <th>Option name</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody v-if="product_options && product_options.length > 0">
            <tr v-for="option in product_options" :key="option.id">

                <td>{{ option.id }}</td>
                <td>
                    <img v-if="option.images && option.images.length > 0" 
                            :src="'../../../../images/product_option_img/' + option.images[0].image" 
                            :alt="option.name"
                            style="height: 8em; object-fit: cover;">
                    <span v-else>No image</span>
                </td>
                <td>{{ option.name }} </td>
                <td>{{ option.price }} </td>
                <td>{{ option.discount }} </td>

                <td>
                    <button v-if="!is_loading_editing_modal" type="submit" class="btn btn-primary" @click="$emit('edit_option_modal', option.id)">Edit</button>
                    <img v-if="is_loading_editing_modal" :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading" style="width: 20%;">
                </td>
                <td>
                    <button type="submit" class="btn btn-danger" @click="$emit('del_option', option.id)">Delete</button>
                </td>
            </tr>
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="7" class="text-center">No options available</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        product_options: {
            type: Array,
            default: () => []
        },
        is_loading_editing_modal: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            // is_loading_editing_modal: false - now passed as prop
        }
    }
}
</script>

<style scoped>
.product-options-table {
    margin-top: 20px;
}

.table {
    margin-bottom: 0;
}

.table th {
    /* background-color: #f5f5f5; */
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
    text-align: center;
}

.table td:first-child {
    font-weight: 600;
}

.price-display {
    font-weight: 600;
    color: #333;
}

.badge {
    padding: 5px 10px;
    font-size: 12px;
}

.btn-group {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

.image-preview-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.alert-summary {
    margin-top: 15px;
    margin-bottom: 15px;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
}

.alert-info {
    margin: 20px 0;
    padding: 20px;
}

.pull-right {
    float: right;
}

.text-success {
    color: #28a745;
}

.text-muted {
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 12px;
    }
    
    .btn-sm {
        padding: 3px 6px;
        font-size: 10px;
    }
}
</style>

