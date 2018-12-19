<?php

namespace classe\Model;

Use \classe\DB\Sql;
Use \classe\Model;

class Category extends Model {
    

    public static function listAll(){

        $sql = new Sql();

        return $sql->select("SELECT * from tb_categories ORDER BY descategory");
    }

    public function save(){

        $sql = new Sql();
        
        //pdesperson VARCHAR(64), pdeslogin VARCHAR(64), pdespassword VARCHAR(256), pdesemail VARCHAR(128), pnrphone BIGINT, pinadmin TINYINT
        $result = $sql->select("CALL sp_categories_save(:idcategory,:idcategory)",array(

            ":idcategory"=>$this->getidcategory(),
            ":idcategory"=>$this->getdescategory()

        ));
        
        $this->setData($result[0]);
        Category::updateFile();


    }
    public function get($idcategory){

        $sql = new Sql();

        $result = $sql->select("SELECT * from tb_categories where idcategory = :idcategory",[

            ":idcategory"=>$idcategory

        ]);

        $this->setData($result[0]);

    }

    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory =:idcategory",[

            
            ":idcategory"=>$this->getidcategory()
        ]);

        Category::updateFile();
    }

    public static function updateFile(){

        $categories = Category::ListAll();

        $html = [];

        foreach ($categories as $row) {
            
            array_push($html, '<li><a href="/ecommerce/categories/'.$row['idcategory'].'">'.$row['descategory'].'</li>');
       
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."ecommerce".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html",implode('',$html));
    }
}
