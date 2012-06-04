<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hippias
 * Date: 04/06/12
 * Time: 21:47
 * To change this template use File | Settings | File Templates.
 */

$con = mysql_connect('127.0.0.1', 'root', '');
$db  = mysql_select_db('resahotel', $con);

function dummyCreatePersonne () {
mysql_query("BEGIN");
mysql_query("START TRANSACTION");

    $_resultCart = mysql_query("insert into cartes (cart_numero, cart_dateExpiration, cart_type) values ('198974312', date_add(curdate(), interval 2 YEAR), 1)");
    $_idCarte    = getMaxId("cart_id", "cartes");
    $_resultPers = mysql_query("insert into personnes (pers_prenom, pers_nom, pers_telephone, pers_adresse, pers_carte) values ('Jean', 'Eudes', '0143546743', 'rue machin', $_idCarte)");

    if (!$_resultCart) {
        mysql_query("rollback");
        return "Erreur lors de la creation de la carte";
    }
    if (!$_resultPers) {
        mysql_query("rollback");
        return "Erreur lors de la creation de la personne";
    }

    mysql_query("commit");
    return "la personne a bien ete creee";
}

function createDummyDemande ()
{
    mysql_query ("insert into demande (dema_nombreChambres, dema_debut, dema_fin, dema_categorie, dema_station, dema_personne) values (3, '2012-08-01', '2012-09-01', 1, 2, 1)");
}

function createDummyResa () {
    mysql_query ("BEGIN");
    mysql_query ("START TRANSACTION");

    $NbChambres = 3;
    $CatHot     = 1;
    $StatHot    = 2;

    //on suppose qu'il y a forcement toutes les categories d'hotels dans une station donnee (ou que l'operateur ne puisse saisir n'importe quoi)

    $_hotelsDemandes = getRequestAsArray("select * from hotels where hote_categorie = $CatHot and hote_station = $StatHot");
    foreach ($_hotelsDemandes as $_hot => $_hotDummy) {
        $hotel    = $_hotelsDemandes [$_hot];
        $chambres = getRequestAsarray ("select * from chambres natural join where cham_hotel = " . $hotel['hote_id']);
    }


}


function getMaxId ($__idChamp, $__idTable)
{
    $req = mysql_query ("select max($__idChamp) as maxId from $__idTable");
    while ($row = mysql_fetch_assoc ($req))
        return $row ["maxId"];
}

function getRequestAsArray ($__query) {
    $res   = mysql_query ($__query);
    $table = array ();

    while ($row = mysql_fetch_assoc($res)) {
        $rowStrip = array();
        foreach ($row as $_rIdx => $_rDummy)
            $rowStrip [$_rIdx] = stripslashes($row [$_rIdx]);

        $table [] = $rowStrip;
    }

    return $table;
}

//echo dummyCreatePersonne();

mysql_close ($con);

?>