<?php

use Illuminate\Support\Facades\Route;
use App\Services\NordigenService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (NordigenService $nordigen) {
    $country = "LV";
    $institutions = json_encode($nordigen->getListOfInstitutions($country));
    return view('home', compact('institutions'));
});

Route::get('/agreements/{id}', function (NordigenService $nordigen) {
    $id = request()->route()->parameter('id');
    $redirectUrl = 'http://localhost:8000/results';
    $data = $nordigen->getSessionData($redirectUrl, $id);
    session(['requisitionId' => $data["requisition_id"]]);
    return redirect()->away($data["link"]);
});


Route::get('/results', function (NordigenService $nordigen) {
    $requisitionId = request()->session()->get('requisitionId');
    if(!$requisitionId) throw new Exception('Requisition id not found.');

    $data = $nordigen->getAccountData($requisitionId);
    return response()->json($data, 200, [], JSON_PRETTY_PRINT)
        ->withHeaders([
            'Accept'=> 'application/json'
        ]);
});

