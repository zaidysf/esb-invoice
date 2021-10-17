<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class InvoiceItemController extends Controller
{
    public function __construct()
    {
        Config::set('invoice_digits', Setting::where('key', 'invoice_digits')->first()->value);
        Config::set('tax_percentage', Setting::where('key', 'tax_percentage')->first()->value);
        Config::set('default_currency', Setting::where('key', 'default_currency')->first()->value);
        Config::set('default_currency_name', Setting::where('key', 'default_currency_name')->first()->value);
        Config::set('default_currency_symbol', Setting::where('key', 'default_currency_symbol')->first()->value);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $invoice_id, Request $request)
    {
        $page = 10;
        if ($request->has('per_page')) {
            $page = $request->get('per_page');
        }
        if ($request->has('key')) {
            switch ($request->get('key')) {
                default:
                    $data = InvoiceItem::where($request->get('key'), 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
            }
        } else {
            $data = InvoiceItem::paginate($page);
        }
        $selectColumn = [
            'subject' => 'Name',
        ];
        $selectPagination = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
        $invoice = Invoice::findOrFail($invoice_id);
        return view('invoice_items.index', compact('data', 'selectColumn', 'selectPagination', 'invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);
        $data = [];
        $formActionURL = route('invoices.invoice_items.store', ['invoice' => $invoice_id]);
        $label = 'Register a New Item ['.$invoice->subject.']';
        $method = 'add';
        $items = Item::all();
        return view('invoice_items.form', compact('invoice', 'data', 'formActionURL', 'label', 'method', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $invoice_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = new InvoiceItem;
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'An item has been registered.')
        ->with('name', $request->subject);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $invoice_id, $id)
    {
        return view('invoices.form', [
            'data' => Invoice::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $invoice_id, $id)
    {
        $data = Invoice::findOrFail($id);
        $formActionURL = route('invoices.invoice_items.update', ['invoice' => $invoice_id, 'invoice_item' => $id]);
        $label = 'Edit Invoice - '.$data->subject;
        $method = 'edit';
        $statusArr = (new Invoice)->statusArr;
        return view('invoices.form', compact('data', 'formActionURL', 'label', 'method', 'clients', 'statusArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $invoice_id, Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = Invoice::findOrFail($id);
        $input = $request->all();
        $input['is_using_tax'] = $request->is_using_tax == 'on' ? 1 : 0;
        $model->fill($input)->save();

        return back()
        ->with('success', 'A Invoice has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $invoice_id, $id)
    {
        $model = Invoice::findOrFail($id);
        $model->delete();

        return back()
        ->with('success', 'A Invoice has been deleted.');
    }
}
