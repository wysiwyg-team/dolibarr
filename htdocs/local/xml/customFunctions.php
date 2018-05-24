<?php
/**
 * Created by PhpStorm.
 * User: Homeshwar
 * Date: 16/05/2018
 * Time: 10:50
 */

class customFunctions
{
    public $hostname = "localhost";
    public $username = "root";
    public $password = "";
    public $pdo;

    /**
     * customFunctions constructor.
     * PDO db connection
     */
    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->hostname;dbname=dolibarr", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Close PDO db connection
     */
    public function closeConnection()
    {
        $this->pdo = null;
    }

    /**
     * @return int
     */
    public function getEntityGlpi()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function get_current_userImport()
    {
        return 2;
    }

    /**
     * @param $t
     * @param string $f
     * @return string
     */
    public function format_time($t, $f = ':') // t = seconds, f = separator
    {
        return sprintf("%02d%s%02d%s%02d", floor($t / 3600), $f, ($t / 60) % 60, $f, $t % 60);
    }

    /**
     * @param $total
     * @param $pay
     * @return float|int
     */
    public function get_amount($total, $pay)
    {
        $hm = explode(":", $total);
        return ($hm[0] + ($hm[1] / 60) + ($hm[2] / 3600)) * $pay;
    }

    /**
     * @return mixed
     */
    public function get_user_author()
    {
        if (isset($_SESSION["dol_login"])) {
            //    echo $_SESSION["dol_login"];
            //            echo session_id();
            $sessionQuery = "select rowid from llx_user where login = '" . $_SESSION["dol_login"] . "' ";
            //var_dump($sessionQuery);
            $sol = $this->pdo->prepare($sessionQuery);
            $sol->execute();
            $soln = $sol->fetch();
            return $soln["rowid"];
        }
    }

    /**
     * Set facnumber to (PROVnum)
     */
    public function custom_facnumber()
    {
        $data = simplexml_load_file($_FILES['file']['tmp_name']);
        $countData = count($data);

        $st = $this->pdo->prepare('select rowid from llx_facture order by rowid desc limit :countData');
        $st->bindParam(':countData', $countData, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
//                $id = mysqli_insert_id();
            $update = $this->pdo->prepare("update llx_facture SET facnumber = '(PROV$row[rowid])' where rowid = $row[rowid]");
            $update->execute();
//            $out = $update->fetch();
//            return $out;

        }
    }


}


