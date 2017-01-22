<?php

namespace app\controllers;

class EventController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addNewEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al añadir la carrera";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO event(user, name, description, image,distance,country,city,date_time_init,web)"
                    . " VALUES"
                    . " (:user, :name, :description, :image,:distance,:country,:city,:date_time_init,:web)";
            $imageName = "";
            if (isset($data["imageBase64"]) && !empty($data["imageBase64"])) {
                $file_path_photo = \app\common\Utils::base64ToFile($data["imageBase64"],\app\common\Utils::getCurrentMilliseconds());
                $imageName=$file_path_photo;
            }

            $dataQuery = array("user" => $data["user"],
                "name" => $data["name"],
                "description" => $data["description"],
                "image" => $imageName,
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

    public function getEvents($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Problemas para obtener las carreras";
        $isOk = FALSE;
        try {
            $columnsResultQuery="e.id,e.user,u.username,e.name,e.description,e.image,e.distance,e.country,e.city,e.date_time_init,e.web,e.num_reviews,e.total_scores,e.rating, "
                    . "(select if(f.event is null,false,true) from favorite f where f.event=e.id and f.user like :user) as favorite";
            $query = "SELECT ".$columnsResultQuery ." FROM event inner join user WHERE user <> :user and country like :country and city like :city";
            $dataQuery = array("user" => $data["country"],
                "country" => "%%",
                "city" => "%%");

            if (array_key_exists("country", $data)) {
                $dataQuery["country"] = "%" . $data["country"] . "%";
            }

            if (array_key_exists("city", $data)) {
                $dataQuery["city"] = "%" . $data["city"] . "%";
            }

            if (array_key_exists("distance", $data)) {
                $query .= " AND distance = :distance";
                $dataQuery["distance"] = $data["distance"];
            }

            if (array_key_exists("date_interval_init", $data) && !array_key_exists("date_interval_end", $data)) {
                $query .= " AND date__time_init < :date_interval_init";
                $dataQuery["date_interval_init"] = $data["date_interval_init"];
            } else if (array_key_exists("date_interval_init", $data) && array_key_exists("date_interval_end", $data)) {
                $query .= " AND date__time_init between :date_interval_init AND date_interval_end";
                $dataQuery["date_interval_init"] = $data["date_interval_init"];
                $dataQuery["date_interval_end"] = $data["date_interval_end"];
            }

            $eventos = $this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);

            if ($eventos) {
                $arrayEventosFinal = array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event = new \app\entities\Event();
                    $event->setId($eventos[$i]["id"]);
                    $event->setUser($eventos[$i]["user"]);
                    $event->setUserName($eventos[$i]["username"]);
                    $event->setName($eventos[$i]["name"]);
                    $event->setDescription($eventos[$i]["description"]);
                    $imageName=$eventos[$i]["image"];
                    $base64= \app\common\Utils::fileToBase64($imageName);
                    $event->setImageBase64($base64);
                    $event->setDistance($eventos[$i]["distance"]);
                    $event->setCountry($eventos[$i]["country"]);
                    $event->setCity($eventos[$i]["city"]);
                    $event->setDate_time_init($eventos[$i]["date_time_init"]);
                    $event->setWeb($eventos[$i]["web"]);
                    $event->setNum_reviews($eventos[$i]["num_reviews"]);
                    $event->setTotal_scores($eventos[$i]["total_scores"]);
                    $event->setRating($eventos[$i]["rating"]);
                    $event->setIsFavorite($eventos[$i]["favorite"]);
                    array_push($arrayEventosFinal, $event->getArray());
                }
                $isOk = TRUE;
                $messageResponse = "";
                $response->setContent(json_encode($arrayEventosFinal));
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function deleteEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al intentar borrar la carrera";
        $isOk = FALSE;
        try {
            $query = "DELETE FROM event WHERE id = :id";
            $dataQuery = array("id" => $data["id"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Se ha eliminado con éxito";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function editEvent($args, $data) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido editar la carrera";
        $isOk = FALSE;
        try {
            $query = "UPDATE event SET name = :name, description = :description, image = :image, distance = :distance, country = :country, city = :city, date_time_init = :date_time_init, web = :web"
                    . " WHERE "
                    . "user = :user AND id = :id";
            $imageName = "";
            if (isset($data["imageBase64"]) && !empty($data["imageBase64"])) {
                $file_path_photo = \app\common\Utils::base64ToFile($data["imageBase64"],\app\common\Utils::getCurrentMilliseconds());
                $imageName=$file_path_photo;
            }
            $dataQuery = array(
                "user" => $data["user"],
                "id" => $args["id"],
                "name" => $data["name"],
                "description" => $data["description"],
                "image" => $imageName,
                "distance" => $data["distance"],
                "country" => $data["country"],
                "city" => $data["city"],
                "date_time_init" => $data["date_time_init"],
                "web" => $data["web"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Éxito al editar la carrera";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
