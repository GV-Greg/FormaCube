@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center text-center">
        <div class="col-lg-6 bg-light mt-5" style="border-radius: 40px;">
            <div id="app" class="p-3">
                <div class="img justify-content-center">
                    <img id="logo" src="../images/interface3-logo.png" alt="logo interface3" class="img-fluid mx-auto d-block p-5" style="width:80%">
                </div>
                <div class="field px-5 pb-3">
                    <login-form></login-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
