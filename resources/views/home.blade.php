@extends('layouts.app')

@section('content')
<div class="container">
    
        
            {{-- <div class="card"> --}}
                {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                {{-- <div >
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif --}}

                    {{-- {{ __('You are logged in!') }} --}}
                {{-- </div> --}}
            {{-- </div> --}}

                    <task-app>
                        
                    </task-app>
       
</div>
@endsection

