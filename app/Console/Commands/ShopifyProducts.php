<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SyncShopifyController;

class ShopifyProducts extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'shopifyproducts:run';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Shopify Products';

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
		Log::stack(['cron'])->debug('---------- Begin '.$this->description.' ----------');

		$result = $this->ShopifyController->getDepopAsosSalesProducts();
		Log::stack(['cron'])->debug('Depop and Asos Sales result');
		Log::stack(['cron'])->debug($result);

		$result = $this->ShopifyController->turnOffShopifyProducts();
		Log::stack(['cron'])->debug('Turn off result');
		Log::stack(['cron'])->debug($result);

		Log::stack(['cron'])->debug('---------- End '.$this->description.' ----------');
	}


}
