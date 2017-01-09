<?php
namespace app\dto;

class Event{
 
    private $id;
    private $user;
    private $name;
    private $description;
    private $image;
    private $distance;
    private $country;
    private $city;
    private $date_time_init;
    private $web;
    private $num_reviews;
    private $total_scores;
    private $rating;
    
    public function __construct() {
        
    }

    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getImage() {
        return $this->image;
    }

    function getDistance() {
        return $this->distance;
    }

    function getCountry() {
        return $this->country;
    }

    function getCity() {
        return $this->city;
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

    function setId($id) {
        $this->id = $id;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setImage($image) {
        $this->image = $image;
    }

    function setDistance($distance) {
        $this->distance = $distance;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setCity($city) {
        $this->city = $city;
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

        
    public function getArray(){
        return array(
            "id"=> $this->id,
            "user"=> $this->user,
            "name"=> $this->name,
            "description"=> $this->description,
            "image"=> $this->image,
            "distance"=> $this->distance,
            "country"=> $this->country,
            "city"=> $this->city,
            "date_time_init"=> $this->date_time_init,
            "web"=> $this->web,
            "num_reviews"=> $this->num_reviews,
            "total_scores"=> $this->total_scores,
            "rating"=> $this->rating);
    }
}
