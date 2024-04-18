@extends('app', ['user' => $user])

@section('content')
    <h1>User Edit</h1>
    <form method="POST" action="/user/{{ $userdetails->id }}">
        @csrf
        @method('PATCH')
        <table class="table table-striped">
            <tr>
                <td>Name: </td>
                <td><input type="text" name="username" value="{{ $userdetails->name }}" class="form-control" required></td>
            </tr>
            <tr>
                <td>Email: </td>
                <td><input type="text" name="useremail" value="{{ $userdetails->email }}" class="form-control" required></td>
            </tr>
            <tr>
                <td>Password:<br><em>Only enter if updating</em> </td>
                <td><input type="text" name="userpassword"  class="form-control"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-primary"></td>
            </tr>
        </table>
    </form>
@stop
@section('javascript')

@stop
