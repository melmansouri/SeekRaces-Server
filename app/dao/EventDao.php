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
            
        }

        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
