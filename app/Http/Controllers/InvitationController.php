<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    function inviter(Request $request, $colocation){
        // dd($colocation);
        $data = $request->validate([
            'email'=>'required|email'
        ]);
        // $invitation = Invitation::create([
        // 'colocation_id'=> $colocation,
        // 'created_by'=> ,
        // 'accepted_by'=> Auth()->id(),
        // 'token',
        // 'status'
        // ]);
    }
}
