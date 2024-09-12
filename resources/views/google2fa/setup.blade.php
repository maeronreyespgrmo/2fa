@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Set up Google Authenticator</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{$errors->first()}}
                        </div>
                    @endif
                    @if(Auth::user()->google2fa_secret)
                    <p>
                        You already have enabled your two-factor authenticator.
                    </p>
                    <form class="form-inline" method="post" action="/2fa-setup">
                        @csrf
                        <input class="form-control mb-2" type="hidden" id="secret" name="secret" value="disable" readonly/>
                        <button class="btn btn-danger mb-2" type="submit">Disable Two-Factor Authentication</button>
                    </form>
                    @else
                    <p>
                        Set up your two-factor authentication by scanning the barcode below.<br>
                        Alternatively, you can use the code: <b>{{ $secret }}</b>
                    </p>
                    <div>
                        {!! $qr !!}
                    </div>
                    <div class="alert alert-warning" role="alert">
                        You must set up your <b>Google Authenticator</b> app before continuing. You will be unable to login otherwise.
                    </div>
                    <div>
                        <form class="form-inline" method="post" action="/2fa-setup">
                            @csrf
                            <input class="form-control mb-2" type="hidden" id="secret" name="secret" value="{{ $secret }}" readonly/>
                            <button class="btn btn-primary mb-2" type="submit">Enable Two-Factor Authentication</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
