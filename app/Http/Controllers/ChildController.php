<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Children - List & Search
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        // فلتر الأطفال الذين لم يسجلوا أي قراءة سكر
$withoutReadings = $request->query('without_readings') === '1';

        $children = Child::with('user')
    ->when($withoutReadings, function ($query) {
        $query->whereDoesntHave('glucoseReadings');
    })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where(
                              'name',
                              'like',
                              '%' . $search . '%'
                          );
                      });
                });
            })
            ->latest()
            ->get();

        return view('children', compact('children', 'search'));
    }

    /*
    |--------------------------------------------------------------------------
    | Children - Details
    |--------------------------------------------------------------------------
    */

    public function show(Child $child)
    {
        // جلب بيانات ولي الأمر المرتبط بالطفل
        $child->load('user');

        return view('children-show', compact('child'));
    }
}