@extends('app', ['user' => $user])

@section('content')
    <h1>Company Edit</h1>
    <form method="POST" action="{{ route('company.update', ['id' => $company->companyid]) }}">
        @csrf
        @method('PATCH')
    <table class="table table-striped">
        <tr>
            <td>Company Name: </td>
            <td><input type="text" name="companyname" value="{{ $company->companyname }}" class="form-control" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-primary"></td>
        </tr>
    </table>
    </form>
@stop
@section('javascript')

@stop
