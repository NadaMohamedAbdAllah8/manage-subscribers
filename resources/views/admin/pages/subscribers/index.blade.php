@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title ?? 'Subscribers' }}
@endsection

@section('content')
    @if ($message)
        <div class="alert alert-danger text-center" style="">
            {!! $message !!}
        </div>
    @endif
    <div class="container">
        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary actionbtn"
            style="margin-bottom:3rem; margin-top:2rem;width:20rem; ">
            Create Subscriber
        </a>

        <table class="table yajra-datatable table-striped" id="subscribers">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Name</th>
                    <th scope="col">Country</th>
                    <th scope="col">Subscription date</th>
                    <th scope="col">Subscription time</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#subscribers').DataTable({
            "processing": true,
            "serverSide": true,
            'paging': true,
            'info': true,
            "ajax": "{{ route('admin.subscribers.data') }}",
            // "language": {
            //     "emptyTable": "No data available in table. Check back later"
            // },
            "columnDefs": [{
                    "targets": 0, // target the first column (id)
                    "data": "id",
                },
                {
                    "targets": 1, // target the second column (email)
                    "render": function(data, type, row) {
                        let url = "{{ route('admin.subscribers.edit', 'id') }}";
                        let id = (row['id']).replace(/"/g, '');
                        url = url.replace('id', id);
                        return '<a  href=' + url + '>' + row['email'];
                    }
                },
                {
                    "targets": 2, // target the third column (name)
                    "data": "name"
                },
                {
                    "targets": 3, // target the fourth column (country)
                    "data": "country"
                },
                {
                    "targets": 4, // target the fifth column (subscription_date)
                    "data": "subscription_date"
                },
                {
                    "targets": 5, // target the sixth column (subscription_time)
                    "data": "subscription_time"
                },
                {
                    "targets": 6, // target the seventh column (action buttons)
                    "data": null,
                    "render": function(data, type, row) {
                        let url = "{{ route('admin.subscribers.edit', 'id') }}";
                        let id = (row['id']).replace(/"/g, '');
                        url = url.replace('id', id);
                        return '<a class="btn btn-primary"  href=' + url +
                            '><i class="fa fa-edit"></i></a> <a style="color:#fff" class="btn btn-danger delete" data-content="' +
                            id + '"><i class="fa fa-trash"></i></a>';
                    },
                    "sortable": false,
                    "searchable": false,
                },
            ],
        });

        // delete subscriber data
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $("#subscribers").on('click', '.delete', function() {
            let content = $(this).data("content");
            let delete_url = "{{ route('admin.subscribers.destroy', 'id') }}";
            delete_url = delete_url.replace('id', content);
            $.ajax({
                url: delete_url,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id: content,
                    _method: "delete"
                },
                dataType: 'JSON',
                beforeSend: function() {},
                success: function(response) {
                    $("#subscribers").DataTable().ajax.reload();
                    swal({
                        title: "Deleted",
                        text: "Deleted successfully",
                        icon: "success",
                        buttons: false,
                        timer: 1700
                    });
                },
                error: function(response) {
                    let errorObj = JSON.parse(response.responseText);
                    let error = errorObj.error;
                    // let id = errorObj.id;
                    // console.log('returned id=' + id);
                    swal({
                        title: "Error",
                        text: error,
                        icon: "error",
                        buttons: false,
                        timer: 1700
                    });
                }
            });
        });
    </script>
@endsection
