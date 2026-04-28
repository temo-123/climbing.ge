<template>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;">
        <div
            v-for="toast in toasts"
            :key="toast.id"
            class="toast show align-items-center border-0"
            :class="'text-bg-' + toast.type"
            role="alert"
            aria-live="assertive"
        >
            <div class="d-flex">
                <div class="toast-body">
                    <strong v-if="toast.title">{{ toast.title }}<br></strong>
                    {{ toast.message }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="remove(toast.id)"></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ToastNotification',
    data() {
        return { toasts: [], nextId: 0 };
    },
    mounted() {
        this.$bus.$on('toast', this.addToast);
    },
    beforeUnmount() {
        this.$bus.$off('toast', this.addToast);
    },
    methods: {
        addToast({ message, title = '', type = 'danger', duration = 5000 }) {
            const id = ++this.nextId;
            this.toasts.push({ id, message, title, type });
            setTimeout(() => this.remove(id), duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
    },
};
</script>
