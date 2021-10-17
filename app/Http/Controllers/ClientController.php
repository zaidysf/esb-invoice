<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
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
                default:
                    $data = Client::where($request->get('key'), 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
            }
        } else {
            $data = Client::paginate($page);
        }
        $selectColumn = [
            'name' => 'Name',
            'email' => 'Email',
        ];
        $selectPagination = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
        return view('clients.index', compact('data', 'selectColumn', 'selectPagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $formActionURL = route('clients.store');
        $label = 'Register a New Client';
        $method = 'add';
        $countries = Http::get('https://restcountries.com/v3.1/all')->json();
        return view('clients.form', compact('data', 'formActionURL', 'label', 'method', 'countries'));
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
            'name' => 'required|unique:clients,name',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = new Client;
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'A Client has been registered.')
        ->with('name', $request->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('clients.form', [
            'data' => Client::findOrFail($id)
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
        $data = Client::findOrFail($id);
        $formActionURL = route('clients.update', ['client' => $id]);
        $label = 'Edit Client - '.$data->name;
        $method = 'edit';
        $countries = Http::get('https://restcountries.com/v3.1/all')->json();
        return view('clients.form', compact('data', 'formActionURL', 'label', 'method', 'countries'));
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
            'name' => 'required|unique:clients,name,'.$id,
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = Client::findOrFail($id);
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'A Client has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Client::findOrFail($id);
        $model->delete();

        return back()
        ->with('success', 'A Client has been deleted.');
    }
}
