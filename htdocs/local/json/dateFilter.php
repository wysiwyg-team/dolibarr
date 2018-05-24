<?php
//include "form.php";
require '../xml/config/db.php';

class dateFilter extends db
{

    public function validate($value)
    {
        return (isset($value) ? $value : null);
    }

    public function getRecords()
    {
        $from = $this->validate($_POST['from']);
        $to = $this->validate($_POST['to']);
// date_assigned = begin_date
// date_ended = end_date in table glpi_ticketcosts
        if ($from && $to):

        $sth = $this->pdo->prepare('SELECT * FROM custom_export_worksheet
              WHERE date_assigned >= :from AND date_ended <= :to');

            $sth->bindParam(":from", $from, PDO::PARAM_STR);
            $sth->bindParam(":to", $to, PDO::PARAM_STR);
        else:
            $sth = $this->pdo->prepare('SELECT * FROM custom_export_worksheet');
            $sth->bindParam(":from", $from, PDO::PARAM_STR);
            $sth->bindParam(":to", $to, PDO::PARAM_STR);
        endif;
        $sth->execute();
//        echo $sth->queryString;die;

        $jsonData = array();
        while ($array = $sth->fetch(PDO::FETCH_ASSOC)) {
            $cropData =
                array('id' => $array['id'],
                    'ticket_no' => $array['ticket_no'],
                    'ticket_task_id' => $array['ticket_task_id'],
                    'task_description' => $array['task_description'],
                    'time_spent' => $array['time_spent'],
                    'resource_name' => $array['resource_name'],
                    'date_assigned' => $array['date_assigned'],
                    'date_ended' => $array['date_ended'],
                    'cost_category' => $array['cost_category'],
                    'rate' => $array['rate'],
                    'societe_id' => $array['societe_id']
                );
            $jsonData[] = $cropData;
        }
        return $jsonData;

    }

    /*
     *
     */
    public function showJson()
    {
        $jsonResult = $this->getRecords();
        header('Content-type: application/json');
        echo json_encode($jsonResult);
    }
}

?>
