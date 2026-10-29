<template>
    <!-- The account defaults as choice cards; v-model is
         { lang, grade, mount_grade, animations }. Shared by the options page
         Preferences tab and the welcome questions. -->
    <div class="cg-prefs">
        <fieldset class="cg-prefs__group">
            <legend><i class="fa fa-language" aria-hidden="true"></i> {{ $t('user.preferences.lang') }}</legend>
            <div class="cg-prefs__choices">
                <label v-for="o in langs" :key="o.value" class="cg-prefs__choice" :class="{ 'is-active': modelValue.lang === o.value }">
                    <input type="radio" :value="o.value" :checked="modelValue.lang === o.value" @change="set('lang', o.value)" />
                    <strong>{{ o.label }}</strong>
                </label>
            </div>
        </fieldset>

        <fieldset class="cg-prefs__group">
            <legend><i class="fa fa-signal" aria-hidden="true"></i> {{ $t('user.preferences.grade') }}</legend>
            <p class="cg-prefs__hint">{{ $t('user.preferences.grade_hint') }}</p>
            <div class="cg-prefs__choices">
                <label v-for="o in grades" :key="o.value" class="cg-prefs__choice" :class="{ 'is-active': modelValue.grade === o.value }">
                    <input type="radio" :value="o.value" :checked="modelValue.grade === o.value" @change="set('grade', o.value)" />
                    <strong>{{ o.label }}</strong>
                    <span class="cg-grade">{{ o.example }}</span>
                </label>
            </div>
        </fieldset>

        <fieldset class="cg-prefs__group">
            <legend><i class="fa fa-area-chart" aria-hidden="true"></i> {{ $t('user.preferences.mount_grade') }}</legend>
            <p class="cg-prefs__hint">{{ $t('user.preferences.mount_grade_hint') }}</p>
            <div class="cg-prefs__choices">
                <label v-for="o in mount_grades" :key="o.value" class="cg-prefs__choice" :class="{ 'is-active': modelValue.mount_grade === o.value }">
                    <input type="radio" :value="o.value" :checked="modelValue.mount_grade === o.value" @change="set('mount_grade', o.value)" />
                    <strong>{{ $t('user.preferences.mount_' + o.value) }}</strong>
                    <span class="cg-grade">{{ o.example }}</span>
                </label>
            </div>
        </fieldset>

        <fieldset class="cg-prefs__group">
            <legend><i class="fa fa-magic" aria-hidden="true"></i> {{ $t('user.preferences.animations') }}</legend>
            <p class="cg-prefs__hint">{{ $t('user.preferences.animations_hint') }}</p>
            <div class="cg-prefs__choices">
                <label class="cg-prefs__choice" :class="{ 'is-active': modelValue.animations }">
                    <input type="radio" :checked="modelValue.animations" @change="set('animations', true)" />
                    <strong>{{ $t('user.preferences.animations_on') }}</strong>
                </label>
                <label class="cg-prefs__choice" :class="{ 'is-active': !modelValue.animations }">
                    <input type="radio" :checked="!modelValue.animations" @change="set('animations', false)" />
                    <strong>{{ $t('user.preferences.animations_off') }}</strong>
                </label>
            </div>
        </fieldset>
    </div>
</template>

<script>
    export default {
        name: 'PreferenceFields',
        props: {
            modelValue: { type: Object, required: true },
        },
        emits: ['update:modelValue'],
        data() {
            return {
                langs: [{ value: 'us', label: 'English' }, { value: 'ka', label: 'ქართული' }],
                grades: [{ value: 'yds', label: 'YDS', example: '5.10a' }, { value: 'uiaa', label: 'UIAA', example: 'VII-' }],
                mount_grades: [{ value: 'russian', example: '3А' }, { value: 'french', example: 'AD-' }],
            };
        },
        methods: {
            set(key, value) {
                this.$emit('update:modelValue', Object.assign({}, this.modelValue, { [key]: value }));
            },
        },
    };
</script>

<style scoped>
    /* doubled class + parents: beats the user panel's `.cg-admin .tabs label` resets */
    .cg-prefs { display: grid; gap: 1.1rem; }
    .cg-prefs__group { margin: 0; padding: 0; border: 0; }
    .cg-prefs__group legend { float: none; width: auto; margin-bottom: .35rem; font-size: 1rem; font-weight: 800; color: var(--cg-ink); }
    .cg-prefs__group legend .fa { width: 1.2rem; color: var(--cg-accent); }
    .cg-prefs__hint { margin: -.15rem 0 .5rem; color: var(--cg-stone); font-size: .85rem; }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choices { display: flex; flex-wrap: wrap; gap: .6rem; }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        min-width: 9rem;
        padding: .6rem .9rem;
        border: 2px solid var(--cg-line);
        border-radius: var(--cg-r);
        background: #fff;
        cursor: pointer;
        transition: border-color .15s, background .15s;
    }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice input { position: absolute; opacity: 0; pointer-events: none; }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice:hover { border-color: rgba(var(--cg-accent-rgb), .5); }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice.is-active { border-color: var(--cg-accent); background: var(--cg-accent-soft); }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice:focus-within { outline: 3px solid rgba(var(--cg-accent-rgb), .35); outline-offset: 2px; }
    .cg-prefs .cg-prefs__choices > label.cg-prefs__choice.cg-prefs__choice .cg-grade { margin-left: auto; font-size: .85rem; }
</style>
