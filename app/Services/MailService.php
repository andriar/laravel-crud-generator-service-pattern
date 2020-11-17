<?php 

namespace App\Services;

use App\Models\User;
use Mail;

class MailService
{
    protected $senderName = 'EXAMPLE NAME';
    protected $senderMail = 'andriar@mymindstores.com';

    public function verificationMail($payload)
    {
        $toName = $payload['full_name'];
        $toMail = $payload['email'];
        $objectMail = "Laravel Test Mail";
        $titleMail = "Verification Email";
        $data = array(
            "name" => $this->senderName, 
            "body" => "A test mail"
        );
        
        Mail::send("emails.mail", $data, 
            function($message) use ($toName, $toMail) 
            {
                $message->to($toMail, $toName)->subject($titleMail);
                $message->from($this->$senderMail, $objectMail);
            }
        );
    }
}
