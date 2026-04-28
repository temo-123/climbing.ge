import { defineAbility } from '@casl/ability';

export const abilityDefaults = defineAbility((can) => {
    can('read', 'public');
});

/**
 * Call this after auth_user loads to sync CASL rules with backend permissions.
 * permissions: [{ action: 'add', subject: 'article' }, ...]
 */
export function updateAbility(permissions = []) {
    abilityDefaults.update(
        permissions.map(p => ({ action: p.action, subject: p.subject }))
    );
}
