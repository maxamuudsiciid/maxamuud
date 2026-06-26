<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\BloodCollection;
use App\Models\BloodInventory;
use App\Models\Distribution;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $data = [];

        if ($role === 'admin' || $role === 'staff') {
            $data['totalDonors']        = Donor::count();
            $data['activeDonors']       = Donor::where('status', 'Active')->count();
            $data['totalHospitals']     = Hospital::count();
            $data['pendingRequests']    = BloodRequest::where('status', 'Pending')->count();
            $data['approvedRequests']   = BloodRequest::where('status', 'Approved')->count();
            $data['totalBloodUnits']    = BloodInventory::sum('quantity');
            $data['totalUsers']         = User::count();
            $data['totalDistributions'] = Distribution::count();

            // Blood group inventory
            $data['bloodGroupsData'] = BloodInventory::orderBy('blood_group')->get();

            // Expiring blood collections (within 7 days)
            $data['expiringUnits'] = BloodCollection::where('expiry_date', '<=', Carbon::now()->addDays(7))
                ->where('expiry_date', '>=', Carbon::now())
                ->where('screening_result', 'Safe')
                ->count();

            // Monthly collections - last 6 months
            $monthlyCollections = BloodCollection::select(
                    DB::raw('MONTH(donation_date) as month'),
                    DB::raw('YEAR(donation_date) as year'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(quantity) as total_ml')
                )
                ->where('donation_date', '>=', Carbon::now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $data['chartLabels'] = $monthlyCollections->map(function($item) {
                return Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
            })->values()->toJson();

            $data['chartValues'] = $monthlyCollections->pluck('count')->values()->toJson();

            // Blood group distribution for donut chart
            $data['donutLabels'] = $data['bloodGroupsData']->pluck('blood_group')->toJson();
            $data['donutValues'] = $data['bloodGroupsData']->pluck('quantity')->toJson();

            // Recent blood collections
            $data['recentCollections'] = BloodCollection::with('donor')
                ->latest()
                ->take(5)
                ->get();

            // Recent blood requests
            $data['recentRequests'] = BloodRequest::with('hospital')
                ->latest()
                ->take(5)
                ->get();

        } elseif ($role === 'donor') {
            $donor = auth()->user()->donor;
            if ($donor) {
                $data['totalDonations'] = BloodCollection::where('donor_id', $donor->id)->count();
                $lastDonation = BloodCollection::where('donor_id', $donor->id)->latest('donation_date')->first();
                $data['lastDonationDate'] = $lastDonation ? $lastDonation->donation_date : null;
                $data['donorProfile'] = $donor;

                // Next eligible donation date (56 days after last donation)
                if ($lastDonation) {
                    $data['nextEligibleDate'] = Carbon::parse($lastDonation->donation_date)->addDays(56);
                    $data['canDonate'] = Carbon::now()->gte($data['nextEligibleDate']);
                }

                // Donation history
                $data['donationHistory'] = BloodCollection::where('donor_id', $donor->id)
                    ->latest()
                    ->take(5)
                    ->get();
                    
                // Upcoming Appointments
                $data['upcomingAppointments'] = \App\Models\Appointment::where('donor_id', $donor->id)
                    ->where('appointment_date', '>=', Carbon::today())
                    ->whereIn('status', ['Pending', 'Approved'])
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
                    ->take(5)
                    ->get();
            } else {
                $data['totalDonations']  = 0;
                $data['lastDonationDate'] = null;
                $data['donorProfile']    = null;
            }

        } elseif ($role === 'hospital') {
            $hospital = auth()->user()->hospital;
            if ($hospital) {
                $data['hospitalProfile']  = $hospital;
                $data['totalRequests']    = BloodRequest::where('hospital_id', $hospital->id)->count();
                $data['pendingRequests']  = BloodRequest::where('hospital_id', $hospital->id)->where('status', 'Pending')->count();
                $data['approvedRequests'] = BloodRequest::where('hospital_id', $hospital->id)->where('status', 'Approved')->count();
                $data['fulfilledRequests']= BloodRequest::where('hospital_id', $hospital->id)->where('status', 'Fulfilled')->count();

                // Recent requests
                $data['recentRequests']   = BloodRequest::where('hospital_id', $hospital->id)
                    ->latest()->take(5)->get();

                // Blood availability
                $data['bloodGroupsData']  = BloodInventory::orderBy('blood_group')->get();
            } else {
                $data['hospitalProfile']  = null;
                $data['totalRequests']    = 0;
                $data['pendingRequests']  = 0;
                $data['approvedRequests'] = 0;
                $data['fulfilledRequests']= 0;
                $data['bloodGroupsData']  = collect();
            }
        }

        return view('dashboard', $data);
    }
}
