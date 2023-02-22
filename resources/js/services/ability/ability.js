import { defineAbility } from '@casl/ability';
// import { Ability } from '@casl/ability';

export default (user) => 
	defineAbility(
		// This is defolt abilities

		(can) => {
		// 	can('read', 'Article');
		// 	can('create', 'Article');
		// 	can('del', 'Article');
		},
	);

// export default new Ability()
//https://www.appsloveworld.com/vuejs/100/54/permissions-and-roles-using-vuejs-and-laravel
