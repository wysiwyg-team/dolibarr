<?php
/**
 * Created by PhpStorm.
 * User: Ankush
 * Date: 4/24/2018
 * Time: 5:23 PM
 */
require '../xml/config/db.php';

class php_xml extends db
{

    public function validate($value)
    {
        return (isset($value) ? $value : null);
    }

    public function getRecords()
    {
        $from = $this->validate($_POST['from']);
        $to = $this->validate($_POST['to']);

        if ($from && $to):
            $sth = $this->pdo->prepare('SELECT * FROM custom_export_worksheet WHERE date_assigned >= :from AND date_ended <= :to');


            $sth->bindParam(":from", $from, PDO::PARAM_STR);
            $sth->bindParam(":to", $to, PDO::PARAM_STR);
        else:
            $sth = $this->pdo->prepare('SELECT * FROM custom_export_worksheet');
            $sth->bindParam(":from", $from, PDO::PARAM_STR);
            $sth->bindParam(":to", $to, PDO::PARAM_STR);
        endif;
        $sth->execute();

        $ticketArray = array();
        while ($array = $sth->fetch(PDO::FETCH_ASSOC)) {
            array_push($ticketArray, $array);
        }
        if (count($ticketArray)) {
            $this->createXMLfile($ticketArray);
        }
    }

    public function createXMLfile($ticketArray)
    {
        $filePath = '../xmlResult/tickets.xml';

        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('tickets');

        for ($i = 0; $i < count($ticketArray); $i++) {
            $ticketId = $ticketArray[$i]['id'];
            $ticketNo = $ticketArray[$i]['ticket_no'];
            $ticketTaskId = $ticketArray[$i]['ticket_task_id'];
            $taskDescription = $ticketArray[$i]['task_description'];
            $timeSpent = $ticketArray[$i]['time_spent'];
            $resourceName = $ticketArray[$i]['resource_name'];
            $dateAssigned = $ticketArray[$i]['date_assigned'];
            $dateEnded = $ticketArray[$i]['date_ended'];
            $costCategory = $ticketArray[$i]['cost_category'];
            $rate = $ticketArray[$i]['rate'];
            $societeId = $ticketArray[$i]['societe_id'];

            $ticket = $dom->createElement('ticket');
            $ticket->setAttribute('id', $ticketId);

            $ticketidd = $dom->createElement('ticket_id',$ticketId);
            $ticket->appendChild($ticketidd);

            $ticket_no = $dom->createElement('ticket_no', $ticketNo);
            $ticket->appendChild($ticket_no);

            $ticket_task_id = $dom->createElement('ticket_task_id', $ticketTaskId);
            $ticket->appendChild($ticket_task_id);

            $task_description = $dom->createElement('task_description', $taskDescription);
            $ticket->appendChild($task_description);

            $time_spent = $dom->createElement('time_spent', $timeSpent);
            $ticket->appendChild($time_spent);

            $resource_name = $dom->createElement('resource_name', $resourceName);
            $ticket->appendChild($resource_name);

            $date_assigned = $dom->createElement('date_assigned', $dateAssigned);
            $ticket->appendChild($date_assigned);

            $date_ended = $dom->createElement('date_ended', $dateEnded);
            $ticket->appendChild($date_ended);

            $cost_category = $dom->createElement('cost_category', $costCategory);
            $ticket->appendChild($cost_category);

            $rate = $dom->createElement('rate', $rate);
            $ticket->appendChild($rate);

            $societeId = $dom->createElement('societe_id', $societeId);
            $ticket->appendChild($societeId);

            $root->appendChild($ticket);

        }

        $dom->appendChild($root);

        $dom->save($filePath);

    }

}

?>