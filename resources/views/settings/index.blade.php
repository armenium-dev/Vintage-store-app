<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row flex-nowrap align-middle justify-between">
            <div class="flex-auto"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Settings') }}</h2></div>
            <div class="flex-none">
                <a href="{{ route('settings.create') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 shadow-sm">
                    <svg width="20" height="20" fill="currentColor" class="mr-2" aria-hidden="true">
                        <path d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z" />
                    </svg>
                    Add New
                </a>
            </div>
        </div>
        <x-alert type="error" :message="session('status')"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table id="table" class="table-auto settings">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Updated</th>
                                <th data-sortable="false">Edit</th>
                                <th data-sortable="false">View</th>
                                <th data-sortable="false">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($settings as $setting)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $setting->name }}</td>
                                <td>{{ $setting->value }}</td>
                                <td>{{ $setting->updated_at->format('M d, Y') }}</td>
                                <td><a href="{{route('settings.edit', $setting->id)}}" class="btn btn-secondary"><i class="fa fa-edit"></i></a></td>
                                <td><a href="{{route('settings.show', $setting->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                                <td>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal_{{$setting->id}}" title="Delete"><i class="fa fa-trash"></i></button>
                                    <div id="myModal_{{$setting->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-dark text-center">
                                                    <h4 class="modal-title text-light">You want delete this Option?</h4>
                                                    <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('settings.destroy', $setting->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="row">
                                                            <div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Delete</button></div>
                                                            <div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    Empty
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-center">
                    <h4 class="modal-title text-light">You want delete this Design?</h4>
                    <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body">
                        <h5 id="reference_id" class="text-center mt-3 mb-4">Reference # <span></span></h5>
                        @csrf
                        @method('DELETE')
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Delete</button></div>
                            <div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @section('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#table').DataTable({
                    "order": [[0, "desc"]],
                    "pageLength": 200,
                    "lengthMenu": [[10, 25, 50, 75, 100, 200, -1], [10, 25, 50, 75, 100, 200, "All"]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "/settings/parts"
                });

                $(document).on('click', '.btn-remove', function(e){
                    var action = $(this).data('action'),
                        $target = $($(this).data('target')),
                        reference_id = $(this).data('reference_id');
                    $target
                        .find('form').attr('action', action)
                        .end()
                        .find('#reference_id').find('span').text(reference_id);

                });
            });
        </script>
    @endsection

</x-app-layout>
