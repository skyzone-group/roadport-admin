<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cars;

class CarsController extends Controller
{
    // List of cars
    public function index()
    {
        abort_if_forbidden('user.show');
        $cars = Cars::get();
        return view('pages.cars.index', compact('cars'));
    }

    // add car
    public function add()
    {
        return view('pages.cars.add');
    }

    // create car
    public function create(Request $request)
    {
        $item = Cars::create($request->all());
        return redirect()->route('carIndex');
    }

    // car edit
    public function edit($id)
    {
        $item = Cars::where('id', '=', $id)->get()->first();
        return view('pages.cars.edit', compact('item'));
    }

    // car update
    public function update(Request $request, $id)
    {
        $item = Cars::find($id);
        $item->fill($request->all());
        $item->save();
        return redirect()->route('carIndex');
    }

    // delete car by id
    public function destroy($id)
    {
        $user = Cars::destroy($id);
        return redirect()->route('carIndex');
    }
}
