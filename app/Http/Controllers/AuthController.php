<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/auth",
     *      
     *      tags={"Auth"},
     *      summary="Authenticates user",
     *      description="Returns authenticated user",
     *      @OA\Parameter(
     *          name="email",
     *          description="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="email"
     *          )
     *      ),
     * 
     *      @OA\Response(
     *          response=200,
     *          description="{}"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     */

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(["error" => null, "error" => "Unauthenticated"], 400);
        }

        $user = User::where('email', Auth::user()->email)->first();
        $token = 'Bearer ' . $user->createToken('maxycare')->accessToken;

        return response()->json(["success" => $token, "error" => null]);
    }

    public function profile()
    {
        return response()->json(Auth::user());
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'surname' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        ]);
        try {
           User::find( Auth::user()->id)->update($data);
           return response()->json(['success' => 'user has been updated!', 'error' => null]);
        } catch (\Throwable $th) {
            return response()->json(['success' => null, 'error' => $th->getMessage()]);
        }
    }

    public function logout()
    {
        # code...
    }

    public function passwordReset(Request $request)
    {
        $request->validate(['password' => 'required']);

        try {
            User::find( Auth::user()->id)->update(['password' => Hash::make($request->password)]);
            return response()->json(['success' => 'user has been updated!', 'error' => null]);
         } catch (\Throwable $th) {
             return response()->json(['success' => null, 'error' => $th->getMessage()]);
         }
    }
}
