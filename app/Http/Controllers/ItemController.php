<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function __construct()
    {
        Config::set('default_currency', Setting::where('key', 'default_currency')->first()->value);
        Config::set('default_currency_name', Setting::where('key', 'default_currency_name')->first()->value);
        Config::set('default_currency_symbol', Setting::where('key', 'default_currency_symbol')->first()->value);
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
                case 'type':
                    $data = Item::whereRelation('item_type', 'name', 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
                default:
                    $data = Item::where($request->get('key'), 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
            }
        } else {
            $data = Item::paginate($page);
        }
        $selectColumn = [
            'name' => 'Name',
            'type' => 'Type'
        ];
        $selectPagination = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
        return view('items.index', compact('data', 'selectColumn', 'selectPagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $formActionURL = route('items.store');
        $label = 'Register a New Item';
        $method = 'add';
        $item_types = ItemType::all();
        return view('items.form', compact('data', 'formActionURL', 'label', 'method', 'item_types'));
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
            'name' => 'required|unique:items,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = new Item;
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'An Item has been registered.')
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
        return view('items.form', [
            'data' => Item::findOrFail($id)
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
        $data = Item::findOrFail($id);
        $formActionURL = route('items.update', ['item' => $id]);
        $label = 'Edit Item - '.$data->name;
        $method = 'edit';
        $item_types = ItemType::all();
        return view('items.form', compact('data', 'formActionURL', 'label', 'method', 'item_types'));
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
            'name' => 'required|unique:items,name,'.$id,
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = Item::findOrFail($id);
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'An Item has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Item::findOrFail($id);
        $model->delete();

        return back()
        ->with('success', 'An Item has been deleted.');
    }
}
