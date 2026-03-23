import { defineAbility } from '@casl/ability';

const defineRules = (can) => {
  can('read', 'public');
  // Safe defaults - user rules updated dynamically via .update(rulesFn)
};

export const abilityDefaults = defineAbility(defineRules);



// export default new Ability()
//https://www.appsloveworld.com/vuejs/100/54/permissions-and-roles-using-vuejs-and-laravel
