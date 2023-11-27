<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Plan;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {
        $authUser = Auth::user();
        $total = $this->getTotalCounts($authUser);
        $plans = $this->getPlans($authUser, $request);
        $search = $request->input('search');
        $plans = $this->applySearchFilter($plans, $search);
        $plans = $plans->orderBy('created_at', 'DESC')->paginate(10);
        return view('dashboard')->with(compact('total', 'plans'));
    }

    private function getTotalCounts($authUser)
    {
        $total = [];

        if ($authUser->hasRole('superadmin')) {
            $total['librariesOwner'] = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();
            $total['student'] = Student::count();
            $total['plans'] = Plan::count();
            $total['libraries'] = Library::count();
            $total['librariesApproved'] = Library::where('status', 'approved')->count();
            $total['librariesPending'] = Library::where('status', 'pending')->count();
        } elseif ($authUser->hasRole('admin')) {
            $total['student'] = Student::where('admin_id', $authUser->id)->count();
            // $total['plans'] = Plan::count();
            // $total['libraries'] = Library::where(['admin_id' => $authUser->id])->count();
            // $total['librariesApproved'] = Library::where(['admin_id' => $authUser->id, 'status' => 'approved'])->count();
            // $total['librariesPending'] = Library::where(['admin_id' => $authUser->id, 'status' => 'pending'])->count();
        } else {
            $total['student'] = Student::where('manager_id', $authUser->id)->count();
        }

        $total['totalActivePlans'] = Plan::whereDate('valid_upto_date', '>=', now())
            ->whereDate('valid_upto_date', '<=', now()->addDays(5))
            ->count();

        return $total;
    }

    private function getPlans($authUser, $request)
    {
        if ($authUser->hasRole('superadmin')) {
            return Plan::with('student')
                ->whereDate('valid_upto_date', '>=', now())
                ->whereDate('valid_upto_date', '<=', now()->addDays(5));
        }

        return Plan::with('student')
            ->whereHas('student', function ($query) use ($authUser) {
                $column = $authUser->hasRole('admin') ? 'admin_id' : 'manager_id';
                $query->where($column, $authUser->id);
            })
            ->whereDate('valid_upto_date', '>=', now())
            ->whereDate('valid_upto_date', '<=', now()->addDays(5));
    }

    private function applySearchFilter($plans, $search)
    {
        if ($search && Carbon::hasFormat($search, 'd-m-Y')) {
            $formattedSearchDate = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
            $plans->where(function ($query) use ($formattedSearchDate) {
                $query->orWhere('valid_from_date', 'like', '%' . $formattedSearchDate . '%')
                    ->orWhere('valid_upto_date', 'like', '%' . $formattedSearchDate . '%');
            });
        } elseif ($search) {
            $plans->where(function ($query) use ($search) {
                $query->where('plan', 'like', '%' . $search . '%')
                    ->orWhere('mode_of_payment', 'like', '%' . $search . '%')
                    ->orWhereHas('student', function ($studentSubquery) use ($search) {
                        $studentSubquery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('aadhar_number', 'like', '%' . $search . '%')
                            ->orWhere('personal_number', 'like', '%' . $search . '%');
                    });
            });
        }

        return $plans;
    }
}
