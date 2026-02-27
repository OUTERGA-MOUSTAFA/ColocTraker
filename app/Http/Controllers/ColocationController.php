<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColocationController extends Controller
{
    function colocations()
    {
        $colocations = Colocation::whereHas('users', function ($query) {
            $query->where('user_id', Auth()->id());
        })->get();
        return view('colocation.colocations', compact('colocations'));
    }

    function show($id)
    {
        $colocation = Colocation::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        return view('colocation.index', compact('colocation'));
    }

    function store(Request $request)
    {
        $this->authorize('create', Colocation::class);
        $data = $request->validate(
            [
                'name' => 'required|min:5|max:30',
                'description' => 'required|min:5|max:255'
            ]
        );

        $colocation = Colocation::create(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'created_by' => Auth()->id()
            ]
        );


        $colocation->users()->attach(auth()->id(), [
            'role' => 'owner'
        ]);

        // return view('colocation.index', compact('colocation'));
        return redirect()
            ->route('colocation.show', $colocation->id)
            ->with('success', 'Colocation créée avec succès.');
    }
}
