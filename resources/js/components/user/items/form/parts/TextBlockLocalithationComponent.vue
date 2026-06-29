<template>
    <div class="tabs row">
        <div class="col-md-12">
            <div class="row">
                <div class="col">
                    <input type="radio" :id="uid + '_tab_1'" :value="1" v-model="tab_num">
                    <label :for="uid + '_tab_1'">English text</label>
                </div>
                <div class="col">
                    <input type="radio" :id="uid + '_tab_2'" :value="2" v-model="tab_num">
                    <label :for="uid + '_tab_2'">Georgian text</label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group clearfix row" v-show="tab_num == 1">
                <label :for="uid + '_tab_1'" class='col-md-2 control-label'>English description</label>
                <div class="col-md-10">
                    <big_editor v-model="local_en" />
                </div>
            </div>
            <div class="form-group clearfix row" v-show="tab_num == 2">
                <label :for="uid + '_tab_2'" class='col-md-2 control-label'>Georgian description</label>
                <div class="col-md-10">
                    <big_editor v-model="local_ka" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            en_value: { type: String, default: '' },
            ka_value: { type: String, default: '' },
        },
        emits: ['update:en_value', 'update:ka_value'],
        data() {
            return {
                tab_num: 1,
                uid: 'tbl_' + Math.random().toString(36).slice(2, 8),
            }
        },
        computed: {
            local_en: {
                get() { return this.en_value },
                set(val) { this.$emit('update:en_value', val) },
            },
            local_ka: {
                get() { return this.ka_value },
                set(val) { this.$emit('update:ka_value', val) },
            },
        },
    }
</script>
