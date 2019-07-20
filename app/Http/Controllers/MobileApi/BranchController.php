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
        return $this->sendResponse($data, 'Branch Retrieved Successfully.');
    }
}
