<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:user-list|user-view|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-view', ['only' => ['show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    private function planCheck()
    {
        $authUser = Auth::user();
        if ($authUser->plan == "free") {
            return view('admin.free-plan');
            die();
        }
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request): View
    {
        $response = $this->planCheck();
        if ($response) {
            return $response;
        }
        $authUser = Auth::user();
        $users = User::orderBy('created_at', 'DESC');
        if (!$authUser->hasRole('superadmin')) {
            $users->where('created_by', $authUser->id);
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $users->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', $search . '%')
                    ->orWhere('plan', 'like', $search . '%')
                    ->orWhereHas('createdByUser', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $data = $users->paginate(10);

        return view('admin.settings.user.all', compact('data'));
        // ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $response = $this->planCheck();
        if ($response) {
            return $response;
        }
        $authUser = Auth::user();
        //  user has a superadmin role.
        if ($authUser->hasRole('superadmin')) {
            // $roles = Role::whereIn('name', ['admin', 'manager'])->pluck('name', 'name')->all();
             $roles = Role::whereIn('name', ['admin'])->pluck('name', 'name')->all();
        }
        // user has an admin role.
        elseif ($authUser->hasRole('admin')) {
            $roles = Role::whereIn('name', ['manager'])->pluck('name', 'name')->all();
        }
        else {
            $roles = Role::pluck('name', 'name')->all();
        }

        return view('admin.settings.user.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
        ];
        $this->validate($request, $rules);

        DB::beginTransaction();
        try {

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['normal_password'] = $request->password;
            $input['created_by'] = Auth::id();

            $authUser = Auth::user();
            if ($authUser->hasRole('admin')) {
                $role = Role::where('name', 'manager')->first();
            } else {
                $role = Role::where('name', $request->input('roles'))->first();
                if ($input['plan'] == 'paid') {
                    $input['add_student_limit'] = "unlimited";
                }
            }
            $user = User::create($input);
            // dd($request->input('roles'));
            $user->assignRole([$role->id]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('admin.settings.user.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        // $roles = Role::pluck('name', 'name')->all();
        $roles = Role::whereIn('name', ['admin'])->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.settings.user.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            // 'add_student_limit' => 'required'

        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['normal_password'] = $request->password;
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $input['created_by'] = Auth::id();

            $authUser = Auth::user();
            if ($authUser->hasRole('admin')) {
                $role = Role::where('name', 'manager')->first();
            } else {
                $role = Role::where('name', $request->input('roles'))->first();
                if ($input['plan'] == 'paid') {
                    $input['add_student_limit'] = "unlimited";
                }
            }

            $user = User::find($id);
            $user->update($input);
            
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            User::find($id)->delete();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'User deleted successfully');
    }
}
