<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\LandlordTenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VerifyLandlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/verify_message';

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
            // 'password' => 'required|string|min:6|confirmed',
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
            'password' => bcrypt(Str::random(8)),
            'invitation_token' => substr(md5(rand(0, 9) . $data['email'] . time()), 0, 32),
        ]);

        $user->role()->attach(2);

        try {
            $user->notify(new VerifyLandlord());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send invitation notification: ' . $e->getMessage());
        }

        return $user;

    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $this->create($request->all());

        return redirect($this->redirectTo);
    }

    public function verify_message()
    {
        return view('auth.verify_message');
    }

    public function verify($invitation_token)
    {
        $user = User::where('invitation_token', $invitation_token)->where('verified_at', null)->firstOrFail();

        $user->verified_at = now();
        $user->save();

        Auth::loginUsingId($user->id);

        return redirect()->route('auth.change_password');
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
