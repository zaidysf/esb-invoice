<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemTypeController extends Controller
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
                    $data = ItemType::where($request->get('key'), 'LIKE', '%'.$request->get('value').'%')->paginate($page);
                    break;
            }
        } else {
            $data = ItemType::paginate($page);
        }
        $selectColumn = [
            'name' => 'Name',
        ];
        $selectPagination = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
        return view('item_types.index', compact('data', 'selectColumn', 'selectPagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $formActionURL = route('item_types.store');
        $label = 'Register a New Item Type';
        $method = 'add';
        return view('item_types.form', compact('data', 'formActionURL', 'label', 'method'));
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
            'name' => 'required|unique:item_types,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = new ItemType;
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'A Item Type has been registered.')
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
        return view('item_types.form', [
            'data' => ItemType::findOrFail($id)
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
        $data = ItemType::findOrFail($id);
        $formActionURL = route('item_types.update', ['item_type' => $id]);
        $label = 'Edit ItemType - '.$data->name;
        $method = 'edit';
        return view('item_types.form', compact('data', 'formActionURL', 'label', 'method'));
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
            'name' => 'required|unique:item_types,name,'.$id,
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $model = ItemType::findOrFail($id);
        $input = $request->all();
        $model->fill($input)->save();

        return back()
        ->with('success', 'A Item Type has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ItemType::findOrFail($id);
        $model->delete();

        return back()
        ->with('success', 'A Item Type has been deleted.');
    }
}
