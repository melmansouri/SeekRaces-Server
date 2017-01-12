<?php

namespace app\controllers;

class FavoriteController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addEventToFavorite($data) {
        $response = new \app\data\Response();
        $messageResponse = "Error al añadir la carrera como favorita";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO favorite(user, event)"
                    . " VALUES"
                    . " (:user, :event)";
            $dataQuery = array("user" => $data["email"],
                "event" => $data["id"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "La carrera ha sido añadida como favorita";
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }
    
    public function getEventsFavorites($data){
        $response = new \app\data\Response();
        $messageResponse = "error al obtener las carreras favoritas";
        $isOk = FALSE;
        try {
            $query = "SELECT * FROM favorite as f inner join event as e on f.event = e.id WHERE f.user=:email";
            $dataQuery = array("user" => $data["email"]);
            
            $eventos=$this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);
            
            if ($eventos) {
                $arrayEventosFinal= array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event=new \app\data\Event();
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
    
    public function deleteEventFromFavorites($data) {
        $response = new \app\data\Response();
        $messageResponse = "Error al intentar borrar la carrera de favoritos";
        $isOk = FALSE;
        try {
            $query = "DELETE FROM favorite WHERE event = :event and user = :user";
            $dataQuery = array("event" => $data["id"],
                "user" => $data["email"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Se ha eliminado con éxito de favoritos";
            }
        } catch (Exception $ex) {
        } catch (\PDOException $pex) {
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
