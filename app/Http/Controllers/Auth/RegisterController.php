<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\LandlordTenant;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->role()->attach(2);

        return $user;

    }

    public function processInvitation($invitation_token, User $user, LandlordTenant $lt)
    {
        // $user = User::where('invitation_token', $invitation_token)->firstOrFail();
        $new_user = false;
        // tenant register
        if(!$user->verified_at && $user->invitation_token === $invitation_token){
            $new_user = true;

            $user->verified_at = now();
            $user->save();
        }

        if(!$user->verified_at){
            abort(400);
        }

        if(!$user->role->contains('id', 3)){
            return abort(403);
        }

        // invited
        if($lt->invite_token != $invitation_token) {
            return redirect()->route('auth.login');
        }

        $lt->update([
            'accepted_invite' => true,
            'active' => true,
            // 'invite_token' => null,
        ]);


        Auth::loginUsingId($user->id);

        if($new_user){
            return redirect()->route('auth.login');
        }


        return redirect()->route('auth.change_password');
    }

}
