<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{



    public function index(Request $request)
    {
                $totalSujets = \App\Models\Sujet::count();
                $sujetsApprouves = \App\Models\Sujet::where('approuve', 1)->count();
                $sujetsNonApprouves = \App\Models\Sujet::where('approuve', 0)->count();
                $totalUsers = \App\Models\User::count();
                $totalCategories = \App\Models\Categorie::count();
                $totalTelechargements = \App\Models\DownloadLog::count();
                $dernierSujets = \App\Models\Sujet::latest()->take(5)->get();
                $dernierUsers = \App\Models\User::latest()->take(5)->get();

                return view('backend.pages.index', compact(
                        'totalSujets', 'sujetsApprouves', 'sujetsNonApprouves',
                        'totalUsers', 'totalCategories', 'totalTelechargements', 'dernierSujets', 'dernierUsers'
                ));
    }
}
