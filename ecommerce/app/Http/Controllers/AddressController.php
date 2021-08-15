<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AddressController extends Controller
{
    public function addOnCheckout($request, $userId){

            $address = new Address();
            $address->address1 = $request->address1;
            if(empty($request->address2)){
                $address->address2 = '';
            } else{
                $address->address2 = $request->address2;
            }
            $address->country = $request->country;
            $address->zip_code = $request->zipCode;
            $address->user_id = $userId;
            $address->save();

            return $address->id;
    
    }

    public function show($id){
        $address = DB::table('addresses')
        ->select('addresses.id', 'addresses.address1', 'addresses.address2', 'addresses.country', 'addresses.zip_code')
        ->where('addresses.user_id', '=', Auth::id())
        ->where('addresses.id', '=', $id)
        ->first();

    return $address;
    }

    // public function show($id){
    //     return Address::firstWhere('user_id', $id);
    // }

    public function customerAddresses(){

    $addresses = DB::table('addresses')
                ->select('addresses.id', 'addresses.address1', 'addresses.address2', 'addresses.country', 'addresses.zip_code')
                ->where('addresses.user_id', '=', Auth::id())
                ->limit(3)
                ->get();

    return $addresses;

    }

    public function destroy($addresses){
        DB::table('addresses')
        ->whereIn('id', $addresses)
        ->delete();
    }

    public function add($address1, $address2, $zipCode, $country)
    {

        $address = new Address;
        $address->address1 = $address1;
        $address->address2 = $address2;
        $address->zip_code = $zipCode;
        $address->country = $country;
        $address->user_id = Auth::id();
        $address->save();

    }

    public function edit($id, $address1, $address2, $zipCode, $country)
    {

        $address = Address::find($id);
        $address->address1 = $address1;
        $address->address2 = $address2;
        $address->zip_code = $zipCode;
        $address->country = $country;
        $address->save();
    }
}
