<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Resources\Branch as BranchResource;
use App\InventoryProductDetail;
use App\User;
use Illuminate\Http\Request;
use Validator;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withTrashed()->get();
        return BranchResource::collection($branches);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|unique:branches',
            'branch_location' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $branch = new Branch();
        $branch->branch_name = $request->branch_name;
        $branch->branch_location = $request->branch_location;
        $branch->save();

        if ($branch->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function update(Request $request, branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|unique:branches,branch_name,' . $branch->id,
            'branch_location' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $branch->branch_name = $request->branch_name;
        $branch->branch_location = $request->branch_location;
        $branch->save();

        if($branch->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function destroy(branch $branch)
    {
        $stockExists = InventoryProductDetail::where('branch_id', $branch->id)->get()->count();
        $userExists = User::where('branch_id', $branch->id)->get()->count();

        if ($userExists == 0 && $stockExists == 0) {
            $branch->forceDelete();
            return response()->json(['status' => 'success', 'message' => 'Branch deleted successfully'], 200);
        }
        $branch->delete();
        return response()->json(['status' => 'success', 'message' => 'Branch soft-deleted successfully'], 200);
    }

    public function un_destroy($branch)
    {
        $restored = Branch::withTrashed()->find($branch)->restore();
        if($restored) {
            return response()->json(['status' => 'success', 'message' => 'Branch Restored successfully'], 200);
        }
    }
}
