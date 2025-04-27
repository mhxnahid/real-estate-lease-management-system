<?php

namespace App\Services;

use App\Models\User;
use App\Models\Lease;
use App\Models\Property;
use App\Models\LandlordTenant;

class BasicSvc
{
    //common
    public static function getAllLeases(){
        return Lease::with(['property', 'tenant', 'landlord'])
        ->where('tenant_id', auth()->user()->id)
        ->orWhere('landlord_id', auth()->user()->id)
        ->orderBy('lease_start')->get();
    }

    //lords
    public static function getTenantIds()
    {
        return LandlordTenant::where('landlord_id', auth()->user()->id)->where('active', 1)->pluck('tenant_id');
    }

    public static function getTenantIdsAll()
    {
        return LandlordTenant::where('landlord_id', auth()->user()->id)->pluck('tenant_id');
    }

    public static function getTenantsAllWithUser()
    {
        return LandlordTenant::where('landlord_id', auth()->user()->id)->with('tenant')->get();
    }

    public static function getTenantsAllUsers()
    {
        return User::whereIn('id', LandlordTenant::where('landlord_id', auth()->user()->id)->pluck('tenant_id'))->get();
    }

    public static function getTenantProperties(){
        return Property::whereIn('id', Lease::where('tenant_id', auth()->user()->id)->pluck('property_id'))->get();
    }

    // landlords
    public static function getLandlordsAllWithUser()
    {
        return LandlordTenant::where('tenant_id', auth()->user()->id)->with('landlord')->get();
    }

    public static function getLandlordsAllUsers()
    {
        return User::whereIn('id', LandlordTenant::where('tenant_id', auth()->user()->id)->pluck('landlord_id'))->get();
    }

    public static function getLandlordProperties(){
        return \App\Property::whereIn('id', \App\Lease::where('tenant_id', auth()->user()->id)->pluck('property_id'))->get();
    }
}