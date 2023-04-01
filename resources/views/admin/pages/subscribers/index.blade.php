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
        console.log('hi');

        $('#subscribers').DataTable({
            "processing": true,
            "serverSide": true,
            'paging': true,
            'info': true,
            "ajax": "{{ route('admin.subscribers.data') }}",
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "name"
                },
                {
                    "data": "country"
                },
                {
                    "data": "subscription_date"
                },
                {
                    "data": "subscription_time"
                },
                {
                    "mRender": function(data, type, row) {
                        var url = "{{ route('admin.subscribers.edit', 'id') }}";
                        url = url.replace('id', row['id']);
                        console.log(' about to view');
                        return '<a class="btn btn-primary"  href=' + url +
                            '><i class="fa fa-edit"></i></a> <a style="color:#fff" class="btn btn-danger delete" data-content="' +
                            row['id'] + '"><i class="fa fa-trash"></i></a>';
                    },
                    sortable: false,
                    searchable: false,
                },
            ]
        }).ajax.reload();

        // Delete subscriber data
        // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        // $("#subscribers").on('click', '.delete', function() {
        //     Swal.fire({
        //         title: 'Are you sure to delete this subscriber data?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             var content = $(this).data("content");
        //             var urls = "{{ route('admin.subscribers.destroy', 'id') }}";
        //             urls = urls.replace('id', content);
        //             $.ajax({
        //                 url: urls,
        //                 method: 'POST',
        //                 data: {
        //                     _token: CSRF_TOKEN,
        //                     id: content,
        //                     _method: "delete"
        //                 },
        //                 dataType: 'JSON',
        //                 beforeSend: function() {},
        //                 success: function(data) {
        //                     $("#subscribers").DataTable().ajax.reload();
        //                     Swal.fire(
        //                         'Deleted!',
        //                         'subscriber has been deleted.',
        //                         'success'
        //                     )
        //                 },
        //                 error: function(data) {
        //                     $("#subscribers").DataTable().ajax.reload();
        //                 }

        //             });
        //         }
        //     });
        // });
    </script>
@endsection
