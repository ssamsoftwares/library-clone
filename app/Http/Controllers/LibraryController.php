<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Pagination\Paginator;

class LibraryController extends Controller
{


    public function index(Request $request)
    {
        $authUser = Auth::user();
        $query = Library::with('admin', 'creator');

        if ($authUser->hasRole('admin')) {
            // Admin can see libraries where they are the admin
            $query->where('admin_id', $authUser->id);
        } elseif (!$authUser->hasRole('superadmin')) {
            // Other users (managers) can see libraries where they are the manager
            $query->where('manager_id', $authUser->id);
        }
        // Search
        $search = $request->input('search');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('library_name', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhereHas('creator', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('admin', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        $library = $query->paginate(10);

        return view('admin.library.all', compact('library'));
    }


    public function create()
    {
        $authUser = Auth::user();
        if ($authUser->hasRole('superadmin')) {
            $usersWithRole = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->pluck('name', 'id')->toArray();
        } elseif ($authUser->hasRole('admin')) {
            $usersWithRole = User::whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })->pluck('name', 'id')->toArray();
        }

        return view('admin.library.add', compact('usersWithRole'));
    }


    public function store(Request $request, Library $library)
    {
        $request->validate([
            'library_name' =>  'required|unique:libraries',
            'address' => 'required',
            // 'admin_id' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $data = $request->all();
            // LOGO IMG
            if ($request->hasFile('logo')) {
                $logoImg = $request->file('logo');
                $filename = uniqid() . '.' . $logoImg->getClientOriginalExtension();
                $logoImg->move(public_path('library_logo'), $filename);
                $data['logo'] = 'library_logo/' . $filename;
            }

            $authUser = Auth::user();
            if ($authUser->hasRole('superadmin')) {
                $data['status'] = "approved";
                $data['admin_id'] = $request->admin_id;
            } else {
                $data['status'] = "pending";
                $data['admin_id'] = Auth::id();
            }
            $data['created_id'] = Auth::id();
            // $data['admin_id'] = $request->manager_id;

            // check library exist
            $ifExist = Library::where(['admin_id' => $data['admin_id']])->first();
            if ($ifExist) {
                return redirect()->back()->with('status', 'Your have already library...!');
                die();
            }

            $library = Library::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'Library Create successfull .!');
    }


    public function show($id, Request $request)
    {
        $library = Library::with('admin', 'creator')->find($id);
        if (!$library) {
            abort(404, 'Library not found');
        }
        $searchTerm = $request->input('search');
        $managerIds = json_decode($library->manager_id);
        $admin = User::where('id', $library->admin_id);
        if ($searchTerm) {
            $admin->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('plan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('created_by', 'like', '%' . $searchTerm . '%');
            });
        }
        $admin = $admin->first();
        $managersQuery = User::whereIn('id', $managerIds ?? []);

        if ($searchTerm) {
            $managersQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('plan', 'like', '%' . $searchTerm . '%')
                    ->orWhere('created_by', 'like', '%' . $searchTerm . '%');
            });
        }
        $managers = $managersQuery->with('createdByUser')->paginate(10);

        if ($searchTerm && $admin === null && $managers->isEmpty()) {
            return view('admin.library.view', compact('library', 'managers', 'admin'))->with('message', 'No records found.');
        }

        return view('admin.library.view', compact('library', 'managers', 'admin'));
    }

    public function edit(Library $library)
    {
        $authUser = Auth::user();
        $managers = null;
        $students = null;
        if ($authUser->hasRole('superadmin')) {
            $usersWithRole = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->pluck('name', 'id')->toArray();
        } elseif ($authUser->hasRole('admin')) {
            $usersWithRole = User::whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })->pluck('name', 'id')->toArray();
            $managers = User::orderBy('created_at', 'DESC')->where('created_by', $authUser->id)->get();

            $students = Student::orderBy('created_at', 'DESC')->where(['admin_id' => $authUser->id, 'manager_id' => NULL, 'lib_id' => NULL])->get();
        } else {
            $students = Student::orderBy('created_at', 'DESC')->where(['admin_id' => $authUser->created_by, 'lib_id' => NULL])->get();
        }

        return view('admin.library.edit', compact('library', 'usersWithRole', 'managers', 'students'));
    }


    public function update(Request $request, Library $library)
    {
        $request->validate([
            'library_name' => ['required', Rule::unique('libraries', 'library_name')->ignore($library->id)],
            'address' => 'required|',
            'logo' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {

            $data = $request->all();
            // LOGO IMG
            if ($request->hasFile('logo')) {
                // Delete the old image if it exists
                if (is_file(public_path($library->logo))) {
                    unlink(public_path($library->logo));
                }
                $logoImg = $request->file('logo');
                $filename = uniqid() . '.' . $logoImg->getClientOriginalExtension();
                $logoImg->move(public_path('library_logo'), $filename);
                $data['logo'] = 'library_logo/' . $filename;
            }

            $authUser = Auth::user();
            if ($authUser->hasRole('superadmin')) {
                $data['status'] = "approved";
                $data['admin_id'] = $request->admin_id;

                if (isset($request->status)) {
                    $data['status'] = $request->status;
                } else {
                    $data['status'] = 'pending';
                }
            } else {
                $data['admin_id'] = Auth::id();
            }
            // $data['status'] = 'pending';
            // $data['created_id'] = Auth::id();
            if (isset($request->manager_id)) {
                $data['manager_id'] = json_encode($request->manager_id);
            }
            if (isset($request->student_id)) {
                Student::whereIn('id', $request->student_id)->update([
                    'lib_id' => $library->id
                ]);
            }

            $library->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'Library Update successfull .!');
    }



    public function destroy(Library $library)
    {
        DB::beginTransaction();
        try {
            $library->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', 'library cannot be deleted!');
        }
        DB::commit();
        return redirect()->back()->with('status', 'library deleted successfully!');
    }


    // public function libraryStatusUpdate($id)
    // {
    //     $studentBlock = Library::find($id);
    //     $studentBlock->active_status = $studentBlock->active_status == 'active' ? 'block' : 'active';
    //     $studentBlock->update();
    //     return redirect()->back()->with('status',  $studentBlock->name . ' Student status has been updated.');
    // }

    public function libraryManagerRemove($manager_id, $lib_id)
    {
        $library = Library::find($lib_id);
        $managers = $library->manager_id;
        $newManagers = array_diff(json_decode($managers), [$manager_id]);
        $arr = !empty($newManagers) ? json_encode($newManagers) : NULL;
        $library->manager_id = $arr;
        $library->update();
        return redirect()->back()->with('status', 'Manager Removed successfull .!');
    }


    public function libraryStudentView($admin_id, $manager_id, $lib_id, Request $request)
    {
        $query = Student::where('lib_id', $lib_id);
        if ($manager_id == 0) {
            $query->where(['admin_id' => $admin_id, 'manager_id' => NULL]);
        } else {
            $query->where('manager_id', $manager_id);
        }
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('personal_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status', 'like', '%' . $searchTerm . '%');
            });
        }

        $students = $query->paginate(10);
        return view('admin.library.view_student', compact('students', 'admin_id', 'manager_id', 'lib_id'));
    }


    public function libraryStudentRemove($student_id)
    {
        Student::find($student_id)->update(['lib_id' => NULL]);
        return redirect()->back()->with('status', 'Student Removed successfull .!');
    }


    public function libraryUpdateStatus($id){
        $library = Library::findOrFail($id);
        $library->status = ($library->status === 'pending') ? 'approved' : 'pending';
        $library->save();
        return redirect()->back()->with('status', 'Status change successfull .!');

    }
}
