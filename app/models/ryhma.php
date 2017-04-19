<?php

class Ryhma extends BaseModel {

    public $id = null, $nimi, $kuvaus, $suljettu, $yllapitaja;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('tarkista_teksti');
    }
    
    private static function lue_rivi($rivi) {
        $yllapitaja = Kayttaja::hae($rivi['yllapitaja']);
        return new Ryhma(array(
            'id' => $rivi['id'],
            'nimi' => $rivi['nimi'],
            'kuvaus' => $rivi['kuvaus'],
            'suljettu' => $rivi['suljettu'],
            'yllapitaja' => $yllapitaja
        ));
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT id, nimi, kuvaus, suljettu, yllapitaja FROM Ryhma');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $ryhmat = array();
        foreach ($rivit as $rivi) {
            $ryhmat[] = self::lue_rivi($rivi);
        }

        return $ryhmat;
    }

    public static function hae($id) {
        $kysely = DB::connection()->prepare('SELECT id, nimi, kuvaus, suljettu, yllapitaja FROM Ryhma WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            return self::lue_rivi($rivi);
        } else {
            return null;
        }
    }

    public function tallenna_uusi() {
        $kysely = DB::connection()->prepare('INSERT INTO Ryhma(nimi, kuvaus, suljettu, yllapitaja) VALUES(:nimi, :kuvaus, :suljettu, :yllapitaja) RETURNING id');
        $kysely->bindValue(':nimi', $this->nimi);
        $kysely->bindValue(':kuvaus', $this->kuvaus);
        $kysely->bindValue(':suljettu', $this->suljettu, PDO::PARAM_BOOL);
        $kysely->bindValue(':yllapitaja', $this->yllapitaja->id);
        $kysely->execute();

        $row = $kysely->fetch();
        $this->id = $row['id'];
    }

    public function tallenna_vanha() {
        $kysely = DB::connection()->prepare('UPDATE Ryhma SET nimi = :nimi, kuvaus = :kuvaus, suljettu = :suljettu, yllapitaja = :yllapitaja WHERE id = :id');
        $kysely->bindValue(':nimi', $this->nimi);
        $kysely->bindValue(':kuvaus', $this->kuvaus);
        $kysely->bindValue(':suljettu', $this->suljettu, PDO::PARAM_BOOL);
        $kysely->bindValue(':yllapitaja', $this->yllapitaja->id);
        $kysely->execute();
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Ryhma WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }
    
    /**
     * Palauttaa listan käyttäjistä jotka ovat ryhmän jäseniä
     */
    public function haeJasenet() {
        return Kayttaja::haeRyhmaJasenyydella($this);
    }
    
    /**
     * Palauttaa listan ryhmän julkaisuista
     */
    public function haeJulkaisut() {
        return Julkaisu::haeRyhmalla($this);
    }
    
    /**
     * Palauttaa listan ryhmistä, joissa annettu käyttäjä on jäsenenä
     */
    public function haeJasenella($kayttaja) {
        $kysely = DB::connection()->prepare('SELECT id, nimi, kuvaus, suljettu, yllapitaja FROM Ryhma INNER JOIN RyhmanJasenyys ON RyhmanJasenyys.ryhma = Ryhma.id WHERE RyhmanJasenyys.kayttaja = :kayttaja UNION SELECT id, nimi, kuvaus, suljettu, yllapitaja  FROM Ryhma WHERE Ryhma.yllapitaja = :kayttaja');
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $ryhmat = array();
        foreach ($rivit as $rivi) {
            $ryhmat[] = self::lue_rivi($rivi);
        }
        return $ryhmat;
    }
    
    public function poistaJasen($kayttaja) {
        $kysely = DB::connection()->prepare('DELETE FROM RyhmanJasenyys WHERE kayttaja = :kayttaja AND ryhma = :ryhma');
        $kysely->bindValue(':ryhma', $this->id);
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
    }
}
