<?php

namespace App\Http\Controllers;

use App\Mail\PasswordAssignedUserMail;
use App\Models\Library;
use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\UserReqNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class RegistrationRequestController extends Controller
{

    public function registrationRequest()
    {
        return view('auth.registrationRequest');
    }

    public function registrationRequestStore(Request $request, RegistrationRequest $registrationReq)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:registration_requests,email',
            'library_name' => 'required',
            'contact_number' => 'required|numeric|digits_between:10,15|unique:registration_requests,contact_number',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['status'] = "pending";

            if ($request->hasFile('image')) {
                $logoImg = $request->file('image');
                $filename = uniqid() . '.' . $logoImg->getClientOriginalExtension();
                $logoImg->move(public_path('library_logo'), $filename);
                $data['image'] = 'library_logo/' . $filename;
            }

            $registrationReq = RegistrationRequest::create($data);

            $notificationData = [
                'regiReq_id' => $registrationReq->id,
                'is_seen' => "unseen"
            ];
            UserReqNotification::create($notificationData);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Your request has been sent successfully. Registration will be done as soon as the details are assigned.!");
    }


    // Backend side
    public function registrationRequestIndex(Request $request)
    {
        $search = $request->input('search');
        $regiReq = RegistrationRequest::where('full_name', 'like', "%$search%")
            ->orWhere('library_name', 'like', "%$search%")
            ->orWhere('contact_number', 'like', "%$search%")
            ->orWhere('library_address', 'like', "%$search%")
            ->orWhere('status', 'like', "%$search%")
            ->paginate(10);
        return view('admin.registrationRequest.all', compact('regiReq'));
    }


    public function registrationRequestEdit(RegistrationRequest $registrationReq, $id)
    {
        $registrationReq = RegistrationRequest::find($id);
        return view('admin.registrationRequest.edit', compact('registrationReq'));
    }


    public function registrationRequestUpdate(Request $request, RegistrationRequest $registrationReq)
    {
        $request->validate([
            'full_name' => 'required',
            'library_name' => ['required', Rule::unique('registration_requests', 'library_name')->ignore($request->id)],
            'contact_number' => [
                'required', 'numeric', 'digits_between:10,15',
                Rule::unique('registration_requests', 'contact_number')->ignore($request->id),
            ],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $registrationReq = RegistrationRequest::find($request->id);

            //IMG
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if (is_file(public_path($registrationReq->image))) {
                    unlink(public_path($registrationReq->image));
                }
                $logoImg = $request->file('image');
                $filename = uniqid() . '.' . $logoImg->getClientOriginalExtension();
                $logoImg->move(public_path('library_logo'), $filename);
                $data['image'] = 'library_logo/' . $filename;
            } else {
                $data['image'] = NULL;
            }

            if (isset($request->status)) {
                $data['status'] = $request->status;

                // Check if the status is 'approved'
                if ($request->status == 'approved') {
                    // Create or update user
                    $user = User::updateOrCreate(
                        ['email' => $registrationReq->email],
                        [
                            'name' => $registrationReq->full_name,
                            'email' => $registrationReq->email,
                            'password' => NULL,
                            'plan' => "paid",
                            'add_student_limit'=>'unlimited',
                            'register_type' => "external"
                        ]
                    );

                    // Assign the 'admin' role to the user
                    $user->assignRole('admin');

                    // Save data to the libraries table
                    $library = Library::updateOrCreate(
                        ['admin_id' => $user->id],
                        [
                            'created_id' => $user->id,
                            'library_name' => $registrationReq->library_name,
                            'address' => $registrationReq->library_address,
                            'logo' => $data['image'],
                            'status' => 'approved',
                        ]
                    );

                    // Update user notification
                    $userNotification = UserReqNotification::where('regiReq_id', $request->id)->first();
                    $userNotification->update(['is_seen' => "seen"]);
                }
            } else {
                DB::rollBack();
                return redirect()->back()->with('status', "Please update user status to approved.");
            }
            $registrationReq->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', "Registration Request updated successfully.");
    }



    public function registrationRequestDestroy($id)
    {
        DB::beginTransaction();
        try {
            RegistrationRequest::find($id)->delete();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Registration Request Deleted successfully. ");
    }

    // Chnage Status
    public function updateStatus(Request $request, $id)
    {
        $registrationReq = RegistrationRequest::findOrFail($id);
        $originalStatus = $registrationReq->status;
        // Update the status
        $registrationReq->status = ($originalStatus == 'pending') ? 'approved' : 'pending';
        $registrationReq->save();

        $userNotification = UserReqNotification::where('regiReq_id', $request->id)->first();
        // Check if the status changed to 'approved'
        if ($registrationReq->status == 'approved') {
            $userNotification->update(['is_seen' => "seen"]);
        } else {
            $userNotification->update(['is_seen' => "unseen"]);
        }
        return redirect()->back()->with('status', 'Status updated successfully.');
    }


    public function asignPasswordUser(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm-password',
        ]);

        DB::beginTransaction();
        try {
            $regiReqUser = RegistrationRequest::find($request->id);

            if ($regiReqUser->status == "approved") {
                // Check if the user already exists
                $user = User::where('email', $regiReqUser->email)->first();

                if (!$user) {
                    $user = new User(['email' => $regiReqUser->email]);
                }
                // Set the password for the Request user
                $user->password = Hash::make($request->password);
                $user->normal_password = $request->password;
                $user->save();
                $data = [
                    'name' => $regiReqUser->full_name,
                    'email' => $regiReqUser->email,
                    'password' => $request->password,
                ];

                // Send email to the user
                Mail::to($user->email)->send(new PasswordAssignedUserMail($data));
            } else {
                return redirect()->back()->with('status', "Please approve this user's status before assigning the password.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', "Password Assigned & email sent successfully!");
    }
}
