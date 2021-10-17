<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Invoice::with(['invoice_items', 'client'])->get();
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
        return Invoice::where('id', $id)->with(['invoice_items', 'client'])->first();
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
}
