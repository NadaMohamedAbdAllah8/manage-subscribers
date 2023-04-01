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
        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary actionbtn">
            Create Subscriber
        </a>
        @if (isset($subscribers) && count($subscribers) == 0)
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
                            <td>{{ $subscriber['email'] }}</td>
                            <td>{{ $subscriber['name'] }}</td>
                            <td>{{ $subscriber['country'] }}</td>
                            <td>{{ $subscriber['subscription_date'] }}</td>
                            <td>{{ $subscriber['subscription_time'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    @endif
@endsection
