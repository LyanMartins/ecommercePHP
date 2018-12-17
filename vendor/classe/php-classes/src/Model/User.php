<?php

namespace classe\Model;

Use \classe\DB\Sql;
Use \classe\Model;

class User extends Model {
    
    const SESSION = "User";
    const SECRET = "HcodePhp7_secret" ;

    public static function login($login,$password)
    {
        $sql = new Sql();
        
        $result = $sql->select("SELECT * from tb_users WHERE deslogin = :login", array(
            ":login"=> $login
        ));
        if(count($result) == 0){
            throw new \Exception("Usuario inexistente ou senha invalida!!", 1);
            
        }

        $data = $result[0];
        if (password_verify($password,$data['despassword'])){
            
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        }else{

            throw new \Exception("Usuario inexistente ou senha invalida!?", 1);

        }
    }
    public static function verifyLogin( $inadmin = true){

        if (!isset($_SESSION[User::SESSION]) 
            || 
            !$_SESSION[User::SESSION] 
            || 
            !(int)$_SESSION[User::SESSION]['iduser'] > 0
            ||
            (bool)$_SESSION[User::SESSION]['inadmin'] !== $inadmin 
            ) {
            
            header("Location: adm/login");
            exit();

        }
        

    }
    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listAll(){

        $sql = new Sql();

        return $sql->select("SELECT * from tb_users a inner join tb_persons  b USING(idperson) ORDER BY b.desperson");
    }

    public function save(){
        
        $sql = new Sql();
        
        //pdesperson VARCHAR(64), pdeslogin VARCHAR(64), pdespassword VARCHAR(256), pdesemail VARCHAR(128), pnrphone BIGINT, pinadmin TINYINT
        $result = $sql->select("CALL sp_users_save(:pdesperson,:pdeslogin,:pdespassword,:pdesemail,:pnrphone,:pinadmin)",array(

            ":pdesperson"=>$this->getdesperson(),
            ":pdeslogin"=>$this->getdeslogin(),
            ":pdespassword"=>$this->getdespassword(),
            ":pdesemail"=>$this->getdesemail(),
            ":pnrphone"=>$this->getnrphone(),
            ":pinadmin"=>$this->getinadmin()

        ));
        
        $this->setData($result[0]);

    }
    public function get($iduser){

        $sql = new Sql();

        $result = $sql->select("SELECT * from tb_users a inner join tb_persons p USING(idperson) where a.iduser = :iduser",array(
            ":iduser"=>$iduser
        ));

        $this->setData($result[0]);
    }

    public function update(){

        $sql = new Sql();
        
        //pdesperson VARCHAR(64), pdeslogin VARCHAR(64), pdespassword VARCHAR(256), pdesemail VARCHAR(128), pnrphone BIGINT, pinadmin TINYINT
        $result = $sql->select("CALL sp_usersupdate_save(:iduser,:pdesperson,:pdeslogin,:pdespassword,:pdesemail,:pnrphone,:pinadmin)",array(

            ":iduser"=>$this->getiduser(),
            ":pdesperson"=>$this->getdesperson(),
            ":pdeslogin"=>$this->getdeslogin(),
            ":pdespassword"=>$this->getdespassword(),
            ":pdesemail"=>$this->getdesemail(),
            ":pnrphone"=>$this->getnrphone(),
            ":pinadmin"=>$this->getinadmin()

        ));
        
        $this->setData($result[0]);
    }
    public function delete(){
        
        $sql = new Sql();

        $result = $sql->select("CALL sp_users_delete(:iduser)",array(

            ":iduser"=>$this->getiduser()
          

        ));
        
        $this->setData($result[0]);
    }
    public static function getForgot($email){


        $sql = new Sql();

        $results2 = $sql->select("SELECT * FROM tb_persons a
                INNER JOIN tb_users b USING(idperson)
                where desemail = :email", array(
                    ":email" => $email
                )) ;

        if(count($results2) === 0){

            throw new \Exception("Não é possivel recuperar a senha!");

        }else{

            $data = $results2[0];

            $result2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser,:desip)",array(
                "iduser" => $data['iduser'],
                "desip" => $_SERVER['REMOTE_ADDR']
            ));

            if(count($result2) === 0){

                throw new \Exception("Não foi possivel recuperar a senha!");
                
            }else{

                $dataRecover = $result2[0];

                $code = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,User::SECRET,$dataRecover['idrecovery'],MCRYPT_MODE_ECB));

                $link = "http://localhost:8080/ecommerce/admin/forgot/reset";

                $mailer = new Mailer($data['desemail'],$data['desperson'],"Redefinir senha","forgot",array(

                    "name"=>$data['desperson'],
                    "link"=>$link

                ));

                $mailer->send();

                return $data;

            }

        }
        
    }

}
