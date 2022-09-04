<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $settings = Settings::query()->orderBy('id', 'desc')->get();

        return view('settings.index', ['settings' => $settings]);
    }

    public function parts(Request $request){
        $sort_cols = [
            0 => 'id',
            1 => 'name',
            2 => 'value',
            3 => 'updated_at',
        ];
        #dd($request);

        $query = Settings::query();

        $query->select('*');

        if(isset($request->search)){
            if(!empty($request->search['value'])){

                $phrase      = $request->search['value'];
                $like_phrase = "%".$phrase."%";

                $query->where('id', '=', $phrase);
                $query->orWhere('name', 'like', $like_phrase);
                $query->orWhere('value', 'like', $like_phrase);
            }
        }

        if(isset($request->order)){
            foreach($request->order as $order){
                $query->orderBy($sort_cols[$order['column']], $order['dir']);
            }
        }

        $query->offset($request->start);
        $query->limit($request->length);

        #dd($query->toSql());

        $data        = $query->get();
        $total_count = $query->getQuery()->getCountForPagination();

        $settings = [];

        if($data){
            foreach($data->all() as $item){
                $settings[] = [
                    $item->id,
                    ucfirst(str_replace('_', ' ', $item->name)),
                    $item->value,
                    $item->updated_at->format('M d, Y'),
                    '<a href="'.route('settings.edit', $item->id).'" class="btn btn-secondary"><i class="fa fa-edit"></i></a>',
                    '<a href="'.route('settings.show', $item->id).'" class="btn btn-primary"><i class="fa fa-eye"></i></a>',
                    '<button class="btn btn-danger btn-remove" data-reference_id="'.$item->id.'" data-toggle="modal" data-target="#myModal" data-action="'.route('settings.destroy', $item->id).'" title="Delete"><i class="fa fa-trash"></i></button>',
                ];
            }
        }

        $data = [
            'draw'            => $request->draw,
            'recordsTotal'    => $total_count,
            'recordsFiltered' => $total_count,
            'data'            => $settings,
        ];


        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        Settings::create($request->all());

        return redirect('settings')->with('status', 'Setting Created');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Settings $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Settings $settings){
        $data = ['model' => $settings];

        return view('settings.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Settings $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings){
        $form = 'settings.edit';
        $json_data = [];

        return view($form, ['settings' => $settings, 'json_data' => $json_data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Settings $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settings $settings){
        $request_data = $request->all();
        $values = $request_data['value'];
        #dd($request_data);

        switch($request_data['name']){
            case 'ship_engine_jersey_type_options':
                foreach($values as $k => $v){
                    if(is_null($v['cost'])){
                        $request_data['value'][$k]['cost'] = 1;
                    }else{
                        $request_data['value'][$k]['cost'] = floatval($request_data['value'][$k]['cost']);
                    }
                }
                break;
            case 'ship_engine_services_options':
                foreach($values as $k => $v){
                    if(is_null($v['rate'])){
                        $request_data['value'][$k]['rate'] = 0;
                    }else{
                        $request_data['value'][$k]['rate'] = floatval($request_data['value'][$k]['rate']);
                    }
                    if(!isset($v['status'])){
                        $request_data['value'][$k]['status'] = 0;
                    }else{
                        $request_data['value'][$k]['status'] = intval($request_data['value'][$k]['status']);
                    }
                }
                break;
        }

        if(is_array($request_data['value'])){
            #dd($request_data);
            $request_data['value'] = json_encode($request_data['value']);
        }

        $settings->update($request_data);

        return redirect('settings/'.$settings->id.'/')->with('status', 'Setting updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Settings $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settings $settings){
        $settings->delete();

        return redirect('design')->with('status', 'Setting Destroyed');
    }

	public function resyncData($redirect = true){
		Settings::where('name', 'like', '%_sync_since_id')->update([
			'value' => 0,
			'active' => 1,
		]);
		
		if($redirect)
			return redirect('dashboard');
	}
	
	public function resyncDataFull($redirect = true){
		DB::statement('TRUNCATE TABLE variants');
		DB::statement('TRUNCATE TABLE products');
		DB::statement('TRUNCATE TABLE tags');

		$this->resyncData(false);

		if($redirect)
			return redirect('dashboard');
	}
	
	public function resetApp(){
		DB::statement('TRUNCATE TABLE mystery_boxes');
		DB::statement('TRUNCATE TABLE mystery_box_products');
		DB::statement('TRUNCATE TABLE orders');
		DB::statement('TRUNCATE TABLE products');
		DB::statement('TRUNCATE TABLE products_custom');
		DB::statement('TRUNCATE TABLE sales');
		DB::statement('TRUNCATE TABLE tags');
		DB::statement('TRUNCATE TABLE uploads');
		DB::statement('TRUNCATE TABLE variants');
		
		return redirect('dashboard');
	}
	
}
