@extends('layout')
@section('content')
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm" style="margin-left: 10px;"> + Register a new Invoice </a>
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
                    <form method="GET" action="{{ route('invoices.index') }}">
                    @csrf
                    <div class="row">
                        <div class="col mb-2">
                            <select name="key" class="form-control">
                                @foreach ($selectColumn as $k => $v)
                                    <option {{ isset($_GET['key']) ? ($_GET['key'] == $k ? "selected" : "") : "" }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input
                                placeholder="Enter keywords..."
                                name="value"
                                type="text"
                                class="form-control"
                                value="{{ isset($_GET['value']) ? $_GET['value'] : "" }}"/>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        <div class="col pull-right text-end mt-2">
                            Per Page
                        </div>
                        <div class="col-1 pull-right text-end">
                            <select id="select-page" name="per_page" class="form-control">
                                @foreach ($selectPagination as $k => $v)
                                    <option {{ isset($_GET['per_page']) ? ($_GET['per_page'] == $k ? "selected" : "") : "" }} value="{{ $k }}">
                                        {{ $v }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">&nbsp;</th>
                                <th scope="col" width="5%">&nbsp;</th>
                                <th scope="col">Invoice No.</th>
                                <th scope="col">Client</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Issue Date</th>
                                <th scope="col">Due Date</th>
                                <th scope="col" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $v)
                                <tr>
                                    <td>
                                        <a href="{{ route('invoices.edit', ['invoice' => $v->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('invoices.destroy', $v->id) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.invoice_items.index', ['invoice' => $v->id]) }}" class="btn btn-success btn-sm">Items</a>
                                    </td>
                                    <td><a target="_blank" href="{{ route('invoices.print', ['invoice' => $v->id]) }}" class="btn btn-outline btn-outline-primary btn-sm">Print</a> {{ Str::padLeft($v->id, config('global.invoice_digits'), 0) }}</td>
                                    <td>{{ $v->client->name }}</td>
                                    <td>{{ $v->subject }}</td>
                                    <td>{{ date('d/m/Y', strtotime($v->issued_at)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($v->due_date)) }}</td>
                                    @php $total = 0; @endphp
                                    @foreach ($v->invoice_items as $x)
                                        @php $total += $x->price * $x->qty; @endphp
                                    @endforeach
                                    @if ($v->is_using_tax)
                                        @php $total += $total * config('global.tax_percentage')/100; @endphp
                                    @endif
                                    <td class="text-right">{{ config('global.default_currency_symbol').number_format($total,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('pagination', ['paginator' => $data])
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
.text-right {
    text-align: right;
}
</style>
@endsection

@section('script')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
    $('#select-page').change(function(e){
        if (this.value != null && this.value != "") {
            let searchParams = new URLSearchParams(window.location.search)
            searchParams.delete('per_page');
            searchParams.append('per_page', this.value);
            window.location='{{ route('invoices.index') }}?'+searchParams.toString();
        }
    });
    $("#searchForm").submit( function(eventObj) {
        let searchParams = new URLSearchParams(window.location.search)
        searchParams.delete("_token");
        searchParams.delete("key");
        searchParams.delete("value");
        searchParams.forEach(function(value, key) {
            $("<input />").attr("type", "hidden")
                .attr("name", key)
                .attr("value", value)
                .appendTo("#searchForm");
        });
        return true;
    });
</script>
@endsection
