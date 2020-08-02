<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends Model
{
    protected $table = 'transactions';



    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            //Валидация пользователя
            $user = User::where('id', $data['user_id'])->first();
            if (!$user) {
                return ['status' => ['error' => 'invalid user id']];
            }

            //Валидация типа транзакции
            if (!in_array(strtolower($data['type']), ['income', 'expense'])) {
                return ['status' => ['error' => 'invalid transaction type']];
            }

            //Валидация даты
            if (DateTime::createFromFormat('Y-m-d H:i:s', $data['date']) === FALSE) {
                return ['status' => ['error' => 'invalid date']];
            }

            $datetime = new DateTime($data['date']);
            // echo ('http://www.cbr.ru/scripts/XML_daily.asp?date_req='.$datetime->format('d/m/yy'));
            // die();

            $xml = simplexml_load_string(file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $datetime->format('d/m/yy')));


            $amount = 0;
            foreach ($xml as $element) {
                if ($element->CharCode == strtoupper($user->currency)) {
                    $amount = number_format(number_format((float)$element->Value, 2) * number_format((float)$data['amount'], 2), 2);
                }
            }

            $transaction = new Transaction();
            $transaction->date = $data['date'];
            $transaction->user_id = $data['user_id'];
            $transaction->amount = $amount;
            $transaction->type = $data['type'];
            $transaction->save();

            return ['transaction_id' => $transaction->id, 'status' => 'ok'];
        }
    }

    public function getTransactionsByUser($userId, $page = 1, $sort = NULL)
    {
        $result = [];
        if ($sort == 'desc') {
            $transactions = Transaction::where('user_id', $userId)
                ->offset(($page - 1) * 10)
                ->limit(10)
                ->orderByDesc('date')
                ->get();
        } else {
            $transactions = Transaction::where('user_id', $userId)
                ->offset(($page - 1) * 10)
                ->limit(10)
                ->get();
        }

        foreach ($transactions as $index => $transaction) {
            $result[$index]['date'] = $transaction['date'];
            $result[$index]['amount'] = $transaction['amount'];
            $result[$index]['type'] = $transaction['type'];
        }
        return $result;
    }

    public function getTransactionsGroup($page = 1)
    {
        $users = User::all();
        $result = [];
        foreach ($users as $index => $user) {
            $transactions = Transaction::where('user_id', $user['id'])->offset(($page - 1) * 10)->limit(10)->get();
            $amount = 0;
            foreach ($transactions as $transaction) {
                if ($transaction['type'] == 'expense') {
                    $amount = $amount - $transaction['amount'];
                } else {
                    $amount = $amount + $transaction['amount'];
                }
                $result[$transaction['date']]['user_id'] = $user['id'];
                $result[$transaction['date']]['nickname'] = $user['nickname'];
                $result[$transaction['date']]['amount'] = abs($amount);
                if($amount < 0){
                    $result[$transaction['date']]['type'] = 'expense';
                }else{
                    $result[$transaction['date']]['type'] = 'income';
                }
            }
        }
        return $result;
    }
}
