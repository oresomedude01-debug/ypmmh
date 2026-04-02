<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // In a real application, you would fetch data from your database
        // Example:
        // $students = Student::latest()->take(5)->get();
        // $totalStudents = Student::count();
        // $totalTeachers = Teacher::count();
        // etc.

        return view('pages.dashboard');
    }
}
