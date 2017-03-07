<?php
namespace app\entities;

class Race{
 
    private $id;
    private $user;
    private $userName;
    private $name;
    private $description;
    private $imageBase64;
    private $imageName;
    private $distance;
    private $place;
    private $date_time_init;
    private $web;
    private $isFavorite;
    
    public function __construct() {
        
    }
    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImageBase64() {
        return $this->imageBase64;
    }

    public function getImageName() {
        return $this->imageName;
    }

    public function getDistance() {
        return $this->distance;
    }

    public function getPlace() {
        return $this->place;
    }

    public function getDate_time_init() {
        return $this->date_time_init;
    }

    public function getWeb() {
        return $this->web;
    }

    public function getIsFavorite() {
        return $this->isFavorite;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setImageBase64($imageBase64) {
        $this->imageBase64 = $imageBase64;
    }

    public function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    public function setDistance($distance) {
        $this->distance = $distance;
    }

    public function setPlace($place) {
        $this->place = $place;
    }

    public function setDate_time_init($date_time_init) {
        $this->date_time_init = $date_time_init;
    }

    public function setWeb($web) {
        $this->web = $web;
    }

    public function setIsFavorite($isFavorite) {
        $this->isFavorite = $isFavorite;
    }

    
                
    public function getArray(){
        return array(
            "id"=> $this->id,
            "user"=> $this->user,
            "userName"=> $this->userName,
            "name"=> $this->name,
            "description"=> $this->description,
            "imageBase64"=> $this->imageBase64,
            "distance"=> $this->distance,
            "place"=> $this->place,
            "date_time_init"=> $this->date_time_init,
            "web"=> $this->web,
            "isFavorite"=> $this->isFavorite);
    }
}
