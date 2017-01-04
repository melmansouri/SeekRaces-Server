<?php
namespace app\dto;

class Event{
 
    private $id;
    private $user;
    private $name;
    private $descripcion;
    private $image;
    private $distance;
    private $country;
    private $city;
    private $date_time_init;
    private $web;
    private $num_votes;
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

    function getDescripcion() {
        return $this->descripcion;
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

    function getNum_votes() {
        return $this->num_votes;
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

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
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

    function setNum_votes($num_votes) {
        $this->num_votes = $num_votes;
    }

    function setTotal_scores($total_scores) {
        $this->total_scores = $total_scores;
    }

    function setRating($rating) {
        $this->rating = $rating;
    }
    
}
?>
