<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
class MedicineController extends Controller
{
    public function index() {
        $medicines = Medicine::orderBy('name')->get();
        return view('pharmacy.medicine.index', compact('medicines'));
    }

    public function create() {
        return view('pharmacy.medicine.create');
    }
}
