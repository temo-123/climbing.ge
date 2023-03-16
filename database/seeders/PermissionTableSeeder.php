<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('permissions')->insert([
        //     [
        //         // 'name' => 'Del article',
        //         // 'slug' => 'manage-users',
        //     ],
        // ]);

        $permissions = array(
            [
                'subject' => 'shiping_country',
                'action' => 'add',
            ],
            [
                'subject' => 'shiping_country',
                'action' => 'edit',
            ],
            [
                'subject' => 'shiping_country',
                'action' => 'del',
            ],

            [
                'subject' => 'dashboard',
                'action' => 'show',
            ],
            
            [
                'subject' => 'article',
                'action' => 'add',
            ],
            [
                'subject' => 'article',
                'action' => 'edit',
            ],
            [
                'subject' => 'article',
                'action' => 'del',
            ],

            [
                'subject' => 'product',
                'action' => 'add',
            ],
            [
                'subject' => 'product',
                'action' => 'edit',
            ],
            [
                'subject' => 'product',
                'action' => 'del',
            ],
            
            [
                'subject' => 'product_category',
                'action' => 'add',
            ],
            [
                'subject' => 'product_category',
                'action' => 'edit',
            ],
            [
                'subject' => 'product_category',
                'action' => 'del',
            ],
            
            [
                'subject' => 'event',
                'action' => 'add',
            ],
            [
                'subject' => 'event',
                'action' => 'edit',
            ],
            [
                'subject' => 'event',
                'action' => 'del',
            ],
            
            [
                'subject' => 'sector',
                'action' => 'add',
            ],
            [
                'subject' => 'sector',
                'action' => 'edit',
            ],
            [
                'subject' => 'sector',
                'action' => 'del',
            ],

            [
                'subject' => 'route',
                'action' => 'add',
            ],
            [
                'subject' => 'route',
                'action' => 'edit',
            ],
            [
                'subject' => 'route',
                'action' => 'del',
            ],

            [
                'subject' => 'mtp',
                'action' => 'add',
            ],
            [
                'subject' => 'mtp',
                'action' => 'edit',
            ],
            [
                'subject' => 'mtp',
                'action' => 'del',
            ],

            [
                'subject' => 'mtp_pitch',
                'action' => 'add',
            ],
            [
                'subject' => 'mtp_pitch',
                'action' => 'edit',
            ],
            [
                'subject' => 'mtp_pitch',
                'action' => 'del',
            ],

            [
                'subject' => 'services',
                'action' => 'add',
            ],
            [
                'subject' => 'services',
                'action' => 'edit',
            ],
            [
                'subject' => 'services',
                'action' => 'del',
            ],

            [
                'subject' => 'services',
                'action' => 'add',
            ],
            [
                'subject' => 'services',
                'action' => 'edit',
            ],
            [
                'subject' => 'services',
                'action' => 'del',
            ],
            
            [
                'subject' => 'sector_local_images',
                'action' => 'add',
            ],
            [
                'subject' => 'sector_local_images',
                'action' => 'edit',
            ],
            [
                'subject' => 'sector_local_images',
                'action' => 'del',
            ],
            
            [
                'subject' => 'mount_route',
                'action' => 'add',
            ],
            [
                'subject' => 'mount_route',
                'action' => 'edit',
            ],
            [
                'subject' => 'mount_route',
                'action' => 'del',
            ],
            
            [
                'subject' => 'mount_massive',
                'action' => 'add',
            ],
            [
                'subject' => 'mount_massive',
                'action' => 'edit',
            ],
            [
                'subject' => 'mount_massive',
                'action' => 'del',
            ],
            
            [
                'subject' => 'gallery',
                'action' => 'add',
            ],
            [
                'subject' => 'gallery',
                'action' => 'edit',
            ],
            [
                'subject' => 'gallery',
                'action' => 'del',
            ],

            [
                'subject' => 'site_data',
                'action' => 'edit',
            ],

            [
                'subject' => 'order',
                'action' => 'edit_order_status',
            ],

            [
                'subject' => 'comments',
                'action' => 'show',
            ],
            [
                'subject' => 'comments',
                'action' => 'del_comment',
            ],

            [
                'subject' => 'user',
                'action' => 'add',
            ],
            [
                'subject' => 'user',
                'action' => 'del',
            ],
            [
                'subject' => 'user',
                'action' => 'edit_permissions',
            ],
            [
                'subject' => 'user',
                'action' => 'create_ban',
            ],

            [
                'subject' => 'product_options',
                'action' => 'add',
            ],
            [
                'subject' => 'product_options',
                'action' => 'del',
            ],
            [
                'subject' => 'product_options',
                'action' => 'edit',
            ],

            [
                'subject' => 'sale_code',
                'action' => 'add',
            ],
            [
                'subject' => 'sale_code',
                'action' => 'del',
            ],
            [
                'subject' => 'sale_code',
                'action' => 'edit',
            ],

            [
                'subject' => 'local_bisnes',
                'action' => 'add',
            ],
            [
                'subject' => 'local_bisnes',
                'action' => 'del',
            ],
            [
                'subject' => 'local_bisnes',
                'action' => 'edit',
            ],

            [
                'subject' => 'film',
                'action' => 'add',
            ],
            [
                'subject' => 'film',
                'action' => 'del',
            ],
            [
                'subject' => 'film',
                'action' => 'edit',
            ],

            [
                'subject' => 'film_category',
                'action' => 'add',
            ],
            [
                'subject' => 'film_category',
                'action' => 'del',
            ],
            [
                'subject' => 'film_category',
                'action' => 'edit',
            ],

            [
                'subject' => 'film_teg',
                'action' => 'add',
            ],
            [
                'subject' => 'film_teg',
                'action' => 'del',
            ],
            [
                'subject' => 'film_teg',
                'action' => 'edit',
            ],

            [
                'subject' => 'spot_region',
                'action' => 'add',
            ],
            [
                'subject' => 'spot_region',
                'action' => 'del',
            ],
            [
                'subject' => 'spot_region',
                'action' => 'edit',
            ],
        );

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                [                    
                    'subject' => $permission['subject'],
                    'action' => $permission['action'],
                ],
            ]);
        }

    }
}
