<?php

namespace classe\Model;

Use \classe\DB\Sql;
Use \classe\Model;

class User extends Model {
    
    const SESSION = "User";


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

}
