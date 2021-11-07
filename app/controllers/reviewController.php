<?php

namespace App\Controllers;

include_once('app/models/reviewModel.php');
include_once('core/http/Container.php');
require_once('app/validators/reviewValidate.php');
include_once('app/models/placeModel.php');
use App\Models\ReviewModel;
use Core\Http\BaseController;
use App\Validator\ReviewValidate;
use App\Models\PlaceModel;
class ReviewController extends BaseController{
    private $review;
    private $validate;
    private $place;
    public function __construct(){
        $this->review = new ReviewModel();
        $this->validate = new ReviewValidate();
        $this->place = new PlaceModel();
    }
    // get review with place_id
    public function index()
    {
        $place_id = (int)$_REQUEST['id'];
        $result = $this->review->getByPlaceId($place_id);
        $msgs = [
            'status'    =>  'success',
            'msg'       =>  'Get review',
            'data'      =>  $result
        ];
        return $this->status(200,$msgs);
    }
    // add review with place_id
    public function postAdd(){
        $place_id = (int)$_REQUEST['id'];
        $req = $_POST;
        $msgs = $this->validate->add($req);
        if(count($msgs) > 0){
            $msg = [
                'status'    => 'error',
                'msg'       => 'Some fielt not fill in',
                'data'      => $msgs
            ];
            return $this->status(422,$msg);
        } 
        $data = [
            'user_id'       => 1,
            'place_id'      => $place_id,
            'rate'          => $req['rate'],
            'comment'       => $req['comment'],
        ];
        $result = $this->review->create($data);
        if($result == false){
            $msg = [
                'status'    => 'error',
                'msg'       => 'Error add review',
                'data'      => null
            ];
            return $this->status(500,$msg);
        }
        $place = $this->place->get($place_id);
        $dataPlace = [
            'reviews'   => $place['reviews'] +1
        ];
        if($place['stars'] == 0.0){
            $dataPlace['stars'] = $req['rate'];
        }else{
            $dataPlace['stars'] =  (float)($place['stars']+$req['rate'])/2;
        }
        var_dump($dataPlace);
        $place = $this->place->update($place_id,$dataPlace);
        $msg = [    
            'status'    => ' success',
            'msg'       => 'Add review success',
            'data'      => null
        ]; 
        return $this->status(200,$msg);
    }  
}