<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class TransactionController extends BaseController
{

    public function index()
    {

        $xml = simplexml_load_string(file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002'));
        // $json = mb_convert_encoding(file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002'), 'UTF-8', mb_detect_encoding(file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002'), 'UTF-8, ISO-8859-1', true));


        //$json = file_get_contents_utf8('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002');
        echo "<pre>";
        // var_dump($xml);
        foreach($xml as $element){
            // var_dump($element);
            // var_dump($element->CharCode);
            if($element->CharCode == 'AUD'){
                var_dump($element->CharCode, $element->Value);
                break;
            }
        }
        die();

        // return Transaction::all();
        $response = Http::get('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002');
        echo "<pre>";
        var_dump(get_class_methods($response));
        var_dump($response->body());
        die();
        return $response;
    }
}
