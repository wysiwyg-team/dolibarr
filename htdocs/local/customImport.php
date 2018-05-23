<?php
/**
 * Created by PhpStorm.
 * User: Ankush
 * Date: 4/30/2018
 * Time: 6:10 PM
 */

require '../main.inc.php';
require 'customFunctions.php';

class customImport extends customFunctions
{
    /**
     * @param $rr
     * @return array
     * Select multiple last inserted ids from llx_facture
     */
    public function arrayResult($rr)
    {
        $data = simplexml_load_file($_FILES['file']['tmp_name']);
        $countData = count($data);

        $queryId = "SELECT rowid FROM llx_facture ORDER BY rowid desc limit $countData";
        $pre = $this->pdo->prepare($queryId);
        $pre->execute();
        $rr = $pre->fetchAll();
        sort($rr);


        return $rr;
    }

    /**
     * Insert data from xml file to tables llx_facture and llx_facturedet
     */
    public function custInsert()
    {

        $id = array();
        $output = '';
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            $valid_extension = array('xml');
            $file_data = explode('.', $_FILES['file']['name']);
            $file_extension = end($file_data);
            if (in_array($file_extension, $valid_extension)) {
                $data = simplexml_load_file($_FILES['file']['tmp_name']);
                $countData = count($data);

                try {
                    for ($i = 0; $i < $countData; $i++) {

                        $query = "INSERT INTO custom_facture (ticket_id) values (:ref_ext);INSERT INTO llx_facture (
`facnumber`,
`entity`,
`ref_ext`,
`ref_int`,
`type`,
`ref_client`,
`increment`,
`fk_soc`,
`datec`,
`datef`,
`date_valid`,
`tms`,
`paye`,
`amount`,
`remise_percent`,
`remise_absolue`,
`remise`,
`close_code`,
`close_note`,
`tva`,
`localtax1`,
`localtax2`,
`revenuestamp`,
`total`,
`total_ttc`,
`fk_statut`,
`fk_user_author`,
`fk_user_modif`,
`fk_user_valid`,
`fk_facture_source`,
`fk_projet`,
`fk_account`,
`fk_currency`,
`fk_cond_reglement`,
`fk_mode_reglement`,
`date_lim_reglement`,
`note_private`,
`note_public`,
`model_pdf`,
`import_key`,
`extraparams`,
`situation_cycle_ref`,
`situation_counter`,
`situation_final`,
`fk_incoterms`,
`location_incoterms`,
`date_pointoftax`,
`fk_multicurrency`,
`multicurrency_code`,
`multicurrency_tx`,
`multicurrency_total_ht`,
`multicurrency_total_tva`,
`multicurrency_total_ttc`,
`fk_fac_rec_source`)
VALUES (
:facnumber,
:entity,
:ref_ext,
:ref_int,
:type,
:ref_client,
:increment,
:fk_soc,
:datec,
:datef,
:date_valid,
:tms,
:paye,
:amount,
:remise_percent,
:remise_absolue,
:remise,
:close_code,
:close_note,
:tva,
:localtax1,
:localtax2,
:revenuestamp,
:total,
:total_ttc,
:fk_statut,
:fk_user_author,
:fk_user_modif,
:fk_user_valid,
:fk_facture_source,
:fk_projet,
:fk_account,
:fk_currency,
:fk_cond_reglement,
:fk_mode_reglement,
:date_lim_reglement,
:note_private,
:note_public,
:model_pdf,
:import_key,
:extraparams,
:situation_cycle_ref,
:situation_counter,
:situation_final,
:fk_incoterms,
:location_incoterms,
:date_pointoftax,
:fk_multicurrency,
:multicurrency_code,
:multicurrency_tx,
:multicurrency_total_ht,
:multicurrency_total_tva, :multicurrency_total_ttc,
:fk_fac_rec_source);";


                        $stmt = $this->pdo->prepare($query);

                        $refExt = $data->ticket[$i]->ticket_id;
                        $rand = "" . $this->custom_facnumber();
                        $entity = $this->getEntityGlpi();
                        $refClient = $data->ticket[$i]->ticket_no;
                        $fk_soc = $data->ticket[$i]->societe_id;
                        $total = $this->format_time($data->ticket[$i]->time_spent);
                        $pay = $data->ticket[$i]->rate;
                        $userAuthor = $this->get_user_author();
                        $notePrivate = $data->ticket[$i]->task_description;
                        $notePublic = $data->ticket[$i]->resource_name;
                        $datec = $data->ticket[$i]->date_assigned;
                        $user = $this->get_current_userImport();
                        $date_lim_reglement = $data->ticket[$i]->date_ended;
                        $amount = $this->get_amount($total, $pay);

                        $stmt->bindParam(':facnumber', $rand);
                        $stmt->bindParam(':entity', $entity);
                        $stmt->bindParam(':ref_ext', $refExt);
                        $stmt->bindValue(':ref_int', null, PDO::PARAM_INT);
                        $stmt->bindValue(':type', 0, PDO::PARAM_INT);
                        $stmt->bindParam(':ref_client', $refClient);
                        $stmt->bindValue(':increment', null, PDO::PARAM_INT);
                        $stmt->bindParam(':fk_soc', $fk_soc);
                        $stmt->bindParam(':datec', $datec);
                        $stmt->bindParam(':datef', $datec);
                        $stmt->bindValue(':date_valid', null, PDO::PARAM_INT);
                        $stmt->bindValue(':tms', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                        $stmt->bindValue(':paye', 0, PDO::PARAM_INT);
                        $stmt->bindParam(':amount', $pay);
                        $stmt->bindValue(':remise_percent', null, PDO::PARAM_INT);
                        $stmt->bindValue(':remise_absolue', null, PDO::PARAM_INT);
                        $stmt->bindValue(':remise', 0, PDO::PARAM_INT);
                        $stmt->bindValue(':close_code', null, PDO::PARAM_INT);
                        $stmt->bindValue(':close_note', null, PDO::PARAM_INT);
                        $stmt->bindValue(':tva', 0, PDO::PARAM_INT);
                        $stmt->bindValue(':localtax1', 0, PDO::PARAM_INT);
                        $stmt->bindValue(':localtax2', 0, PDO::PARAM_INT);
                        $stmt->bindValue(':revenuestamp', 0, PDO::PARAM_INT);
                        $stmt->bindParam(':total', $amount);
                        $stmt->bindValue(':total_ttc', 100, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_statut', 0, PDO::PARAM_INT);
                        $stmt->bindParam(':fk_user_author', $userAuthor);
                        $stmt->bindValue(':fk_user_modif', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_user_valid', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_facture_source', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_projet', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_account', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_currency', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_cond_reglement', 1, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_mode_reglement', 0, PDO::PARAM_INT);
                        $stmt->bindParam(':date_lim_reglement', $date_lim_reglement);
                        $stmt->bindParam(':note_private', $notePrivate);
                        $stmt->bindParam(':note_public', $notePublic);
                        $stmt->bindValue(':model_pdf', 'crabe', PDO::PARAM_STR);
                        $stmt->bindValue(':import_key', null, PDO::PARAM_INT);
                        $stmt->bindValue(':extraparams', null, PDO::PARAM_INT);
                        $stmt->bindValue(':situation_cycle_ref', null, PDO::PARAM_INT);
                        $stmt->bindValue(':situation_counter', null, PDO::PARAM_INT);
                        $stmt->bindValue(':situation_final', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_incoterms', null, PDO::PARAM_INT);
                        $stmt->bindValue(':location_incoterms', null, PDO::PARAM_INT);
                        $stmt->bindValue(':date_pointoftax', null, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_multicurrency', 1, PDO::PARAM_INT);
                        $stmt->bindValue(':multicurrency_code', 'EUR', PDO::PARAM_STR);
                        $stmt->bindValue(':multicurrency_tx', 1, PDO::PARAM_INT);
                        $stmt->bindValue(':multicurrency_total_ht', 100, PDO::PARAM_INT);
                        $stmt->bindValue(':multicurrency_total_tva', 100, PDO::PARAM_INT);
                        $stmt->bindValue(':multicurrency_total_ttc', 100, PDO::PARAM_INT);
                        $stmt->bindValue(':fk_fac_rec_source', null, PDO::PARAM_INT);
                        $stmt->execute();
//                        $stmt->closeCursor();
                        $cust = new customFunctions();
                        $cust->custom_facnumber();
                        $stmt->closeCursor();

                    }
                    $result = $stmt->fetchAll();

                    print_r($this->arrayResult($rr));
                    $stt = $this->pdo->prepare("INSERT INTO llx_facturedet (fk_facture, label, description) values (:fk_facture,:label,:description)");
//                    for ($i = 0; $i < $countData; $i++) {

                    $count = 0;
                    foreach ($this->arrayResult($rr) as $th) {

                        $stt->execute(array(
                            'fk_facture' => $th['rowid'],
                            'label' => $data->ticket[$j]->ticket_task_id,
                            'description' => $data->ticket[$j]->cost_category
                        ));

                        $count++;
                        if ($count > $countData) {
                            break;
                        }
                    }


//                    }

                } catch (PDOException $exception) {
                    if ($exception->errorInfo[1] == 1062) {
                        echo "duplicate enttry of ticket";

                    } else {
                        $exception->errorInfo;
                    }
                }


                if (isset($result)) {
                    $output = '<div class="alert alert-success">Import Done</div>';

                }

            } else {
                $output = '<div class="alert alert-warning">Invalid file</div>';

            }
        } else {
            $output = '<div class="alert alert-warning">Select XML file</div>';
        }

        echo $output;

    }


}

$custImport = new customImport();
$custImport->custInsert();

?>