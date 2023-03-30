@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary actionbtn">
            Create Subscriber
        </a>
        @if (count($subscribers) == 0)
            <p>No records</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Email</th>
                        <th scope="col">Name</th>
                        <th scope="col">Country</th>
                        <th scope="col">Subscription date</th>
                        <th scope="col">Subscription time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscribers as $key => $subscriber)
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    @endif
@endsection
