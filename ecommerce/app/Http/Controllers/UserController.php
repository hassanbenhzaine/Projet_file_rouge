<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{

    public function customers($limit, $searchQuery){

        if(isset($searchQuery)){
            $customers = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.phone', 'users.created_at', 'users.updated_at')
            ->where('users.is_admin', '=', 0)
            ->where('users.name', 'like', '%'.$searchQuery.'%')
            ->orWhere('users.email', 'like', '%'.$searchQuery.'%')
            ->paginate($limit);
        } else{
            $customers = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.phone', 'users.created_at', 'users.updated_at')
            ->where('users.is_admin', '=', 0)
            ->paginate($limit);
        }

        return $customers;

    }

    public function destroy($customers){
        DB::table('users')
        ->whereIn('id', $customers)
        ->delete();

    }

    public function showCustomer($id){
        $customer =  DB::table('users')
         ->where('id', '=', $id)
         ->where('is_admin', '=', 0)
         ->first();
 
         return $customer;
     }

     public function edit($id, $name, $email, $phone, $password)
     {
         $user = User::find($id);
         $user->name = $name;
         $user->email = $email;
         $user->phone = $phone;
         if($password == null || $password == '********'){
            
         }else{
            $user->password = Hash::make($password);
         };
         
         $user->save();
 
     }
}

