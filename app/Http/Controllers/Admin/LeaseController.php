<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Lease;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\LandlordTenant;
use App\Http\Controllers\Controller;
use App\Notifications\LeaseNotification;


class LeaseController extends Controller
{
    public function index()
    {
        $leases = Lease::with(['property', 'tenant', 'landlord'])
        ->where('tenant_id', auth()->user()->id)
        ->orWhere('landlord_id', auth()->user()->id)
        ->orderBy('lease_start')->get();

        return view('admin.leases.index', compact('leases'));
    }

    public function create()
    {
        $properties = Property::where('user_id', auth()->user()->id)->get();

        $tenant_ids = LandlordTenant::where('landlord_id', auth()->user()->id)->where('active', 1)->pluck('tenant_id');
        $tenants = User::whereIn('id', $tenant_ids)->get();

        return view('admin.leases.create', compact('tenants', 'properties'));
    }

    public function store(Request $request)
    {
        // Validate and store the new lease
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:users,id',
            // 'landlord_id' => 'required|exists:users,id',
            'lease_start' => 'required|date',
            'lease_end' => 'required|date|after:lease_start',
            'document_ref' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,bmp,tiff',
        ]);

        $lease = Lease::create($request->all() + ['landlord_id' => auth()->user()->id]);
        $lease->document_ref = $request->file('document_ref')->store('leases', 'public');
        $lease->save();

        try {
            $lease->tenant->notify(new LeaseNotification($lease->landlord));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send invitation notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.leases.index');
    }

    public function acceptLease(Request $request, Lease $lease)
    {
        $lease->tenant_accepted = true; // Update the status
        $lease->save();

        return redirect()->route('admin.leases.index')->with('success', 'Lease accepted successfully.');
    }

    public function show(Lease $lease)
    {
        // $lease = Lease::findOrFail($id);
        return view('admin.leases.show', compact('lease'));
    }

    public function edit(Lease $lease)
    {
        // Logic to show the form for editing a lease
    }

    public function update(Request $request, Lease $lease)
    {
        // Validate and update the lease
    }

    public function destroy(Lease $lease)
    {
        // Logic to delete a lease
        $lease->delete();
        return redirect()->route('leases.index');
    }
}