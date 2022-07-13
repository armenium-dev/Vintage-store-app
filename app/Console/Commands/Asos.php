<?php


namespace App\Console\Commands;

use App\Http\Helpers\Parser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class Asos extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'asos:run';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Parse Asos html document';

    private $Parser;

	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct(Parser $p){
		$this->Parser = $p;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug('---------- Begin '.$this->description.' ----------');

		$result = $this->Parser->runParsing('html');

		Log::stack(['cron'])->debug('Parse result: '.$result);

		Log::stack(['cron'])->debug('---------- End '.$this->description.' ----------');
	}


}
