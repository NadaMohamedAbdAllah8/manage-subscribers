@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="container">
        <div class="formdiv">
            <form action="{{ route('admin.subscribers.update', $id) }}" method="POST">
                @csrf {{ method_field('PUT') }}
                <h1>Edit Subscriber</h1>
                <hr>

                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="username"><b>Subscriber Name</b></label>
                        <input type="text" placeholder="Enter Subscriber Name" name="name" required
                            value="{{ $subscriber['name'] }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="username"><b>Subscriber Country</b></label>
                        <input type="text" placeholder="Enter Subscriber Country" name="country" required
                            value="{{ $subscriber['country'] }}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="username"><b>Subscriber email</b></label>
                        <input type="text" class="read-only-input" value="{{ $subscriber['email'] }}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="username"><b>Subscription date</b></label>
                        <input type="text" class="read-only-input" value="{{ $subscriber['subscription_date'] }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="username"><b>Subscription time</b></label>
                        <input type="text" class="read-only-input" value="{{ $subscriber['subscription_time'] }}">
                    </div>
                </div>

                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-primary actionbtn">
                    Back
                </a>
                <button type="submit" class="actionbtn btn btn-primary">Edit</button>

            </form>
        </div>
    </div>
@endsection
