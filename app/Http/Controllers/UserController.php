<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use function Laravel\Prompts\password;
class UserController extends Controller
{
    public function login(Request $request)
    {
        $user=User::where('email', $request->email)->first();
        if($user!=null){
            if($user->password==$request->password){
                return redirect("/")->with('user', $user);
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

            return redirect("/")->with('user', $user);
        }
        
        
    }
}
