<template>
    <div class="toast-notification-container">
        <div
            v-for="toast in toasts"
            :key="toast.id"
            class="toast-notification"
            :class="'toast-notification--' + toast.type"
            role="alert"
            aria-live="assertive"
        >
            <div class="toast-notification-body">
                <strong v-if="toast.title">{{ toast.title }}<br></strong>
                {{ toast.message }}
            </div>
            <button type="button" class="toast-notification-close" @click="remove(toast.id)">&times;</button>
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

<style scoped>
/* Self-contained — this project's shop layout loads Bootstrap 3 from a CDN,
   which has no `.toast`/`text-bg-*`/`.btn-close` classes at all (those are
   Bootstrap 5-only), so relying on them left every toast rendering as an
   unstyled, borderless block. Written from scratch instead of assuming any
   particular Bootstrap version is present. */
.toast-notification-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 11000;
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 360px;
}
.toast-notification {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    border-radius: 8px;
    color: #fff;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    font-size: 13.5px;
    line-height: 1.5;
    animation: toast-notification-in 0.2s ease-out;
}
@keyframes toast-notification-in {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.toast-notification-body { flex: 1; }
.toast-notification-body strong { display: inline-block; margin-bottom: 2px; }
.toast-notification-close {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.85);
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    flex-shrink: 0;
}
.toast-notification-close:hover { color: #fff; }

.toast-notification--danger { background: #d9534f; }
.toast-notification--warning { background: #e0a338; }
.toast-notification--success { background: #5cb85c; }
.toast-notification--info { background: #4a90d9; }
</style>
