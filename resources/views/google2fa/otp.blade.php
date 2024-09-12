@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">One Time Password</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{$errors->first()}}
                        </div>
                    @endif
                    <p>
                        Get your One Time Password in your <b>Google Authenticator</b> app.
                    </p>
                    <form class="form-inline" method="post" action="/otp">
                        @csrf
                        <input class="form-control mb-2" type="number" id="otp" name="otp" />
                        <button class="btn btn-primary mb-2" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
