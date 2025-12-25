<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Saving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Saving::orderBy('id', 'ASC');
            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user->name . " |" . $row->user->whatsapp;
                })
                ->addColumn('animal_name', function ($row) {
                    return $row->animal->name;
                })
                ->addColumn('qty', function ($row) {
                    return number_format($row->qty, 0, ',', '.');
                })
                ->addColumn('nominal', function ($row) {
                    return 'Rp. ' . number_format($row->nominal, 2, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "<span data-toggle='tooltip' data-placement='top' title='View'>
                    <a data-toggle='modal' class='mr-2 view'
                        style='color: #28a745; cursor: pointer;'
                        data-target='$row->id'><i
                            class='fa fa-eye'></i></a></span>";
                    $nameUser = $row->period . '-' . ($row->user->name ?? '') . '-' . ($row->animal->name ?? '');
                    $actionBtn .= "<a id='$row->id' data-toggle='tooltip' data-target='$nameUser'
                    data-placement='top' title='Delete'
                    style='color: #dc3545; cursor: pointer;' class='delete'><i
                        class='fa fa-trash'></i></a>";
                    return $actionBtn;
                })
                ->rawColumns(['action', 'is_admin'])
                ->make(true);
        }
        $dataUser = User::select('id', 'name', 'whatsapp')->orderBy('name', 'ASC')->get();
        $dataAnimal = Animal::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('savings.index', compact('dataUser', 'dataAnimal'));
    }

    // public function create()
    // {
    //     $roles = Role::all();
    //     return view('savings.create', compact('roles'));
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => [
                'required',
                Rule::unique('savings')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->name)
                        ->where('animal_id', $request->animal);
                }),
            ],
            'name' => 'required|exists:users,id',
            'animal' => 'required|exists:animals,id',
            'qty' => 'required|numeric',
            'nominal' => 'required|numeric',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        Saving::create([
            'period' => strtoupper($request->period),
            'user_id' => $request->name,
            'animal_id' => $request->animal,
            'qty' => $request->qty,
            'nominal' => $request->nominal,
            'address' => $request->address,
        ]);

        // $user->assignRole($request->roles);

        return response()->json(['res' => 200]);
    }

    public function show($user)
    {
        return response()->json(Saving::find($user));
    }

    // public function edit(User $user)
    // {
    //     $roles = Role::all();
    //     return view('savings.edit', compact('user', 'roles'));
    // }

    public function update(Request $request, Saving $saving)
    {
        $validator = Validator::make($request->all(), [
            'period' => [
                'required',
                Rule::unique('savings')->ignore($saving->id)->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->name)
                        ->where('animal_id', $request->animal);
                }),
            ],
            'name' => 'required|exists:users,id',
            'animal' => 'required|exists:animals,id',
            'qty' => 'required|numeric',
            'nominal' => 'required|numeric',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $saving->update([
            'period' => strtoupper($request->period),
            'user_id' => $request->name,
            'animal_id' => $request->animal,
            'qty' => $request->qty,
            'nominal' => $request->nominal,
            'address' => $request->address,
        ]);

        return response()->json(['res' => 200]);
    }

    public function destroy(Saving $user)
    {
        $user->delete();
        return redirect()->route('savings.index');
    }
}
