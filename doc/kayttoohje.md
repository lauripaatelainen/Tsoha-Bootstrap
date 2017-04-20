# Käyttöohje

Sovellus avataan osoitteesta: http://lpaatela.users.cs.helsinki.fi/tsoha/

Käyttäjätunnuksia testausta varten

Käyttäjätunnus | Salasana | On ylläpitäjä
---------------|----------|--------------
Kalle          | Kalle123! | Kyllä        
Pekka          | Pekka123! | 
Matti          | Matti123! | 
Mikko          | Mikko123! | 
Antti          | Antti123! | 
Hannu          | Hannu123! | 
Seppo          | Seppo123! | 
Jukka          | Jukka123! | 


Sovelluksesta on vielä seuraavat toiminnot kesken:
- Ryhmien listaus ja niihin liittyminen / liittymispyynnön lähettäminen
- Jäsenten kutsuminen ryhmiin
- Ryhmään julkaisu (testidatan kautta lisättyjä julkaisuja voi kuitenkin kommentoida ja niistä voi tykätä)

Etusivulta onnistuu julkaisujen teko, kommentointi ja tykkäys. Käyttäjän nimeä klikkaamalla pääsee käyttäjän julkaisuihin, josta
ylläpitäjät voivat myös siirtyä muokkaamaan käyttäjää. Etusivulla listataan julkisten julkaisujen lisäksi ryhmäjulkaisut niistä ryhmistä, joissa kirjautunut käyttäjä on jäsenenä. Näiden julkaisujen yhteydessä näytetään ryhmän nimi, jota klikkaamalla pääsee ryhmän sivulle. Ryhmän sivulla listataan vain kyseiseen ryhmään tehdyt julkaisut ja siellä toimii myös julkaisujen kommentointi ja tykkäys. 
Ryhmän sivulta ryhmän ylläpitäjä pääsee ryhmän muokkaukseen josta voi halutessaan muuttaa ryhmän nimeä ja kuvausta. Ryhmän jäsenten listaus on kaikille avoin, ryhmän ylläpitäjä voi poistaa sieltä jäseniä. 

Vasemmasta reunasta ryhmien listauksen alapuolelta kuka tahansa käyttäjä voi luoda uuden ryhmän. 

Suuri osa toiminnoista on vielä saatavilla vain [README:ssa](../README.md) olevien linkkien kautta. Toimintoja kannattaa testata kirjautumalla ensin ylläpitäjänä sisään, ja siirtymällä sivuille README:n linkkien kautta. 
