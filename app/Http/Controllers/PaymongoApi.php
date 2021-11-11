<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

class PaymongoApi extends Controller
{
    public function view()
    {
        $webhook = Paymongo::webhook()->all();
        /*  $webhook = Paymongo::webhook()->create([
            'url' => 'http://127.0.0.1:9000/school/payment/billing-transaction/success',
            'events' => [
                'source.chargeable'
            ]
        ]); */
        return view('administrator.paymongo.view', compact('webhook'));
    }
    public function paymongo_sources(Request $_request)
    {
        $gcashSource = Paymongo::source()->create([
            'type' => 'gcash',
            'amount' => 100.00,
            'currency' => 'PHP',
            'redirect' => [
                'success' => 'http://127.0.0.1:9000/school/payment/billing-transaction/success',
                'failed' => 'http://127.0.0.1:9000/school/payment/billing-transaction/failed'
            ],
            'billing' => [
                'address' => [
                    'line1' => $_request->input('_street'),
                    'line2' => $_request->input('_barangay'),
                    'city' => $_request->input('_city'),
                    'state' => $_request->input('_province'),
                    'country' => 'PH',
                    'postal_code' => $_request->input('_zip_code'),
                ],
                'name' => ucwords($_request->input('_first_name') . ' ' . $_request->input('_last_name')),
                'email' => $_request->input('_email'),
                'phone' => $_request->input('_contact_number'),
            ], 
        ]);
        return response()->json($gcashSource);
    }
    public function paymongo_view()
    {
        $webhook = Paymongo::webhook()->all();
        return json_encode($webhook);
    }
}
