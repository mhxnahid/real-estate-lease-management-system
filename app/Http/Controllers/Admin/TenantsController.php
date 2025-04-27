<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Property;
use App\Services\BasicSvc;
use Illuminate\Support\Str;
use App\Models\LandlordTenant;
use App\Http\Controllers\Controller;
use App\Notifications\InvitationSend;
use App\Http\Requests\Admin\StoreTenantsRequest;

class TenantsController extends Controller
{

    /**
     * Display a listing of Tenants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = BasicSvc::getTenantsAllWithUser();
        // $tenants = User::whereIn('id', $tenant_ids)->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating new Tenant.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $properties = Property::where('user_id', auth()->user()->id)->pluck('name', 'id');

        return view('admin.tenants.create', compact('properties'));
    }

    /**
     * Store a newly created Tenant in storage.
     *
     * @param  \App\Http\Requests\StoreTenantsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantsRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user && !$user->role->contains('id', 3)){
            return redirect()->back()->withErrors(['email' => 'User exists, but not a tenant'])->withInput();
        }

        $token = substr(md5(rand(0, 9) . $request->email . time()), 0, 32);

        if($user && !$user->verified_at){
            $user->invitation_token = $token;
            $user->save();
        }

        if(!$user){
            $user = User::create([
                'name'             => $request->name,
                'email'            => $request->email,
                'password'         => Str::random(8),
                // 'property_id'      => $request->property_id,
                'invitation_token' => $token,
            ]);
        }

        if(LandlordTenant::where('landlord_id', auth()->user()->id)->where('tenant_id', $user->id)->exists()) {
            return redirect()->back()->withErrors(['email' => 'Tenant exists'])->withInput();
        }

        // $user->properties()->attach($request->property_id);
        // $user->properties()->create([
        //     'user_id' => $user->id,
        //     'property_id' => $request->property_id,
        // ]);

        $lt = LandlordTenant::create([
            'landlord_id' => auth()->user()->id,
            'tenant_id' => $user->id,
            'invite_token' => $token,
        ]);

        $user->role()->attach(3);

        try {
            $user->notify(new InvitationSend(auth()->user(), $lt, $token));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send invitation notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.tenants.index');
    }


    /**
     * Remove Property from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tenant = LandlordTenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.tenants.index');
    }

}
