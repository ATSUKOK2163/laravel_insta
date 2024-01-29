<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.show')->with('user',$user);
    }

    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.profile.edit')
                ->with('user',$user);
    }
    
    public function update(Request $request)
    {
        # 1. Validate the data from the form
        $request->validate([
            'name'           => 'required|min:1|max:50',
            'email'          => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar'         => 'mimes:jpeg,jpg,gif,png|max:1048',
            'introduction'   => 'max:100'
        ]);

         # 2. Update the profile
         $user                   = $this->user->findOrFail(Auth::user()->id);
         $user->name             = $request->name;
         $user->email            = $request->email;
         $user->introduction     = $request->introduction;
 
         //If there is a new avatar...
         if($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
         }
         
         #save
         $user->save();

         # 3. Redirect to show profile page (to confirm the update)
        return redirect()->route('profile.show', Auth::user()->id);
    }

    # To show the user's followers on the profile
    public function followers($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.followers')->with('user',$user);
    }

    # To show the user's following on the profile
    public function following($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.following')->with('user',$user);
    }
}
