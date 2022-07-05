<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Http\Controllers\ShopifyController;

class SyncShopifyProducts extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'syncsopifyproducts:run';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Sync Shopify Products';

    private $ShopifyController;

	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct(ShopifyController $SC){
        $this->ShopifyController = $SC;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug(__CLASS__.'::'.__FUNCTION__.' - RUN');

		$sync_products = $this->ShopifyController->getSyncShopifyProducts();
		$products_count = count($sync_products);

		Log::stack(['cron'])->debug('Sync Shopify Products Count = '.$products_count);

		if($products_count > 0){
			foreach($sync_products as $product){
				if(Product::where('id', $product['id'])->update(['name' => $product['shopify_name']])){
					Log::stack(['cron'])->debug('Updatet Product ID = '.$product['id']);
				}else{
					Log::stack(['cron'])->debug('Error Product ID = '.$product['id']);
				}

			}
		}

		Log::stack(['cron'])->debug('---------- End Sync Shopify Products ----------');
	}


}
