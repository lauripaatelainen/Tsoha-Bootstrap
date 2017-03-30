<?php
class Kayttaja extends BaseModel {
    public $id, $kayttajatunnus, $salasana, $yllapitaja = false;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $kayttajat = array();
        foreach ($rivit as $rivi) {
            $kayttajat[] = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
        }
        
        return $kayttajat;
    }
    
    public static function find($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }
    
    public static function haeKayttajatunnuksella($kayttajatunnus) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus LIMIT 1');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }
    
    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES(:kayttajatunnus, :salasana, :yllapitaja) RETURNING id');
        $kysely->execute(array(
            'kayttajatunnus' => $this->kayttajatunnus,
            'salasana' => $this->salasana,
            'yllapitaja' => 'false'
        ));
        $row = $kysely->fetch();
        $this->id = $row['id'];
    }
}
