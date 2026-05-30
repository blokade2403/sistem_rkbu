<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingInput\Anggaran;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.dashboard_index');
    }
}
