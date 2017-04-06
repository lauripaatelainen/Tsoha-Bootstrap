<?php

class Julkaisu extends BaseModel {

    public $id = null, $kayttaja, $ryhma = null, $teksti, $aika;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }
    
    private static function lue_rivi($rivi) {
        $kayttaja = Kayttaja::hae($rivi['kayttaja']);
        $ryhma = null; /* Ryhma::hae($rivi['ryhma']); */
        return new Julkaisu(array(
            'id' => $rivi['id'],
            'kayttaja' => $kayttaja,
            'ryhma' => $ryhma,
            'teksti' => $rivi['teksti'],
            'aika' => $rivi['aika']
        ));
    }

    public static function kaikki_julkiset() {
        $kysely = DB::connection()->prepare('SELECT id, kayttaja, ryhma, teksti, aika FROM Julkaisu WHERE ryhma is null ORDER BY aika DESC');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $julkaisut = array();
        foreach ($rivit as $rivi) {
            $julkaisut[] = self::lue_rivi($rivi);
        }

        return $julkaisut;
    }

    public static function haeKayttajalla($kayttaja) {
        $kysely = DB::connection()->prepare('SELECT id, kayttaja, ryhma, teksti, aika FROM Julkaisu WHERE ryhma is null AND kayttaja = :kayttaja ORDER BY aika DESC');
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $julkaisut = array();
        foreach ($rivit as $rivi) {
            $julkaisut[] = self::lue_rivi($rivi);
        }

        return $julkaisut;
    }

    public static function hae($id) {
        $kysely = DB::connection()->prepare('SELECT id, kayttaja, ryhma, teksti, aika FROM Julkaisu WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            return self::lue_rivi($rivi);
        } else {
            return null;
        }
    }

    public function tallenna_uusi() {
        $kysely = DB::connection()->prepare('INSERT INTO Julkaisu(kayttaja, ryhma, teksti, aika) VALUES(:kayttaja, :ryhma, :teksti, :aika) RETURNING id');
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        if ($this->ryhma) {
            $kysely->bindValue(':ryhma', $this->ryhma->id);
        } else {
            $kysely->bindValue(':ryhma', null);
        }
        $kysely->bindValue(':teksti', $this->teksti);
        $kysely->bindValue(':aika', $this->aika);
        $kysely->execute();

        $row = $kysely->fetch();
        $this->id = $row['id'];
    }

    public function tallenna_vanha() {
        $kysely = DB::connection()->prepare('UPDATE Julkaisu SET teksti = :teksti, aika = :aika, kayttaja = :kayttaja, ryhma = :ryhma WHERE id = :id');
        $kysely->bindValue(':id', $this->id);
        $kysely->bindValue(':teksti', $this->teksti);
        $kysely->bindValue(':aika', $this->aika);
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        if ($this->ryhma) {
            $kysely->bindValue(':ryhma', $this->ryhma->id);
        } else {
            $kysely->bindValue(':ryma', null);
        }
        $kysely->execute();
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Julkaisu WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }
    
    public function haeKommentit() {
        return Kommentti::haeJulkaisulla($this);
    }
}
