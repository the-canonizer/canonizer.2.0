<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NameSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$namespaceArray = array(
            [
            'parent_id' => 0,
            'name' => 'Genaral',
            'label' => 'Genaral',
        ],[
            'parent_id' => 0,
            'name' => 'corporations',
            'label' => '/corporations/',
        ],[
            'parent_id' => 0,
            'name' => 'crypto_currency',
            'label' => '/crypto_currency/',
        ],
		[
            'parent_id' => 0,
            'name' => 'family',
            'label' => '/family/',
        ],
		[
            'parent_id' => 3,
            'name' => 'Jesperson_Oscar_F',
            'label' => '/family/Jesperson_Oscar_F/',
        ]
		,[
            'parent_id' => 0,
            'name' => 'Occupy Wall Street',
            'label' => '/Occupy Wall Street/',
        ]
		,[
            'parent_id' => 0,
            'name' => 'organizations',
            'label' => '/organizations/',
        ],
		[
            'parent_id' => 6,
            'name' => 'canonizer',
            'label' => '/organizations/canonizer/',
        ],
		[
            'parent_id' => 7,
            'name' => 'help',
            'label' => '/organizations/canonizer/help/',
        ],
		[
            'parent_id' => 6,
            'name' => 'mta',
            'label' => '/organizations/mta/',
        ],
		[
            'parent_id' => 6,
            'name' => 'TV07',
            'label' => '/organizations/TV07/',
        ],
		[
            'parent_id' => 6,
            'name' => 'wta',
            'label' => '/organizations/wta/',
        ],
		[
            'parent_id' => 0,
            'name' => 'personal_attributes',
            'label' => '/personal_attributes/',
        ],
		[
            'parent_id' => 0,
            'name' => 'personal_reputations',
            'label' => '/personal_reputations/',
        ],
		[
            'parent_id' => 0,
            'name' => 'professional_services',
            'label' => '/professional_services/',
        ],
		[
            'parent_id' => 0,
            'name' => 'sandbox',
            'label' => '/sandbox/',
        ],
		[
            'parent_id' => 0,
            'name' => 'terminology',
            'label' => '/terminology/',
        ],
		[
            'parent_id' => 0,
            'name' => 'www',
            'label' => '/www/',
        ],
		);
		foreach($namespaceArray as $data) {
          DB::table('namespace')->insert($data);
		}
    }
}
