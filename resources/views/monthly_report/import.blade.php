@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Import Monthly Report</h2>

    <form action="{{ route('monthly.report.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Pilih file</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
@endsection
