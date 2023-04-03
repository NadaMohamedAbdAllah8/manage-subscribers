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
                        <input type="text" placeholder="Enter Subscriber Name" name="name" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="username"><b>Subscriber Country</b></label>
                        <input type="text" placeholder="Enter Subscriber Country" name="country" required>
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
