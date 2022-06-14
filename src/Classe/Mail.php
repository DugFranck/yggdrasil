<?php
namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '39330ff9d259599ee10343d5da3bca24';
    private $api_Key_secret ='e0748471f0ac6a21ec627175160e2297';

    public function send($to_email, $to_name, $subject, $content)
    {

        $mj = new Client($this->api_key, $this->api_Key_secret,true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fullcreating63@gmail.com",
                        'Name' => "L'Atelier Yggdrasil"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],

                    'TemplateID' => 3877917,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}