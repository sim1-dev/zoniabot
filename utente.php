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
    private $pianeta_corrente;
    protected $mySql;

    public function __construct($nome_utente, $id_utente, $admin = 0, $livello = 1, $metallo = 1000, $cristallo = 400, $deuterio = 100, $energia = 0, $esperienza = 0, $id_flotta = 0, $data_iscrizione = "", $invitato_da = "", $data_ultima_azione = 0, $numero_pianeti = 0, $onore = 0, $bannato = 0, $pianeta_corrente = "") {
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
        $this->pianeta_corrente = $pianeta_corrente;  
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

    public function getUtente() {
        $sql = "SELECT * FROM utenti WHERE id_utente = $this->id_utente";
        $row = $this->mySql->fetch_array_by_id($sql);
            $this->livello = $row["livello"];
            $this->metallo = $row["metallo"];
            $this->cristallo = $row["cristallo"];
            $this->deuterio = $row["deuterio"];
            $this->energia = $row["energia"];
            $this->esperienza = $row["esperienza"];
            $this->data_iscrizione = $row["data_iscrizione"];
            $this->invitato_da = $row["invitato_da"];
            $this->onore = $row["onore"];
        return;
    }

    public function creaUtente() {
        if(!$this->esisteUtente()) {
           /* $sql = "INSERT INTO utenti 
            (nome_utente, id_utente, livello, metallo, cristallo, deuterio, energia, esperienza, id_flotta, data_iscrizione, invitato_da, data_ultima_azione, numero_pianeti, admin, onore, bannato, pianeta_corrente)
            VALUES
            ('$this->nome_utente', '$this->id_utente', '$this->livello', '$this->metallo', '$this->cristallo', '$this->deuterio', '$this->energia', '$this->esperienza', '$this->id_flotta', '$this->data_iscrizione', '$this->invitato_da', '$this->data_ultima_azione', '$this->numero_pianeti', '$this->admin', '$this->onore', '$this->onore', '$this->pianeta_corrente')  
            ";*/
            $sql = "INSERT INTO utenti 
            (nome_utente, id_utente, livello, metallo, cristallo, deuterio, energia, esperienza, id_flotta, data_iscrizione, invitato_da, data_ultima_azione, numero_pianeti, admin, onore, bannato, pianeta_corrente)
            VALUES
            ('$this->nome_utente', '$this->id_utente', 1, 1000, 400, 100, 0, 0, '$this->id_flotta', '$this->data_iscrizione', '$this->invitato_da', '$this->data_ultima_azione', '$this->numero_pianeti', '$this->admin', 0, 0, '$this->pianeta_corrente')  
            ";
            $this->mySql->query($sql);
            $pianeta = new Pianeta($this->id_utente, "Pianeta senza nome"); //hmmmmm
            $pianeta->creaPianeta();
            //return "$this->mySql->error()";
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

    public function trasferisciSu($_nome_pianeta) {
        $sql = "UPDATE utenti SET 
        pianeta_corrente = '$_nome_pianeta'
         WHERE id_utente = '$this->id_utente'";
         $this->mySql->query($sql);
    }

    /**
     * Get the value of nome_utente
     */ 
    public function getNome_utente()
    {
        return $this->nome_utente;
    }

    /**
     * Set the value of nome_utente
     *
     * @return  self
     */ 
    public function setNome_utente($nome_utente)
    {
        $this->nome_utente = $nome_utente;

        return $this;
    }

    /**
     * Get the value of id_utente
     */ 
    public function getId_utente()
    {
        return $this->id_utente;
    }

    /**
     * Set the value of id_utente
     *
     * @return  self
     */ 
    public function setId_utente($id_utente)
    {
        $this->id_utente = $id_utente;

        return $this;
    }

    /**
     * Get the value of livello
     */ 
    public function getLivello()
    {
        return $this->livello;
    }

    /**
     * Set the value of livello
     *
     * @return  self
     */ 
    public function setLivello($livello)
    {
        $this->livello = $livello;

        return $this;
    }

    /**
     * Get the value of metallo
     */ 
    public function getMetallo()
    {
        return $this->metallo;
    }

    /**
     * Set the value of metallo
     *
     * @return  self
     */ 
    public function setMetallo($metallo)
    {
        $this->metallo = $metallo;

        return $this;
    }

    /**
     * Get the value of cristallo
     */ 
    public function getCristallo()
    {
        return $this->cristallo;
    }

    /**
     * Set the value of cristallo
     *
     * @return  self
     */ 
    public function setCristallo($cristallo)
    {
        $this->cristallo = $cristallo;

        return $this;
    }

    /**
     * Get the value of deuterio
     */ 
    public function getDeuterio()
    {
        return $this->deuterio;
    }

    /**
     * Set the value of deuterio
     *
     * @return  self
     */ 
    public function setDeuterio($deuterio)
    {
        $this->deuterio = $deuterio;

        return $this;
    }

    /**
     * Get the value of energia
     */ 
    public function getEnergia()
    {
        return $this->energia;
    }

    /**
     * Set the value of energia
     *
     * @return  self
     */ 
    public function setEnergia($energia)
    {
        $this->energia = $energia;

        return $this;
    }

    /**
     * Get the value of esperienza
     */ 
    public function getEsperienza()
    {
        return $this->esperienza;
    }

    /**
     * Set the value of esperienza
     *
     * @return  self
     */ 
    public function setEsperienza($esperienza)
    {
        $this->esperienza = $esperienza;

        return $this;
    }

    /**
     * Get the value of id_flotta
     */ 
    public function getId_flotta()
    {
        return $this->id_flotta;
    }

    /**
     * Set the value of id_flotta
     *
     * @return  self
     */ 
    public function setId_flotta($id_flotta)
    {
        $this->id_flotta = $id_flotta;

        return $this;
    }

    /**
     * Get the value of data_iscrizione
     */ 
    public function getData_iscrizione()
    {
        return $this->data_iscrizione;
    }

    /**
     * Set the value of data_iscrizione
     *
     * @return  self
     */ 
    public function setData_iscrizione($data_iscrizione)
    {
        $this->data_iscrizione = $data_iscrizione;

        return $this;
    }

    /**
     * Get the value of invitato_da
     */ 
    public function getInvitato_da()
    {
        return $this->invitato_da;
    }

    /**
     * Set the value of invitato_da
     *
     * @return  self
     */ 
    public function setInvitato_da($invitato_da)
    {
        $this->invitato_da = $invitato_da;

        return $this;
    }

    /**
     * Get the value of data_ultima_azione
     */ 
    public function getData_ultima_azione()
    {
        return $this->data_ultima_azione;
    }

    /**
     * Set the value of data_ultima_azione
     *
     * @return  self
     */ 
    public function setData_ultima_azione($data_ultima_azione)
    {
        $this->data_ultima_azione = $data_ultima_azione;

        return $this;
    }

    /**
     * Get the value of numero_pianeti
     */ 
    public function getNumero_pianeti()
    {
        return $this->numero_pianeti;
    }

    /**
     * Set the value of numero_pianeti
     *
     * @return  self
     */ 
    public function setNumero_pianeti($numero_pianeti)
    {
        $this->numero_pianeti = $numero_pianeti;

        return $this;
    }

    /**
     * Get the value of onore
     */ 
    public function getOnore()
    {
        return $this->onore;
    }

    /**
     * Set the value of onore
     *
     * @return  self
     */ 
    public function setOnore($onore)
    {
        $this->onore = $onore;

        return $this;
    }

    /**
     * Get the value of admin
     */ 
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */ 
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get the value of bannato
     */ 
    public function getBannato()
    {
        return $this->bannato;
    }

    /**
     * Set the value of bannato
     *
     * @return  self
     */ 
    public function setBannato($bannato)
    {
        $this->bannato = $bannato;

        return $this;
    }

    /**
     * Get the value of pianeta_corrente
     */ 
    public function getPianeta_corrente()
    {
        return $this->pianeta_corrente;
    }

}

?>