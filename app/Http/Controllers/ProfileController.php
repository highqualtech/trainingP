<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function listusers(){
        $user = Auth::user();
        $users = User::select()
            ->orderBy('name','ASC')
            ->get();
        return view('users.index', compact('user','users'));
    }

    public function createuser(Request $request){
        $company = new User();
        $company->name = $request->input('username');
        $company->email = $request->input('useremail');
        $company->password = Hash::make($request->input('userpassword'));
        $company->save();
        return redirect("/users")->with('status', 'usercreated');
    }

    public function edituser($id)
    {
        $user = Auth::user();
        $userdetails = User::select()
            ->where('id','=',$id)
            ->first();
        return view('users.edit', compact('user','userdetails'));
    }

    public function updateuser(Request $request, $id)
    {
        $user = Auth::user();
        $userdetails = User::select()
            ->where('id','=',$id)
            ->first();
        $userdetails->name = $request->input('username');
        $userdetails->email = $request->input('useremail');
        if($request->input('userpassword')!='') {
            $userdetails->password = Hash::make($request->input('userpassword'));
        }
        $userdetails->save();
        return redirect("/users")->with('status', 'userupdated');
    }

    public function destroyuser($id)
    {
        User::where('id', $id)->delete();
        return redirect("/users")->with('status', 'userdeleted');
    }
}
