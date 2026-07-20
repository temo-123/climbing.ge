<template>
    <div class="notify-subscribers-box">
        <div class="notify-subscribers-title">
            <i class="fa fa-bell-o" aria-hidden="true"></i>
            {{ $t('admin.common.notify_subscribers_title') }}
        </div>

        <label class="notify-option" :class="{ 'notify-option-active': data === 'none' }">
            <input type="radio" value="none" v-model="data" @change="change()">
            <div class="notify-option-body">
                <span class="notify-option-label">{{ $t('admin.common.notify_none_label') }}</span>
            </div>
        </label>

        <label class="notify-option" :class="{ 'notify-option-active': data === 'update' }">
            <input type="radio" value="update" v-model="data" @change="change()">
            <div class="notify-option-body">
                <span class="notify-option-label">{{ $t('admin.common.notify_update_label') }}</span>
                <small class="notify-option-hint">{{ $t('admin.common.notify_update_hint') }}</small>
            </div>
        </label>

        <label class="notify-option" :class="{ 'notify-option-active': data === 'new' }">
            <input type="radio" value="new" v-model="data" @change="change()">
            <div class="notify-option-body">
                <span class="notify-option-label">{{ $t('admin.common.notify_new_label') }}</span>
                <small class="notify-option-hint">{{ $t('admin.common.notify_new_hint') }}</small>
            </div>
        </label>
    </div>
</template>

<script>
    export default {
        props: [
            'notify_mode_prop'
        ],

        watch: {
            notify_mode_prop: function (newVal) {
                this.data = newVal
            },
        },

        data() {
            return {
                data: 'none',
            }
        },

        methods: {
            change() {
                this.$emit('item_data', this.data)
            }
        }
    }
</script>

<style scoped>
.notify-subscribers-box {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px 14px;
    background: #fafbfc;
    margin-bottom: 1rem;
}
.notify-subscribers-title {
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
}
.notify-subscribers-title i {
    color: #f0ad4e;
    margin-right: 6px;
}
.notify-option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color .15s ease;
}
.notify-option:hover {
    background-color: #f0f2f5;
}
.notify-option-active {
    background-color: #eaf3ff;
}
.notify-option input[type="radio"] {
    margin-top: 4px;
}
.notify-option-body {
    display: flex;
    flex-direction: column;
}
.notify-option-label {
    font-weight: 500;
    color: #212529;
}
.notify-option-hint {
    color: #868e96;
    font-size: 12px;
    margin-top: 2px;
}
</style>
