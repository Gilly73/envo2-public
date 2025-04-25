<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // for POSTMAN testing
    public function index()
    {
        return "This is the index method.";
    }

    public function create()
    {
        return view('test.create'); 
    }
  
    public function store(Request $request)
    {
        // For testing, just return the data:
        return "Data received: " . json_encode($request->all());

    }

}