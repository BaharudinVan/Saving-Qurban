<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Saving;
use App\Models\SavingDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SavingTransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SavingDetail::orderBy('id', 'ASC');
            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->savings->user->name . " |" . $row->savings->user->whatsapp;
                })
                ->addColumn('animal_name', function ($row) {
                    return $row->savings->animal->name;
                })
                ->addColumn('qty', function ($row) {
                    return number_format($row->savings->qty, 0, ',', '.');
                })
                ->addColumn('amount', function ($row) {
                    return 'Rp. ' . number_format($row->amount, 2, ',', '.');
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
        $dataUser = Saving::get();
        return view('savings.transaction', compact('dataUser'));
    }

    // public function create()
    // {
    //     $roles = Role::all();
    //     return view('savings.create', compact('roles'));
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'name' => 'required|exists:savings,id',
            'nominal' => 'required|numeric',
            'evidence' => 'required|file|unique:saving_details,evidence',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $savings = SavingDetail::create([
            'date' => $request->date,
            'saving_id' => $request->name,
            'amount' => $request->nominal,
        ]);
        if ($request->hasFile('evidence')) {
            if ($savings->evidence && Storage::disk('public')->exists($savings->evidence)) {
                Storage::disk('public')->delete($savings->evidence);
            }
            $path = $request->file('evidence')->store('evidence', 'public');
            // simpan path ke database
            $savings->evidence = $path;
            $savings->save();
        }

        return response()->json(['res' => 200]);
    }

    public function show($user)
    {
        $showDetail = SavingDetail::find($user);
        return response()->json([$showDetail, $showDetail->savings->user->whatsapp ?? '']);
    }

    // public function edit(User $user)
    // {
    //     $roles = Role::all();
    //     return view('savings.edit', compact('user', 'roles'));
    // }

    public function update(Request $request, $saving)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'name' => 'required|exists:savings,id',
            'nominal' => 'required|numeric',
            'evidence' => 'nullable|file|unique:saving_details,evidence,' . $saving,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $dataSaving = SavingDetail::where('id', $saving)->first();
        $dataSaving->update([
            'date' => $request->date,
            'saving_id' => $request->name,
            'amount' => $request->nominal,
        ]);
        if ($request->hasFile('evidence')) {
            if ($dataSaving->evidence && Storage::disk('public')->exists($dataSaving->evidence)) {
                Storage::disk('public')->delete($dataSaving->evidence);
            }
            $path = $request->file('evidence')->store('evidence', 'public');
            // simpan path ke database
            $dataSaving->update([
                'evidence' => $path
            ]);
        }

        return response()->json(['res' => 200]);
    }

    public function destroy(SavingDetail $user)
    {
        Storage::delete($user->evidence);
        $user->delete();
        return redirect()->route('saving-transaction');
    }

    public function viewTabungan($id)
    {
        $data = User::where('whatsapp', base64_decode($id))->pluck('id')->first();
        if ($data == null) {
            abort(404);
        }
        $savingData = Saving::where('user_id', $data)->first();
        $amountSave = SavingDetail::where('saving_id', $savingData->id ?? null)->sum('amount');
        return view('savings-detail.index', compact('data', 'savingData', 'amountSave'));
    }

    public function listTabungan(Request $request)
    {
        if ($request->ajax()) {
            $idSaving = Saving::where('user_id', $request->reqData)->pluck('id')->first();
            $data = SavingDetail::where('saving_id', $idSaving)->orderBy('date', 'DESC');
            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return '<i class="fa fa-eye showFile" style="cursor:pointer" data-id="' . $row->id . '"></i>';
                })
                // ->addColumn('user_name', function ($row) {
                //     return $row->user->name . " |" . $row->user->whatsapp;
                // })
                // ->addColumn('animal_name', function ($row) {
                //     return $row->animal->name;
                // })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount, 0, ',', '.');
                })
                // ->addColumn('nominal', function ($row) {
                //     return 'Rp. ' . number_format($row->nominal, 2, ',', '.');
                // })
                // ->addColumn('action', function ($row) {
                //     $actionBtn = "<span data-toggle='tooltip' data-placement='top' title='View'>
                //     <a data-toggle='modal' class='mr-2 view'
                //         style='color: #28a745; cursor: pointer;'
                //         data-target='$row->id'><i
                //             class='fa fa-eye'></i></a></span>";
                //     $nameUser = $row->period . '-' . ($row->user->name ?? '') . '-' . ($row->animal->name ?? '');
                //     $actionBtn .= "<a id='$row->id' data-toggle='tooltip' data-target='$nameUser'
                //     data-placement='top' title='Delete'
                //     style='color: #dc3545; cursor: pointer;' class='delete'><i
                //         class='fa fa-trash'></i></a>";
                //     return $actionBtn;
                // })
                ->rawColumns(['evidence', 'is_admin'])
                ->make(true);
        }
    }
}
