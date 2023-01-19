import { defineAbility } from '@casl/ability';

export default (user) => 
	defineAbility(
		(can) => {
			can('read', 'Article');
			can('create', 'Article');
			// can('del', 'Article');
		},
	);



// import { Ability } from '@casl/ability';

// export default new Ability()
//https://www.appsloveworld.com/vuejs/100/54/permissions-and-roles-using-vuejs-and-laravel
