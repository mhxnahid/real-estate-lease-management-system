<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lease;
use App\Models\Property;
use App\Http\Requests;
use App\Services\BasicSvc;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $totalLandlords = 0;
        $totalTenants = 0;
        $totalProperties = 0;
        $totalLeases = 0;


        if($user->role->contains('id', 1)){
            $totalLandlords = User::whereHas('role', function ($query) {
                $query->where('id', 2);
            })->count();
            $totalTenants = User::whereHas('role', function ($query) {
                $query->where('id', 2);
            })->count();;
            $totalProperties = Property::count();
            $totalLeases = Lease::count();
        }
        elseif($user->role->contains('id', 2)){
            $totalLandlords = null;
            $totalTenants = BasicSvc::getTenantsAllWithUser()->count();
            $totalProperties = Property::where('user_id', auth()->user()->id)->count();
            $totalLeases = BasicSvc::getAllLeases()->count();
        }
        elseif($user->role->contains('id', 3)){
            $totalLandlords = BasicSvc::getLandlordsAllWithUser()->count();
            $totalTenants = null;
            $totalProperties = BasicSvc::getTenantProperties()->count();
            $totalLeases = BasicSvc::getAllLeases()->count();
        }
        // dd($totalLandlords, $totalTenants, $totalProperties, $totalLeases);
        
        // Pass the counts to the view
        return view('home', compact('totalLandlords', 'totalTenants', 'totalProperties', 'totalLeases'));
    }
}
