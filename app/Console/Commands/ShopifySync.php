<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SyncShopifyController;
use App\Http\Controllers\ProductsController;

class ShopifySync extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'shopifysync:run';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Shopify Sync';

    private $ActionController;

	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct(SyncShopifyController $AC){
        $this->ActionController = $AC;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug('---------- Begin '.$this->description.' ----------');

		$result = $this->ActionController->syncShopsProducts();

		Log::stack(['cron'])->debug('Sync result');
		Log::stack(['cron'])->debug($result);

		Log::stack(['cron'])->debug('---------- End '.$this->description.' ----------');
	}


}
