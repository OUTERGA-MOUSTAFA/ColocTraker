<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    function index(){

        return view('colocation.colocations');
    }
    function store(Request $request){

       $data = $request->validate(
        [
            'name'=>'required|min:5|max:30',
            'description'=>'nullable|string|max:100'
        ]
       );
        $colocation = Colocation::create(
        [
            'name'=> $data['name'],
            'description'=>$data['description'],
            'created_by'=>Auth()->id()
        ]
       );
    }
}
