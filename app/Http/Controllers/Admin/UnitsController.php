<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitsController extends Controller
{
    public function __construct(protected UnitService $unitService)
    {
    }

    public function units()
    {
        return view('admin.units.units', ['title' =>"Measurement units"]);
    }

    public function createUnit(){
        return view('admin.units.form');
    }

    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();

        $unit = $this->unitService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully!',
            'data' => $unit
        ]);
    }


    public function editUnit(Unit $unit)
    {
        return view('admin.units.form', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $data = $request->validated();
        $this->unitService->update($unit, $data);
        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully!',
            'data' => $unit
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->is_active = $request->is_active;
        $unit->save();

        return response()->json([
            'message' => 'Status updated successfully'
        ]);
    }
    public function data()
    {
        $query = Unit::select(['id', 'name', 'is_active']);

        return DataTables::of($query)

            // ->addColumn('status', function ($brand) {
            //     return $brand->status == 1
            //         ? '<span class="text-green-600">Active</span>'
            //         : '<span class="text-red-600">Inactive</span>';
            // })

            ->addColumn('status', function ($unit) {
                return '
                    <div x-data="{ switcherToggle: ' . ($unit->is_active == 1 ? 'true' : 'false') . ' }">
                        <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                            <div class="relative">
                                <input type="checkbox"
                                    class="sr-only unitStatusToggler"
                                    data-id="' . $unit->id . '"
                                    x-model="switcherToggle" />

                                <div class="block h-6 w-11 rounded-full"
                                    :class="switcherToggle ? \'bg-green-500 dark:bg-green-500\' : \'bg-gray-200 dark:bg-white/10\'">
                                </div>

                                <div :class="switcherToggle ? \'translate-x-full\' : \'translate-x-0\'"
                                    class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear">
                                </div>
                            </div>

                            <span x-text="switcherToggle ? \'Active\' : \'Inactive\'"></span>
                        </label>
                    </div>';
            })

            ->addColumn('action', function ($unit) {
                return '
                    <div class="flex items-center gap-2">

                        <a href="' . route('admin.unit.edit', $unit->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                            Edit
                        </a>

                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition deleteBtn"
                            data-id="' . $unit->id . '">
                            Delete
                        </button>

                    </div>
                ';
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (strtolower($keyword) === 'active') {
                    $query->where('is_active', 1);
                } elseif (strtolower($keyword) === 'inactive') {
                    $query->where('is_active', 0);
                }
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
