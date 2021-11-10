<?php
namespace App\Models;
include_once('lib/database.php');
use Database\DB;

class WishlistModel {
    public $conn;
    private $table = 'tb_wishlist';
    public function __construct(){
        $this->conn = new DB();
    }
    public function get ($id = -1){
        if($id == -1){
            return $this->conn->getArray($this->table);
        }
        return $this->conn->getRowArray($this->table,$id);
    }
    public function getForUser ($user_id){
        $where = 'user_id= "'.$user_id.'"';
        $sql = 'SELECT * FROM '. $this->table . ' WHERE '. $where;
        return $this->conn->query($sql);
    }
    public function create($data){
       return $this->conn->insert($this->table,$data);
    }
    public function delete($id){
        return $this->conn->delete($this->table,$id);
    }
}
