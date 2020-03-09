<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="注册",
     *     description="注册",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="昵称",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="邮箱",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="密码",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="注册成功"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('TutsForWeb')->accessToken;

        return response()->json([
            'code' => 0,
            'message' => '注册成功',
            'token' => $token
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="登录",
     *     description="登录",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="邮箱",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="密码",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="登录成功"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="登录失败",
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;
            return response()->json([
                'code' => 0,
                'token' => $token,
                'message' => '登录成功'
            ], 200);
        } else {
            return response()->json([
                'code' => 1,
                'message' => '登录失败，账号或密码错误',
            ], 401);
        }
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json([
            'code' => 0,
            'data' => $user
        ], 200);
    }

    public function logout()
    {
        if (Auth::guard('api')->check()) {
            Auth::guard('api')->user()->token()->delete();
        }

        return response()->json(
            [
                'code' => 0,
                'message' => '登出成功',
            ]
        );
    }
}
