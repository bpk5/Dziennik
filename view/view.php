<?php

/**
 * Przechowuje informacje potrzebne do wyświetlenia w headerze strony.
 * Wczytuje pliki html.
 * Posiada pola:
 *      $komunikat jako tablica asocjacyjna z różnymi komunikatami
 *          przekazanymi z innych obiektów.
 *      $daneBaza jako tablica asocjacyjna z danymi wczytanymi przez obiekty
 *          działające bezpośrednio na bazie danych (dane z bazy danych).
 */
class View {
    
    private $daneBaza;
    private $komunikat;
    private $informacje;
    
    public function __construct() {
        // Informacje do wyświetlania w headerze.
        // W zależności od tego na jaką stronę było przekierowanie
        // podajemy odpowiednią informację.
        $this->informacje['logowanie'] = 'Zaloguj się.';
        $this->informacje['admin'] = 'Strona administratora.';
        $this->informacje['dodajUA'] = 'Dodaj ucznia do bazy.';
        $this->informacje['usunUA'] = 'Usuń ucznia z bazy.';
        $this->informacje['wyswietlUA'] = 'Wyświetl uczniów.';
        $this->informacje['dodajNA'] = 'Dodaj nauczyciela do bazy.';
        $this->informacje['usunNA'] = 'Usuń nauczyciela z bazy.';
        $this->informacje['wyswietlNA'] = 'Informacje o nauczycielach.';
        $this->informacje['przypiszNKP'] = 'Przypisz nauczycielowi przedmiot i klasę.';
        $this->informacje['odepnijNKP'] = 'Odepnij przedmiot i klasę od nauczyciela.';
        $this->informacje['wyswietlNAsz'] = 'Szczegółowe informacje o nauczycielu.';
        $this->informacje['nauczyciel'] = 'Strona nauczyciela.';
        $this->informacje['dodajOcene'] = 'Dodaj ocenę do dziennika.';
        $this->informacje['usunOcene'] = 'Usuń ocenę z dziennika.';
        $this->informacje['uczen'] = 'Wszystkie przedmioty i oceny ucznia.';
        $this->informacje['wybieranieN'] = 'Wybierz klasę i przedmiot aby dodać ocenę.';
        
        // Ustawienie pustych komunikatów.
        $this->komunikat['ogolny'] = '';
        $this->komunikat['imie'] = '';
        $this->komunikat['nazwisko'] = '';
        $this->komunikat['pesel'] = '';
        $this->komunikat['login'] = '';
        $this->komunikat['haslo'] = '';
        $this->komunikat['wychowawca'] = '';
        $this->komunikat['flaga'] = '0';
    }

    /**
     * Ładuje plik strony html.
     * 
     * @param string $nazwa
     * @param string $path
     * @throws Exception
     */
    public function render($nazwa, $path = 'templates/') {
        $path = $path . $nazwa . '.html.php';
       
        try {
            if (is_file($path)) {
                require_once $path;
            }
            else {
                throw new Exception('Nie można otworzyć ' . $nazwa . ' w: ' . $path);
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    /**
     * Wywołując tą metodę otrzymamy jeden string z ocenami oddzielonymi spacją.
     * 
     * @param int $nrp nr przedmiotu (oceny są w tablicy).
     */
    public function podajOceny($nrp) {
        $rozmiar = count($this->daneBaza[$nrp]);
        $oceny = '';
        
        for ($i = 0; $i < $rozmiar; $i++) {
            $oceny .= $this->daneBaza[$nrp][$i];
            $oceny .= ' ';
        }
        
        return $oceny;
    }


    /**
     * Ustawia pole $daneBaza danymi z bazy danych.
     * 
     * @param array $daneBaza
     */
    public function setDaneBaza($daneBaza) {
        $this->daneBaza = $daneBaza;
    }
    
    /**
     * Zwraca dane z bazy danych.
     * 
     * @return array
     */
    public function getDaneBaza() {
        return $this->daneBaza;
    }
    
    /**
     * Ustawia ogólne komunikaty do wyświetlenia na stronie html.
     * 
     * @param array $komunikat
     */
    public function setKomunikat($komunikat) {
        $this->komunikat = $komunikat;
    }
    
    /**
     * Zwraca tablicę z komunikatami do wyświetlenia na stronie html.
     * 
     * @return array
     */
    public function getKomunikat() {
        return $this->komunikat;
    }
    
    /**
     * Zwraca odpowiednie informacje dla headera na podstawie podanej nazwy.
     * @param string $nazwa nazwa komórki z tablicy assocjacyjnej.
     * @return type
     */
    public function getInformacje($nazwa) {
        return $this->informacje[$nazwa];
    }

}
