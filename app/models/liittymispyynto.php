<?php
class Liittymispyynto extends BaseModel {

    public $kayttaja, $ryhma, $viesti;
    private $tallennettu = false;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    private static function lue_rivi($rivi) {
        $kayttaja = Kayttaja::hae($rivi['kayttaja']);
        $ryhma = Ryhma::hae($rivi['ryhma']);
        $liittymispyynto = new Liittymispyynto(array(
            'kayttaja' => $kayttaja,
            'ryhma' => $ryhma,
            'viesti' => $rivi['viesti']
        ));
        
        $liittymispyynto->tallennettu = true;
        
        return $liittymispyynto;
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Liittymispyynto');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $liittymispyynnot = array();
        foreach ($rivit as $rivi) {
            $liittymispyynnot[] = self::lue_rivi($rivi);
        }

        return $liittymispyynnot;
    }
    
    public static function hae($ryhma, $kayttaja) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Liittymispyynto WHERE ryhma = :ryhma AND kayttaja = :kayttaja LIMIT 1');
        $kysely->bindValue(':ryhma', $ryhma->id);
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        
        $rivi = $kysely->fetch();
        if ($rivi) {
            return self::lue_rivi($rivi);
        } else {
            return null;
        }
    }

    public function tallenna() {
        if ($this->tallennettu) {
            $kysely = DB::connection()->prepare('UPDATE Liittymispyynto SET viesti = :viesti WHERE kayttaja = :kayttaja AND ryhma = :ryhma');
            $kysely->bindValue(':ryhma', $this->ryhma->id);
            $kysely->bindValue(':kayttaja', $this->kayttaja->id);
            $kysely->bindValue(':viesti', $this->viesti);
            $kysely->execute();
        } else {
            $kysely = DB::connection()->prepare('INSERT INTO Liittymispyynto(ryhma, kayttaja, viesti) VALUES(:ryhma, :kayttaja, :viesti)');
            $kysely->bindValue(':ryhma', $this->ryhma->id);
            $kysely->bindValue(':kayttaja', $this->kayttaja->id);
            $kysely->bindValue(':viesti', $this->viesti);
            $kysely->execute();
            
            $this->tallennettu = true;
        }
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Liittymispyynto WHERE ryhma = :ryhma AND kayttaja = :kayttaja');
        $kysely->bindValue(':ryhma', $this->ryhma->id);
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        $kysely->execute();
    }
    
    public static function kayttajanLiittymispyynnot($kayttaja) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Liittymispyynto WHERE kayttaja = :kayttaja');
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $liittymispyynnot = array();
        foreach ($rivit as $rivi) {
            $liittymispyynnot[] = self::lue_rivi($rivi);
        }

        return $liittymispyynnot;
    }
    
    public static function ryhmanLiittymispyynnot($ryhma) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Liittymispyynto WHERE ryhma = :ryhma');
        $kysely->bindValue(':ryhma', $ryhma->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $liittymispyynnot = array();
        foreach ($rivit as $rivi) {
            $liittymispyynnot[] = self::lue_rivi($rivi);
        }

        return $liittymispyynnot;
    }
}