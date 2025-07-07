@extends('layouts.app')

@section('content')
<div class="container py-3 py-md-4"> {{-- Added vertical padding --}}
    <div class="row justify-content-center">
        <div class="col-11 col-md-10 col-lg-8"> {{-- Responsive columns --}}
            <div class="card">
                <div class="card-header h5 py-3">{{ __('Verify Your Email Address') }}</div> {{-- Better header sizing --}}

                <div class="card-body p-3 p-md-4"> {{-- Adjusted padding --}}
                    @if (session('resent'))
                        <div class="alert alert-success mb-4" role="alert"> {{-- Added margin --}}
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="mb-3">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    <p class="mb-4">{{ __('If you did not receive the email') }},</p> {{-- Added margin --}}
                    
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 d-block w-100 text-center"> {{-- Full width on mobile --}}
                            {{ __('click here to request another') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection