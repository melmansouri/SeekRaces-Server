<?php

namespace app\controllers;

class CountryCityController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }
    
    public function getCountries(){
        $countries="";
        try {
            $query = "SELECT * FROM country";
            
            $paises=$this->connectionDb->executeQueryWithoutDataFetchAll($query);
            
            if ($paises) {
                $arrayPaisesFinal= array();
                for ($i = 0; $i < count($paises); $i++) {
                    $country=new \app\entities\Country();
                    $country->setCode($paises[$i]["Code"]);
                    $country->setName($paises[$i]["Name"]);
                    array_push($arrayPaisesFinal, $country->getArray());
                }
                $countries=json_encode($arrayPaisesFinal);
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }

        return $countries;
    }
    public function getCities(){
        $cities="";
        try {
            $query = "SELECT * FROM city";
            
            $ciudades=$this->connectionDb->executeQueryWithoutDataFetchAll($query);
            
            if ($ciudades) {
                $arrayCiudadesFinal= array();
                for ($i = 0; $i < count($ciudades); $i++) {
                    $city=new \app\entities\City();
                    $city->setName($ciudades[$i]["Name"]);
                    $city->setCountryCode($ciudades[$i]["CountryCode"]);
                    array_push($arrayCiudadesFinal, $city->getArray());
                }
                $cities=json_encode($arrayCiudadesFinal);
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }

        return $cities;
    }

}
