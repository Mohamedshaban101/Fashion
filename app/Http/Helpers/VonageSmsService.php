<?php 

namespace App\Http\Services;
use Vonage\Client;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;

class VonageSmsService{

    public $client;
    public function __construct(){
        $basic =new Basic(config('services.vonage.key' , 'services.vonage.secret'));
        $this->client = new Client($basic);
    }

    public function send($to , $message){
        return $this->client->sms->send(
            new SMS(
                $to,
                config('services.vonage.from'),
                $message
            )
        );
    }
}

?>