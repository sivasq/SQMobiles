<?php

namespace App\Http\Controllers\MobileApi;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class BranchController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch_id = Auth::user()->branch_id;
        $data = DB::table('branches')
            ->where('id', '!=', $branch_id)
            ->get();
        $parsedData = collect($data);

        $collection = $parsedData->map(function ($item) {
            return ['id' => $item->id, 'branch_name' => $item->branch_name . '-' . $item->branch_location];
        });
        return $this->sendResponse($collection, 'Branch Retrieved Successfully.');
    }
}
