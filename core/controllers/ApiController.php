<?php

namespace core\controllers;

class ApiController
{
    /**
     * @return \core\router\Response
     * @throws \core\router\NoAuthException
     */
    public function getMe()
    {
        if (!auth()->isLoggedIn()) {
            throw new \core\router\NoAuthException('You are not logged in');
        }

        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function login()
    {
        $email = request()->post('email');
        $pass = request()->post('password');

        if (auth()->login($email, $pass)) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Login successful',
                'user' => auth()->user(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Login failed',
        ], 401);
    }
}
