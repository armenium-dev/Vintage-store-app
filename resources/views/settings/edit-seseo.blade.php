@extends('layouts.app',['title' => 'Edit Ship Engine Jersey Type Options'])
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 text-left">
                <h4>Ship Engine Jersey Type Options:</h4>
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
                <table id="js_multi_data_table" class="table table-striped shipto">
                        <tr>
                            <th>Option title</th>
                            <th>Cost coefficient</th>
                            <th>Status<br><small>(uncheck if you want to exclude from the form)</small></th>
                            <th>Action</th>
                        </tr>
                    @foreach($json_data as $k => $v)
                        <tr>
                            <td><input type="text" class="form-control" name="value[{{$k}}][title]" placeholder="Option title" value="{{$v['title']}}"></td>
                            <td><input type="number" class="form-control" name="value[{{$k}}][cost]" placeholder="Cost coefficient" value="{{$v['cost']}}" step="any"></td>
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

                    $tr.append('<td><input type="text" class="form-control" name="value['+key+'][title]" placeholder="Option title" value=""></td>');
                    $tr.append('<td><input type="number" class="form-control" name="value['+key+'][cost]" placeholder="Digit only" value="" step="any"></td>');
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
