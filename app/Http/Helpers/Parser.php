<?php
namespace App\Http\Helpers;

use App\Models\Uploads;
use Illuminate\Support\Facades\Log;

class Parser {

	public function runParsing($file_type): int{
		$res = 0;

		$uploads = Uploads::where(['parsed' => 0, 'file_type' => $file_type])->pluck('file', 'id')->all();

		if(!empty($uploads)){
			foreach($uploads as $id => $file){
				$file_path = storage_path('app/'.$file);
				if($content = $this->ParseFile($file_type, $file_path)){
					$res++;
					Uploads::find($id)->update(['parsed' => 1, 'content' => json_encode($content)]);
				}
			}
		}

		return $res;
	}

	private function ParseFile($type, $file_path): array{
		return match($type){
			"csv" => $this->parseCsv($file_path),
			"html" => $this->parseHtml($file_path),
			default => [],
		};
	}

	private function parseCsv($file_path): array{
		$res = [];
		Log::stack(['cron'])->debug($file_path);

		if(!file_exists($file_path)) return $res;

		$content = file_get_contents($file_path);

		if(!empty($content)){
			$content .= "\n\r";

			$output_array = [];
			preg_match_all('/Ref[\s]?\-([\s\S]*?(?=([\n\r|\}])))/', $content, $output_array);
			/*if(empty($output_array[0])){
				$output_array = [];
				preg_match_all('/Ref[\s]?\-([\s\S]*?(?=([\s|\}])))/', $content, $output_array);
			}*/
			if(!empty($output_array[0])){
				foreach($output_array[0] as $k => $v){
					$pos = strpos($v, '"');
					if($pos !== false){
						$v = substr($v, 0, $pos);
					}
					$v = trim($v);
					$res[] = $v;
				}
			}

			$output_array = [];
			preg_match_all('/Ref[\s]?\-([\s\S]*?(?=([\s|\}])))/', $content, $output_array);
			if(!empty($output_array[0])){
				foreach($output_array[0] as $k => $v){
					$pos = strpos($v, '"');
					if($pos !== false){
						$v = substr($v, 0, $pos);
					}
					$v = trim($v);
					if(!in_array($v, $res)){
						$res[] = $v;
					}
				}
			}


		}
		Log::stack(['cron'])->debug($res);

		return $res;
	}

	private function parseHtml($file_path): array{
		$res = [];
		#Log::stack(['cron'])->debug($file_path);

		if(!file_exists($file_path)) return $res;

		$content = file_get_contents($file_path);

		if(!empty($content)){
			$output_array = [];
			preg_match_all('/((http|https):\/\/(marketplace.asos.com\/listing\/)([\w\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?)/', $content, $output_array);
			if(!empty($output_array[0])){
				$res = array_values(array_unique($output_array[0]));
			}else{
				$output_array = [];
				preg_match_all('/((\/listing\/)([\w\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?)/', $content, $output_array);
				#Log::stack(['cron'])->debug($output_array);
				if(!empty($output_array[0])){
					$res = array_values(array_unique($output_array[0]));
				}
			}
		}

		return $res;
	}

	public function getVCUKtag($content): string{
		$res = '';

		if(!empty($content)){
			$content = strip_tags($content);

			$content .= "\n\r";

			$output_array = [];
			preg_match('/VCUK[\s]?([\s\S]*?(?=([\n\r|\}])))/', $content, $output_array);
			if(!empty($output_array[0])){
				$res = $output_array[0];
			}else{
				$output_array = [];
				preg_match('/TV[\s]?([\s\S]*?(?=([\n\r|\}])))/', $content, $output_array);
				if(!empty($output_array[0])){
					$res = $output_array[0];
				}
			}
		}

		return $res;
	}
}

