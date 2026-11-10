<template>
    <!-- Small segmented switch: "LABEL  [icon A] [icon B]". Shared by the
         article Text / Cards switch and the sectors Full / Compact switch. -->
    <div class="cg-view-switch" role="group" :aria-label="label">
        <span v-if="label" class="cg-view-switch__label">{{ label }}</span>
        <button
            v-for="v in views"
            :key="v.mode"
            type="button"
            class="cg-view-switch__btn"
            :class="{ 'is-active': modelValue === v.mode }"
            :aria-pressed="modelValue === v.mode ? 'true' : 'false'"
            :title="v.title || v.label"
            @click="$emit('update:modelValue', v.mode)"
        >
            <i class="fa" :class="v.icon" aria-hidden="true"></i>
            <span>{{ v.label }}</span>
        </button>
    </div>
</template>

<script>
    export default {
        name: 'ViewSegmentSwitchComponent',
        props: {
            modelValue: { type: String, default: '' },
            // [{ mode, icon (fa-…), label, title? }]
            views: { type: Array, default: () => [] },
            label: { type: String, default: '' },
        },
        emits: ['update:modelValue'],
    };
</script>

<style scoped>
.cg-view-switch {
    display: flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem;
    border-radius: 999px;
    background: var(--cg-chalk);
}
.cg-view-switch__label {
    padding: 0 .35rem 0 .55rem;
    margin-right: auto;
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--cg-muted);
}
.cg-view-switch__btn {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    border: 0;
    padding: .3rem .7rem;
    border-radius: 999px;
    background: transparent;
    font-size: .8rem;
    font-weight: 700;
    color: var(--cg-stone);
    cursor: pointer;
    white-space: nowrap;
}
.cg-view-switch__btn:hover { color: var(--cg-ink); }
.cg-view-switch__btn.is-active {
    background: var(--cg-surface, #fff);
    color: var(--cg-accent-dark);
    box-shadow: var(--cg-shadow-sm);
}
.cg-view-switch__btn:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
</style>
