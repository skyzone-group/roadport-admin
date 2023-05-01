<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;

class CountryController extends Controller
{
    // List of countries
    public function index()
    {
        abort_if_forbidden('user.show');
        $countries = Countries::get();
        return view('pages.country.index', compact('countries'));
    }

    // add car
    public function add()
    {
        return view('pages.country.add');
    }

    // create car
    public function create(Request $request)
    {
        $item = Countries::create($request->all());
        return redirect()->route('countryIndex');
    }

    // car edit
    public function edit($id)
    {
        $item = Countries::where('id', '=', $id)->get()->first();
        return view('pages.country.edit', compact('item'));
    }

    // car update
    public function update(Request $request, $id)
    {
        $item = Countries::find($id);
        $item->fill($request->all());
        $item->save();
        return redirect()->route('countryIndex');
    }

    // delete car by id
    public function destroy($id)
    {
        $user = Countries::destroy($id);
        return redirect()->route('countryIndex');
    }
}
