<template>
    <div class="role-permissions-picker">
        <input
            type="text"
            class="form-control mb-2"
            v-model="filter"
            :placeholder="$t('admin.users.filter_permissions_placeholder')"
        >

        <div class="text-muted small mb-2">
            {{ $t('admin.users.permissions_selected_count', { count: modelValue.length, total: permissions.length }) }}
        </div>

        <div class="role-permissions-picker__groups">
            <div class="card mb-2" v-for="group in filteredGroups" :key="group.subject">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <strong>{{ group.subject }}</strong>
                    <div class="form-check form-switch mb-0">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            role="switch"
                            :id="'group_' + group.subject"
                            :checked="isGroupFullySelected(group)"
                            @change="toggleGroup(group, $event.target.checked)"
                        >
                        <label class="form-check-label small" :for="'group_' + group.subject">
                            {{ $t('admin.users.select_all_btn') }}
                        </label>
                    </div>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4 mb-1" v-for="permission in group.items" :key="permission.id">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :id="'perm_' + permission.id"
                                    :checked="modelValue.includes(permission.id)"
                                    @change="togglePermission(permission.id, $event.target.checked)"
                                >
                                <label class="form-check-label" :for="'perm_' + permission.id">
                                    {{ permission.action }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted" v-if="filteredGroups.length === 0">
                {{ $t('admin.users.no_permissions_found') }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            permissions: { type: Array, default: () => [] },
            modelValue: { type: Array, default: () => [] },
        },
        emits: ['update:modelValue'],
        data(){
            return {
                filter: '',
            }
        },
        computed: {
            groups(){
                const bySubject = {}
                for (const permission of this.permissions) {
                    if (!bySubject[permission.subject]) {
                        bySubject[permission.subject] = []
                    }
                    bySubject[permission.subject].push(permission)
                }
                return Object.keys(bySubject).sort().map(subject => ({
                    subject,
                    items: bySubject[subject],
                }))
            },
            filteredGroups(){
                const needle = this.filter.trim().toLowerCase()
                if (!needle) return this.groups

                return this.groups
                    .map(group => ({
                        subject: group.subject,
                        items: group.items.filter(permission =>
                            group.subject.toLowerCase().includes(needle) ||
                            permission.action.toLowerCase().includes(needle)
                        ),
                    }))
                    .filter(group => group.items.length > 0)
            },
        },
        methods: {
            togglePermission(id, checked){
                const next = new Set(this.modelValue)
                checked ? next.add(id) : next.delete(id)
                this.$emit('update:modelValue', Array.from(next))
            },
            isGroupFullySelected(group){
                return group.items.every(permission => this.modelValue.includes(permission.id))
            },
            toggleGroup(group, checked){
                const next = new Set(this.modelValue)
                for (const permission of group.items) {
                    checked ? next.add(permission.id) : next.delete(permission.id)
                }
                this.$emit('update:modelValue', Array.from(next))
            },
        },
    }
</script>

<style>
    .role-permissions-picker__groups {
        max-height: 50vh;
        overflow-y: auto;
    }
</style>
