@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Perbarui informasi akun dan password')

@section('content')
    <div class="animate-fadeIn">
        <div class="row justify-content-center g-4">
            <div class="col-12 col-xl-10">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-10">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-10">
                <div class="card border-danger-subtle">
                    <div class="card-body p-4 p-md-5">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
