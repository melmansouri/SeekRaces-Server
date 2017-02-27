<?php
namespace app\entities;

class Event{
 
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
    private $num_reviews;
    private $total_scores;
    private $rating;
    private $isFavorite;
    
    public function __construct() {
        
    }
    
    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getUserName() {
        return $this->userName;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getImageBase64() {
        return $this->imageBase64;
    }

    function getImageName() {
        return $this->imageName;
    }

    function getDistance() {
        return $this->distance;
    }

    function getPlace() {
        return $this->place;
    }

    function getDate_time_init() {
        return $this->date_time_init;
    }

    function getWeb() {
        return $this->web;
    }

    function getNum_reviews() {
        return $this->num_reviews;
    }

    function getTotal_scores() {
        return $this->total_scores;
    }

    function getRating() {
        return $this->rating;
    }

    function getIsFavorite() {
        return $this->isFavorite;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setUserName($userName) {
        $this->userName = $userName;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setImageBase64($imageBase64) {
        $this->imageBase64 = $imageBase64;
    }

    function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    function setDistance($distance) {
        $this->distance = $distance;
    }

    function setPlace($place) {
        $this->place = $place;
    }

    function setDate_time_init($date_time_init) {
        $this->date_time_init = $date_time_init;
    }

    function setWeb($web) {
        $this->web = $web;
    }

    function setNum_reviews($num_reviews) {
        $this->num_reviews = $num_reviews;
    }

    function setTotal_scores($total_scores) {
        $this->total_scores = $total_scores;
    }

    function setRating($rating) {
        $this->rating = $rating;
    }

    function setIsFavorite($isFavorite) {
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
