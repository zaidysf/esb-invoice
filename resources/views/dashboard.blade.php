@extends('layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
.title-link h1 {
    color: blue;
}
</style>
@endsection

@section('script')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
@endsection
