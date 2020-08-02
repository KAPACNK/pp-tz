<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class User extends Model
{
    protected $table = 'users';

    public function store(Request $request)
    {

        $currencies = ['AUD', 'GBP', 'BYR', 'DKK', 'USD', 'EUR', 'ISK', 'KZT', 'RUB'];
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user = User::where('nickname', $data['nickname'])->first();
            if ($user) {
                return ['status' => ['error' => 'nickname is alredy exist']];
            }
            if (!in_array(strtoupper($data['currency']), $currencies)) {
                return ['status' => ['error' => 'incorrect currency code']];
            }
            $user = new User();
            $user->nickname = $data['nickname'];
            $user->currency = strtoupper($data['currency']);
            $user->save();
            return ['user_id' => $user->id, 'status' => 'ok'];
        }
    }
}
