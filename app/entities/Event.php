<?php
namespace app\entities;

class Event{
 
    private $id;
    private $user;
    private $userName;
    private $name;
    private $description;
    private $imageBase64;
    private $distance;
    private $place;
    private $city;
    private $date_time_init;
    private $web;
    private $num_reviews;
    private $total_scores;
    private $rating;
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

    public function getNum_reviews() {
        return $this->num_reviews;
    }

    public function getTotal_scores() {
        return $this->total_scores;
    }

    public function getRating() {
        return $this->rating;
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

    public function setNum_reviews($num_reviews) {
        $this->num_reviews = $num_reviews;
    }

    public function setTotal_scores($total_scores) {
        $this->total_scores = $total_scores;
    }

    public function setRating($rating) {
        $this->rating = $rating;
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
            "num_reviews"=> $this->num_reviews,
            "total_scores"=> $this->total_scores,
            "rating"=> $this->rating,
            "isFavorite"=> $this->isFavorite);
    }
}
