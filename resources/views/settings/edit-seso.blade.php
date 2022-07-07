@extends('layouts.app',['title' => 'Edit Ship Engine Services Options'])
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 text-left">
                <h4>Ship Engine Services Options:</h4>
            </div>
            <div class="col-md-4 text-right">
                <h4>Edit mode</h4>
            </div>
        </div>
        <hr>
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('settings.update',$settings->id) }}" method="POST">
            @csrf
            <input name="_method" type="hidden" value="PATCH">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Option name" value="{{$settings->name}}">
            </div>
            <div class="form-group">
                <label for="name">Value:</label>
                <table id="js_multi_data_table" class="table table-striped">
                        <tr>
                            <th>Service name description<br><small>(For example: Canada Post)</small></th>
                            <th>Service name<br><small>(json key: service_type)</small></th>
                            <th>Service code<br><small>(json key: service_code)</small></th>
                            <th>Custom rate<br><small>(Enter your custom rate without dollar sign)</small></th>
                            <th>Transit time<br><small></small></th>
                            <th>Status<br><small>(uncheck if you want to exclude from the results)</small></th>
                            <th>Action<br><small></small></th>
                        </tr>
                    @foreach($json_data as $k => $v)
                        <tr>
                            <td><input type="text" class="form-control" name="value[{{$k}}][desc]" placeholder="Custom Description" value="{{$v['desc']}}"></td>
                            <td><input type="text" class="form-control" name="value[{{$k}}][type]" placeholder="service_type" value="{{$v['type']}}"></td>
                            <td><input type="text" class="form-control" name="value[{{$k}}][code]" placeholder="service_code" value="{{$v['code']}}"></td>
                            <td><input type="number" class="form-control" name="value[{{$k}}][rate]" placeholder="Digit only" value="{{$v['rate']}}"></td>
                            <td>
                                <select class="form-control" name="value[{{$k}}][transit_time]">
                                    <option value="bday" @if(isset($v['transit_time']) && $v['transit_time'] == 'bday') selected="selected" @endif>Business days</option>
                                    <option value="cday" @if(isset($v['transit_time']) && $v['transit_time'] == 'cday') selected="selected" @endif>Calendar days</option>
                                </select>
                            </td>
                            <td><input type="checkbox" class="form-control" name="value[{{$k}}][status]" value="1" {{$v['status'] ? 'checked' : ''}}></td>
                            <td><a role="button" class="js_remove_row btn btn-danger btn-remove"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <a href="#" role="button" id="js_add_row" class="btn btn-secondary">Add new row</a>
            <button class="btn btn-primary" type="submit">Submit</button>
            <a href="{{route('settings.index')}}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($){

            var BJS = {
                vars: {
                    last_key: 0,
                },
                els: {
                    js_multi_data_table: $('#js_multi_data_table'),
                },
                Init: function(){
                    this.initEvents();
                    this.vars.last_key = this.els.js_multi_data_table.find('tr').length;
                },
                initEvents: function(){
                    $(document)
                        .on('click', '.js_remove_row', BJS.remove_row)
                        .on('click', '#js_add_row', BJS.add_row);
                },
                remove_row: function(){
                    var $btn = $(this);
                    $btn.parents('tr').remove();
                },
                add_row: function(){
                    var key = (BJS.vars.last_key + 1),
                        $tr = $('<tr>');

                    $tr.append('<td><input type="text" class="form-control" name="value['+key+'][desc]" placeholder="Custom Description" value=""></td>');
                    $tr.append('<td><input type="text" class="form-control" name="value['+key+'][type]" placeholder="service_type" value=""></td>');
                    $tr.append('<td><input type="text" class="form-control" name="value['+key+'][code]" placeholder="service_code" value=""></td>');
                    $tr.append('<td><input type="number" class="form-control" name="value['+key+'][rate]" placeholder="Digit only" value=""></td>');
                    $tr.append('<td><select class="form-control" name="value['+key+'][transit_time]"><option value="bday">Business days</option><option value="cday">Calendar days</option></select></td>');
                    $tr.append('<td><input type="checkbox" class="form-control" name="value['+key+'][status]" value="1" checked></td>');
                    $tr.append('<td><a role="button" class="js_remove_row btn btn-danger btn-remove"><i class="fa fa-trash"></i></a></td>');

                    BJS.els.js_multi_data_table.append($tr);

                    BJS.vars.last_key = key;
                },
            };

            BJS.Init();

        });
    </script>
@endsection
