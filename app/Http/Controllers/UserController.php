<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function login(Request $request)
    {
        $user=User::where('email', $request->email)->first();
        if($user!=null){
            if($user->password==$request->password){
                session(['user' => $user]);
                return redirect("/");
            }else{
                return redirect("login");
            }
        }else{
            return redirect("signup");
        }
        
        
    }

    public function signup(Request $request){
        
        if (User::where('email', $request->email)->exists()) {
            return redirect("login");
        } else {
            $user =(new User());
            $user->name=$request->name;
            $user->email=$request->email;
            $user->type=$request->type;
            $user->password=$request->password;
            $user->save();
            session(['user' => $user]);
            return redirect("/");
        }
        
        
    }

    // public function logout(){
    //     session()->flush();
    //     return redirect("/");
    // }
}
