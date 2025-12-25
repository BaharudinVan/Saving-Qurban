<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Animal::orderBy('name', 'ASC');
            return DataTables::of($data)
                // ->addIndexColumn()
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
        return view('animal.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('animal.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:animals,name',
            'uom' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        Animal::create([
            'name' => strtoupper($request->name),
            'uom' => strtoupper($request->uom),
        ]);

        // $user->assignRole($request->roles);

        return response()->json(['res' => 200]);
    }

    public function show($user)
    {
        return response()->json(Animal::find($user));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('animal.edit', compact('user', 'roles'));
    }

    public function update(Request $request, Animal $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:animals,name,' . $user->id,
            'uom' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $user->update([
            'name' => strtoupper($request->name),
            'uom' => strtoupper($request->uom),
        ]);

        // $user->assignRole($request->roles);

        return response()->json(['res' => 200]);
    }

    public function destroy(Animal $user)
    {
        $user->delete();
        return redirect()->route('animal.index');
    }
}
