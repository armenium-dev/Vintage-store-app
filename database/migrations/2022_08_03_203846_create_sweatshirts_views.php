<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration{
	
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		$_sql = "SELECT
                    p.id,
                    p.shop_id,
                    p.product_id,
                    v.variant_id,
                    p.title AS product_title,
                    v.title AS variant_title,
                    v.inventory_quantity,
                    v.price,
                    v.size,
                    v.color,
                    p.image
				FROM products p
				LEFT JOIN variants v ON p.product_id = v.product_id
				WHERE p.is_mystery = 0
				  AND p.status = 'active'
				  AND (p.link_asos != '' OR p.link_depop != '')
				  AND v.inventory_quantity = 1
				  AND v.price = 12
				  AND p.title LIKE '%Sweatshirt%'
				  AND (p.body LIKE '%A* Vintage Quality%' OR body LIKE '%A Vintage Quality%')
				ORDER BY v.product_id ASC;";
		
		#DB::connection()->getPdo()->exec("CREATE VIEW sweatshirts AS $_sql");
		#DB::statement("CREATE ALGORITHM=UNDEFINED DEFINER=`".env('DB_USERNAME')."`@`localhost` SQL SECURITY DEFINER VIEW sweatshirts AS $_sql");
		DB::statement("CREATE VIEW sweatshirt_items AS $_sql");
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		#Schema::dropView('sweatshirts');
		DB::statement('DROP VIEW sweatshirt_items');
	}
};
