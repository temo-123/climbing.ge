<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb user_breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{name: 'home'}"><i class="fa fa-home"></i> {{ $t('user.nav.home') }}</router-link>
            </li>
            <li class="breadcrumb-item" v-if="parentLabel && parentLabel !== pageLabel">
                <router-link v-if="parentTo" :to="parentTo">{{ parentLabel }}</router-link>
                <span v-else>{{ parentLabel }}</span>
            </li>
            <li class="breadcrumb-item active" aria-current="page" :title="pageLabel">{{ pageLabel }}</li>
        </ol>
    </nav>
</template>

<script>
    export default {
        props: {
            // Optional overrides — every existing caller uses this component with no
            // props at all, so these stay fully optional and default to auto-deriving
            // friendly labels from the current URL.
            title: { type: String, default: '' },
            parentTitle: { type: String, default: '' },
            parentPath: { type: String, default: '' },
        },
        data(){
            return{
                general_pathname: window.location.pathname,
            }
        },
        computed: {
            segments() {
                return this.general_pathname.split('/').filter(Boolean);
            },
            parentLabel() {
                if (this.parentTitle) return this.parentTitle;
                if (this.segments.length < 2) return '';
                return this.humanize(this.segments[0]);
            },
            parentTo() {
                if (this.parentPath) return this.parentPath;
                if (this.segments.length < 2) return '';
                return '/' + this.segments[0];
            },
            pageLabel() {
                if (this.title) return this.title;
                const last = this.segments[this.segments.length - 1] || '';
                // Raw numeric IDs (e.g. .../edit/45) aren't friendly on their own —
                // pair them with the nearest meaningful segment instead.
                if (/^\d+$/.test(last)) {
                    const prev = this.segments[this.segments.length - 2];
                    return prev ? this.humanize(prev) + ' #' + last : '#' + last;
                }
                return this.humanize(last);
            },
        },
        methods: {
            humanize(segment) {
                if (!segment) return '';
                return segment
                    .replace(/[-_]+/g, ' ')
                    .trim()
                    .split(' ')
                    .filter(Boolean)
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            },
        },
    }
</script>

<style>
.user_breadcrumb {
    display: flex;
    align-items: center;
    background-color: #f1f3f5 !important;
    border-radius: 6px;
    padding: 0.65rem 1rem;
    margin-bottom: 1.25rem;
    font-size: 0.9rem;
}
.user_breadcrumb .breadcrumb-item a {
    color: #495057;
    text-decoration: none;
    transition: color 0.15s ease;
}
.user_breadcrumb .breadcrumb-item a:hover {
    color: #7427bb;
    text-decoration: underline;
}
.user_breadcrumb .breadcrumb-item.active {
    color: #7427bb;
    font-weight: 600;
    max-width: 40vw;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.user_breadcrumb .fa-home {
    margin-right: 0.25rem;
}
</style>
