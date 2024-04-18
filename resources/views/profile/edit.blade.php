@extends('app', ['user' => $user])

@section('content')
    <h1>Your Profile</h1>

    <h3>Your Details</h3>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <table class="table table-striped">
            <tr>
                <td><strong>Name</strong></td>
                <td><input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" required><x-input-error class="p-3 mb-2 bg-danger text-white" :messages="$errors->get('name')" /></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control" required><x-input-error class="p-3 mb-2 bg-danger text-white" :messages="$errors->get('email')" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-success"></td>
            </tr>
        </table>
        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="p-3 mb-2 bg-success text-white"
            >{{ __('Saved.') }}</p>
        @endif
    </form>

    <h3>Update Password</h3>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <table class="table table-striped">
            <tr>
                <td><strong>Current Password</strong></td>
                <td><input type="password" name="current_password" id="current_password" class="form-control" required> <x-input-error :messages="$errors->updatePassword->get('current_password')" class="p-3 mb-2 bg-danger text-white" /></td>
            </tr>
            <tr>
                <td><strong>New Password</strong></td>
                <td><input type="password" name="password" id="password" class="form-control" required><x-input-error :messages="$errors->updatePassword->get('password')" class="p-3 mb-2 bg-danger text-white" /></td>
            </tr>
            <tr>
                <td><strong>Confirm Password</strong></td>
                <td><input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required><x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="p-3 mb-2 bg-danger text-white" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-success"></td>
            </tr>
        </table>


            @if (session('status') === 'password-updated')
                <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="p-3 mb-2 bg-success text-white"
                >{{ __('Saved.') }}</p>
            @endif
    </form>

    @stop
