@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }} <a href="{{route('editEmail')}}" style="float: right;">Update Email</a></div>

                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                <div class="card-body">
                    <h4>{{$message}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
   setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 3000);
    </script>
