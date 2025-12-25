<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('name', 'ASC');
            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('is_admin', function ($row) {
                    $actionBtn = '<span class="badge ' . ($row->is_admin ? 'badge-success' : 'badge-danger') . '">' . ($row->is_admin ? 'YES' : 'NO') . '</span>';
                    return $actionBtn;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "<span data-toggle='tooltip' data-placement='top' title='View'>
                    <a data-toggle='modal' class='mr-2 view'
                        style='color: #28a745; cursor: pointer;'
                        data-target='$row->id'><i
                            class='fa fa-eye'></i></a></span>";
                    $actionBtn .= "<a id='$row->id' data-toggle='tooltip' data-target='$row->name'
                    data-placement='top' title='Delete'
                    style='color: #dc3545; cursor: pointer;' class='delete'><i
                        class='fa fa-trash'></i></a>";
                    return $actionBtn;
                })
                ->rawColumns(['action', 'is_admin'])
                ->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|digits_between:8,15|unique:users,whatsapp',
            'password' => 'required|min:6',
            'isAdmin' => 'required|in:1,0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'whatsapp' => $request->whatsapp,
            'is_admin' => filter_var($request->isAdmin, FILTER_VALIDATE_BOOLEAN),
        ]);

        // $user->assignRole($request->roles);

        return response()->json(['res' => 200]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'whatsapp' => 'required|digits_between:8,15|unique:users,whatsapp,' . $user->id,
            'password' => 'nullable|min:6',
            'isAdmin' => 'required|in:1,0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => Hash::make($request->password),
            'whatsapp' => $request->whatsapp,
            'is_admin' => filter_var($request->isAdmin, FILTER_VALIDATE_BOOLEAN),
        ]);
        if ($request->password != null) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // $user->assignRole($request->roles);

        return response()->json(['res' => 200]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
