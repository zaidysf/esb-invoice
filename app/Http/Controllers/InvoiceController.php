<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

use LaravelDaily\Invoices\Invoice as Inv;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoiceController extends Controller
{
    public function __construct()
    {
        Config::set('invoice_digits', Setting::where('key', 'invoice_digits')->first()->value);
        Config::set('tax_percentage', Setting::where('key', 'tax_percentage')->first()->value);
        Config::set('default_currency', Setting::where('key', 'default_currency')->first()->value);
        Config::set('default_currency_name', Setting::where('key', 'default_currency_name')->first()->value);
        Config::set('default_currency_symbol', Setting::where('key', 'default_currency_symbol')->first()->value);

        Config::set('invoices.seller.attributes.name', Setting::where('key', 'company_name')->first()->value);
        Config::set('invoices.seller.attributes.address', Setting::where('key', 'company_address1')->first()->value);
        Config::set('invoices.seller.attributes.code', '');
        Config::set('invoices.seller.attributes.vat', Setting::where('key', 'tax_percentage')->first()->value);
        Config::set('invoices.seller.attributes.phone', '');
        Config::set('invoices.seller.attributes.custom_fields.country', Setting::where('key', 'company_country')->first()->value);
        Config::set('invoices.currency.code', config('global.default_currency'));
        Config::set('invoices.currency.symbol', config('global.default_currency_symbol'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = 10;
        if ($request->has('per_page')) {
            $page = $request->get('per_page');
        }
        if ($request->has('key')) {
            switch ($request->get('key')) {
                case 'invoice_no':
                    $data = Invoice::where('id', 'LIKE', '%'.(int) $request->get('value').'%')->paginate($page);
                    break;
                case 'client':
                    $data = Invoice::whereRelation('client', 'name', 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
                default:
                    $data = Invoice::where($request->get('key'), 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
            }
        } else {
            $data = Invoice::paginate($page);
        }
        $selectColumn = [
            'invoice_no' => 'Invoice No',
            'client' => 'Client',
            'subject' => 'Subject',
        ];
        $selectPagination = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
        return view('invoices.index', compact('data', 'selectColumn', 'selectPagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $formActionURL = route('invoices.store');
        $label = 'Register a New Invoice';
        $method = 'add';
        $clients = Client::all();
        $statusArr = (new Invoice)->statusArr;
        return view('invoices.form', compact('data', 'formActionURL', 'label', 'method', 'clients', 'statusArr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = new Invoice;
        $input = $request->all();
        $input['is_using_tax'] = $request->is_using_tax == 'on' ? 1 : 0;
        $model->fill($input)->save();

        return back()
        ->with('success', 'An Invoice has been registered.')
        ->with('name', $request->subject);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function edit($id)
    {
        $data = Invoice::findOrFail($id);
        $formActionURL = route('invoices.update', ['invoice' => $id]);
        $label = 'Edit Invoice - '.$data->subject;
        $method = 'edit';
        $clients = Client::all();
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = Invoice::findOrFail($id);
        $input = $request->all();
        $input['is_using_tax'] = $request->is_using_tax == 'on' ? 1 : 0;
        $model->fill($input)->save();

        return back()
        ->with('success', 'An Invoice has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Invoice::findOrFail($id);
        $model->delete();

        return back()
        ->with('success', 'An Invoice has been deleted.');
    }

    /**
     * Print specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $model = Invoice::findOrFail($id);

        $date_diff = strtotime($model->issued_at) - strtotime($model->due_date);
        $due_date = ceil(abs($date_diff / 86400));;
        Config::set('invoices.date.pay_until_days', $due_date);

        $items = [];
        foreach ($model->invoice_items as $v) {
            $items[] = (new InvoiceItem())->title($v->item->name)
                        ->description($v->item->item_type->name)
                        ->pricePerUnit($v->price)
                        ->quantity($v->qty);
        }

        $buyer = new Party([
            'name'          => $model->client->name,
            'phone'         => $model->client->phone,
            'custom_fields' => [
                'address'   => $model->client->address1,
                'country'   => $model->client->country,
            ],
        ]);

        $invoice = Inv::make()
                    ->name($model->subject)
                    ->status($model->status_name)
                    ->buyer($buyer)
                    ->taxRate(config('global.tax_percentage'))
                    ->addItems($items);

        return $invoice->stream();
    }
}
