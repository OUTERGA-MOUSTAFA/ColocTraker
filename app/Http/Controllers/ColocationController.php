<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    function index(){
        $colocations = Colocation::whereHas('users', function($query){
            $query->where('user_id', Auth()->id());
        })->get();
        return view('colocation.colocations', compact('colocations'));
    }
    function store(Request $request){

       $data = $request->validate(
        [
            'name'=>'required|min:5|max:30',
        ]
       );
        $colocation = Colocation::create(
        [
            'name'=> $data['name'],
            'description'=>$data['description'],
            'created_by'=>Auth()->id()
        ]
       );


       $colocation->users()->attach(auth()->id());

    return redirect()->route('colocation.index');
    }

}
