<?php

require_once("mysql_db.php");
require_once("pianeta.php");

class Utente {
    private $nome_utente;
    private $id_utente;
    private $livello;
    private $metallo;
    private $cristallo;
    private $deuterio;
    private $energia;
    private $esperienza;
    private $id_flotta;
    private $data_iscrizione;
    private $invitato_da;
    private $data_ultima_azione;
    private $numero_pianeti;
    private $onore;
    private $admin;
    private $bannato;
    protected $mySql;

    public function __construct($nome_utente, $id_utente, $admin = 0, $livello = 1, $metallo = 1000, $cristallo = 400, $deuterio = 100, $energia = 0, $esperienza = 0, $id_flotta = 0, $data_iscrizione = "", $invitato_da = "", $data_ultima_azione = 0, $numero_pianeti = 0, $onore = 0, $bannato = 0) {
        $this->nome_utente = $nome_utente;
        $this->id_utente = $id_utente;
        $this->livello = $livello;
        $this->metallo = $metallo;
        $this->cristallo = $cristallo;
        $this->deuterio = $deuterio;
        $this->energia = $energia;
        $this->esperienza = $esperienza;
        $this->id_flotta = $id_flotta;
        $this->data_iscrizione = date("d/m/Y");
        $this->invitato_da = $invitato_da;
        $this->data_ultima_azione = date("YmdHis"); //AAAAmmggHHmmss
        $this->numero_pianeti = $numero_pianeti;
        $this->admin = $admin;
        $this->onore = $onore;
        $this->bannato = $bannato;        
        $this->mySql = new mySql_db();
    }

    public function esisteUtente() {
        $sql = "SELECT * FROM utenti WHERE id_utente = $this->id_utente";
        $row = $this->mySql->fetch_array_by_id($sql);
        if($row == 0)
            return false;
        else 
            return true;
    }

    public function getLivello() {
        return $this->livello;
    }

    public function creaUtente() {
        if(!$this->esisteUtente()) {
            $sql = "INSERT INTO utenti 
            (nome_utente, id_utente, livello, metallo, cristallo, deuterio, energia, esperienza, id_flotta, data_iscrizione, invitato_da, data_ultima_azione, numero_pianeti, admin, onore, bannato)
            VALUES
            ('$this->nome_utente', '$this->id_utente', '$this->livello', '$this->metallo', '$this->cristallo', '$this->deuterio', '$this->energia', '$this->esperienza', '$this->id_flotta', '$this->data_iscrizione', '$this->invitato_da', '$this->data_ultima_azione', '$this->numero_pianeti', '$this->admin', '$this->onore', '$this->onore')  
            ";
            $this->mySql->query($sql);
            $pianeta = new Pianeta($this->id_utente, "Pianeta senza nome"); //hmmmmm
            $pianeta->creaPianeta();
            return "Iscrizione effettuata.";
        }
        else return "Utente già esistente.";
    }

    public function incrementaXP($valore) {
        $stringa = "Hai guadagnato".$valore." XP.";
        $sql = "SELECT * FROM utenti WHERE id_utente = $this->id_utente";
        $row = $this->mySql->fetch_array_by_id($sql);
        $vecchio_valore = $row["esperienza"];
        $nuovo_valore = $vecchio_valore + $valore;
        $livello = $row["livello"];
        if($nuovo_valore > ($livello * 10 - 1)) {
            $livello++;
            $nuovo_valore = 0;
            $stringa.= "Hai raggiunto il livello ".$livello."!";
        }
        else {
            $stringa.= "Ti mancano ".(($livello * 10) - $nuovo_valore)."XP per raggiungere il livello successivo."; 
        }
        $sql = "UPDATE `utenti` SET 
       `livello` = '$livello',
       `esperienza` = '$nuovo_valore'
        WHERE id_utente = '$this->id_utente'";
        $this->mySql->query($sql);
        return $stringa;
    }
}

?>