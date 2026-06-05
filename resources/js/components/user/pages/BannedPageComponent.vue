<template>
    <div class="banned-page d-flex align-items-center justify-content-center min-vh-100">
        <div class="banned-card text-center p-5">
            <div class="banned-icon mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                </svg>
            </div>

            <h1 class="banned-title mb-2">{{ $t('error.banned.title') }}</h1>
            <p class="banned-subtitle mb-4">{{ $t('error.banned.subtitle') }}</p>

            <div class="banned-notice mb-3">
                <p class="mb-2">{{ $t('error.banned.reason') }}</p>
                <p class="mb-2">{{ $t('error.banned.blacklist') }}</p>
                <p class="mb-0 text-muted small">{{ $t('error.banned.no_appeal') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('x_xsrf_token');
        localStorage.removeItem('user_permissions');

        const navbar = document.querySelector('.admin_page_header_navbar');
        if (navbar) navbar.style.display = 'none';
        const body = document.querySelector('body');
        if (body) body.style.marginLeft = '0';
    },
};
</script>

<style scoped>
.banned-page {
    background: #f8f9fa;
    min-height: 100vh;
}

.banned-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    max-width: 520px;
    width: 100%;
}

.banned-icon {
    color: #dc3545;
}

.banned-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
}

.banned-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
}

.banned-notice {
    background: #fff3f3;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 1.25rem;
    color: #491217;
    font-size: 0.95rem;
    text-align: left;
}
</style>
