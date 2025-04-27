<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreTenantsRequest;
use App\Notifications\InvitationSend;
use App\Models\Property;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BasicSvc;

class LandlordController extends Controller
{

    /**
     * Display a listing of Tenants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $landlords = BasicSvc::getLandlordsAllWithUser();

        return view('admin.landlords.index', compact('landlords'));
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
        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => str_random(8),
            // 'property_id'      => $request->property_id,
            'invitation_token' => substr(md5(rand(0, 9) . $request->email . time()), 0, 32),
        ]);

        // $user->properties()->attach($request->property_id);
        $user->properties()->create([
            'user_id' => $user->id,
            'property_id' => $request->property_id,
        ]);

        $user->role()->attach(3);

        try {
            $user->notify(new InvitationSend());
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
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('admin.properties.index');
    }

}
