<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserHomepageController extends Controller
{
    public function index()
    {
        return view('user.homepage.index', [
            'user' => Auth::user()
        ]);
    }
}
