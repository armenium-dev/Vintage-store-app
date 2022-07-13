<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Http\Helpers\Parser;

class Depop extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'depop:run';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Parse Depop csv file';

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

		$result = $this->Parser->runParsing('csv');

		Log::stack(['cron'])->debug('Parse result = '.$result);

		Log::stack(['cron'])->debug('---------- End '.$this->description.' ----------');
	}


}
