@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary actionbtn">
        Create Subscriber
    </a>

    <div class="container">
        <div class="card">
            <div class="card-body">

                @if (count($subscribers) != 0)
                    <table class="table table-dark" id="main-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscribers as $key => $subscriber)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $subscriber['name'] }}</td>
                                    <td>

                                        <a href="{{ route('admin.subscribers.show', $subscriber->id) }}" title="Show"
                                            class="">
                                            Show</a>

                                        <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" title="Edit"
                                            class="">
                                            Edit</a>

                                        <form action="{{ route('admin.subscribers.destroy', $subscriber->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf {{ method_field('Delete') }}

                                            <button type="sumbit" class="btn-looklike-link" title=Delete
                                                onclick="return confirm('Are you sure?')"> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $categories->render() !!}

                    </div>
                @else
                    No records
                @endif

            </div>
        </div>
    </div>

@endsection
