<?php

namespace classe\Model;

Use \classe\DB\Sql;
Use \classe\Model;

class Product extends Model {
    

    public static function listAll(){

        $sql = new Sql();

        return $sql->select("SELECT * from tb_products ORDER BY desproduct");
    }

    public function save(){

        $sql = new Sql();
        
        //pdesperson VARCHAR(64), pdeslogin VARCHAR(64), pdespassword VARCHAR(256), pdesemail VARCHAR(128), pnrphone BIGINT, pinadmin TINYINT
        $result = $sql->select("CALL sp_products_save(:idproduct,:desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)",array(

            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()

        ));
        
        $this->setData($result[0]);


    }
    public function get($idproducts){

        $sql = new Sql();

        $result = $sql->select("SELECT * from tb_products where idproduct = :idproducts",[

            ":idproducts"=>$idproducts

        ]);

        $this->setData($result[0]);

    }

    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct =:idproduct",[

            
            ":idproduct"=>$this->getidproduct()
        ]);

    }

    public function checkPhoto(){

        if (file_exists($_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . "ecommerce" . DIRECTORY_SEPARATOR. 
            "res" . DIRECTORY_SEPARATOR. 
            "site" . DIRECTORY_SEPARATOR . 
            "img" . DIRECTORY_SEPARATOR .
            "product" .DIRECTORY_SEPARATOR . 
            $this->getidproduct().".jpg")) 
        {
           $url = "/ecommerce/res/site/img/product/".$this->getidproduct().".jpg";
        }else{

            $url = "/ecommerce/res/site/img/product.jpg";
        }

        return $this->setdesphoto($url);


    }

    public function getValues(){

        $this->checkPhoto();

        $value = parent::getValues();

        return $value;

    }

    public function setPhoto($file){

        $extension = explode('.', $file['name']); 
        $extension = end($extension);

        switch ($extension) {
            case 'jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'gif':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'png':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;            
            default:
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
        }

        $dest = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . 
            "ecommerce" . DIRECTORY_SEPARATOR. 
            "res" . DIRECTORY_SEPARATOR. 
            "site" . DIRECTORY_SEPARATOR . 
            "img" . DIRECTORY_SEPARATOR .
            "product" .DIRECTORY_SEPARATOR . 
            $this->getidproduct().".jpg";
        echo $dest;
        imagejpeg($image, $dest);

        imagedestroy($image);

        $this->checkPhoto();

    }


}
