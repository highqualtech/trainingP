@extends('app', ['user' => $user])

@section('content')
    <h1>CSV Import</h1>
    @if (session('status') === 'csvimportcomplete')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">CSV Imported</p>
    @endif
    <a href="/csvimport.csv" target="_blank">Download Template</a><br><br>

    <form method="POST" action="{{ route('participant.csvimportprocess') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <table class="table table-striped">
            <tr>
                <td>Import CSV</td>
                <td><input type="file" name="csvfile" class="form-control" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" class="btn btn-success" value="Import"></td>
            </tr>
        </table>
@stop
