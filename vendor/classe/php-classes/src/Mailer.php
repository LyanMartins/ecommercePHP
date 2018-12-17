<?php

namespace Hcode;

use Rain\Tpl;

class Mailer
{

    const USERNAME = "lyan.martins3@gmail.com";
    const PASSWORD = "?pass?";
    const NAME_FROM = "Hcode store";

    private $mail;

    public function __construct($toAddress,$toName,$subject,$tplName,$data = array()){

        //CODIGO DO ENVIO DE EMAIL

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/ecommerce/views/email",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/ecommerce/views-cache/",
            "debug"         => false // set to false to improve the speed
           );

        Tpl::configure( $config );         

        $tpl = new Tpl; 

        foreach ($data as $key => $value) {
            $tpl->assign($key,$value);
        }

        $hmtl -> $tpl->draw($tplName,true);

        $this->mail = new \PHPMailer;
        
        $this->mail->isSMTP();

        $this->mail->SMTPDebug = 0;

        $this->mail->Debugoutput = "html";

        $this->mail->Host = "smtp.gmail.com";

        $this->mail->Port= 587;

        $this->mail->SMTPSecure = 'tls';

        $this->mail->SMTPAuth = true;

        $this->mail->Username = Mailer::USERNAME;

        $this->mail->Password = Mailer::PASSWORD;

        $this->mail->setFrom(Mailer::USERNAME,Mailer::NAME_FROM);

        $this->mail->addAdress($toAddress,$toName);

        $this->mail->Subject = $subject;

        $this->mail->msgHTML($hmtl);

        $this->mail->AltBody = "";

    }

    public function send(){

        return $this->mail->send();

    }
}
