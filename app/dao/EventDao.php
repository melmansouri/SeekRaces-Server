<?php

namespace app\dao;

class EventDao {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addNewEvent($data) {
        $response = new \app\dto\Response();
        $messageResponse = "Error al añadir la carrera";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO event(user, name, description, image,distance,country,city,date_time_init,web)"
                    . " VALUES"
                    . " (:user, :name, :description, :image,:distance,:country,:city,:date_time_init,:web)";
            $dataQuery = array("user" => $data["user"],
                "name" => $data["name"],
                "description" => $data["description"],
                "image" => $data["image"],
                "distance" => $data["distance"],
                "country" => $data["country"],
                "city" => $data["city"],
                "date_time_init" => $data["date_time_init"],
                "web" => $data["web"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Nueva carrera añadida";
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }
    
    public function getEvent($data){
        $response = new \app\dto\Response();
        $messageResponse = "Problemas para obtener las carreras";
        $isOk = FALSE;
        try {
            $query = "SELECT * FROM event WHERE country like :country and city like :city";
            $dataQuery = array("country" => "%%",
                "city" => "%%");
            
            if (array_key_exists("country", $data)) {
                $dataQuery["country"]="%".$data["country"]."%";
            }
            
            if (array_key_exists("city", $data)) {
                $dataQuery["city"]="%".$data["city"]."%";
            }
            
            if (array_key_exists("distance", $data)) {
                $query.=" AND distance = :distance";
                $dataQuery["distance"]=$data["distance"];
            }
            
            if (array_key_exists("date_interval_init", $data) && !array_key_exists("date_interval_end", $data)) {
                $query.=" AND date__time_init < :date_interval_init";
                $dataQuery["date_interval_init"]=$data["date_interval_init"];
            }else if(array_key_exists("date_interval_init", $data) && array_key_exists("date_interval_end", $data)){
                $query.=" AND date__time_init between :date_interval_init AND date_interval_end";
                $dataQuery["date_interval_init"]=$data["date_interval_init"];
                $dataQuery["date_interval_end"]=$data["date_interval_end"];
            }
            
            $eventos=$this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);
            
            if ($eventos) {
                $arrayEventosFinal= array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event=new \app\dto\Event();
                    $event->setId($eventos[$i]["id"]);
                    $event->setUser($eventos[$i]["user"]);
                    $event->setName($eventos[$i]["name"]);
                    $event->setDescription($eventos[$i]["description"]);
                    $event->setImage($eventos[$i]["image"]);
                    $event->setDistance($eventos[$i]["distance"]);
                    $event->setCountry($eventos[$i]["country"]);
                    $event->setCity($eventos[$i]["city"]);
                    $event->setDate_time_init($eventos[$i]["date_time_init"]);
                    $event->setWeb($eventos[$i]["web"]);
                    $event->setNum_votes($eventos[$i]["num_votes"]);
                    $event->setTotal_scores($eventos[$i]["total_scores"]);
                    $event->setRating($eventos[$i]["rating"]);
                    array_push($arrayEventosFinal, $event->getArray());
                }
                $isOk = TRUE;
                $messageResponse="";
                $response->setContent($arrayEventosFinal);
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
