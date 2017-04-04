<?php

class ValidationException extends Exception {
    private $errors;
    
    public function __construct($errors, $previous = null) {
        parent::__construct(implode(", ", $errors));
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    /* Yleinen tallennusmetodi. Aliluokkien tulee toteuttaa metodit tallenna_uusi()
     * ja tallenna_vanha() insert- ja update-toiminnoille. Validointi on toteutettu
     * BaseModelin tallennuksessa, jolloin aliluokkien ei tarvitse kutsua validointia. 
     */

    public function tallenna() {
        $errors = $this->errors();
        
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if ($this->{'id'} == null) {
            $this->{'tallenna_uusi'}();
        } else {
            $this->{'tallenna_vanha'}();
        }
    }

    public function errors() {
        $errors = array();

        foreach ($this->validators as $validator) {
            $errors = array_merge($errors, $this->{$validator}());
        }

        return $errors;
    }

}
