@extends('layout')
@section('content')
    <div class="mb-3">
        <a href="{{ route('items.index') }}" class="title-link"><h1 class="h5 d-inline align-middle">&larr; Back to list</h1></a><br/>
        <h1 class="h3 d-inline align-middle">{{ $label }}</h1>

        @if ($method == 'add')
        <a href="#" id="btn-save" class="btn btn-outline-success float-end">Save</a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-danger float-end" style="margin-right: 5px;">Cancel</a>
        @else
        <a href="#" id="btn-edit" class="btn btn-warning float-end">Edit</a>
        <a href="#" id="btn-save" class="btn btn-outline-success float-end" style="display: none;">Save</a>
        <a href="#" id="btn-cancel" class="btn btn-outline-danger float-end" style="display: none; margin-right: 5px;">Cancel</a>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form
                        action="{{ $formActionURL }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="form-firm"
                    >
                        @if ($method == 'edit')
                            <input type="hidden" name="_method" value="PUT" />
                        @endif
                        @csrf
                        <div class="card card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ isset($data->name) ? $data->name : "" }}" required/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="item_type_id" class="form-control form-flexible select2">
                                    @foreach ($item_types as $v)
                                        <option value="{{ $v->id }}"{{ isset($data->item_type_id) && $data->item_type_id == $v->id ? " selected=selected" : "" }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input id="description" name="description" type="text" class="form-control" value="{{ isset($data->description) ? $data->description : "" }}" required/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">{{ config('global.default_currency_symbol') }}</span>
                                    </div>
                                    <input id="price" name="price" type="text" class="form-control" value="{{ isset($data->price) ? $data->price : "" }}" required/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<style>
.title-link h1 {
    color: blue;
}
.text-left {
  text-align: left !important;
}
</style>
@endsection

@section('script')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        @if ($method != 'add')
            $(".form-control").prop("disabled", true);
            $(".form-flexible").prop("disabled", true);
        @endif
    });
    @if ($method != 'add')
    $('#btn-edit').click( function(e) {
        $(".form-control").prop("disabled", false);
        $(".form-flexible").prop("disabled", false);
        $('#btn-save').toggle();
        $('#btn-edit').toggle();
        $('#btn-cancel').toggle();
    });
    $('#btn-cancel').click( function(e) {
        $(".form-control").prop("disabled", true);
        $(".form-flexible").prop("disabled", true);
        $('#btn-save').toggle();
        $('#btn-edit').toggle();
        $('#btn-cancel').toggle();
    });
    @endif
    $('#btn-save').click( function(e) {
        e.preventDefault();
        $('form#form-firm').submit();
    });
</script>
@endsection
