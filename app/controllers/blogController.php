<?php

namespace App\Controllers;

require_once('core/http/Container.php');
require_once('app/services/blogService.php');

use Core\Http\BaseController;
use App\Services\blogService;
class blogController extends BaseController {
    private $blogService;
    public function __construct(){
        $this->blogService = new blogService();
    }
    public function index()
    {
        return $this->blogService->list();
    }
    public function postAdd(){
        $inputJSON = file_get_contents('php://input');
        $req= json_decode( $inputJSON,true ); 
        return $this->blogService->add($req);
    }  
    public function getEdit(){
        $id = (int)$_REQUEST['id'];
        return $this->blogService->getEdit($id);
    }
    public function postEdit(){
        $inputJSON = file_get_contents('php://input');
        $req= json_decode( $inputJSON,true ); 
        $id = (int)$_REQUEST['id'];
        return $this->blogService->postEdit($id,$req);
    }
    public function delete(){
        $id = (int)$_REQUEST['id'];
        return $this->blogService->delete($id); 
    }
}