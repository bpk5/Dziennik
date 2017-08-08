<?php

/**
 * Każda metoda AKCJA odpowiada za wywołanie odpowiednich obiektów
 * działających na bazie, na koniec wywołuje obiekt odpowiadający
 * za wyświetlenie strony html.
 * 
 * @author Bartłomiej Kulesa
 */
class AkcjeModel {

    private $strona;

    public function __construct($strona) {
        $this->strona = $strona;
    }

    /**
     * LOGOWANIE AKCJA
     * Uruczamia obiekty i metody do logowania.
     */
    public function logowanie() {
        require_once 'model/weryfikacjaLoginHaslo.php';
        $view = $this->loadView();
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $weryfikacjaLG = new WeryfikacjaLoginHaslo($connection, $_POST['login'], $_POST['haslo']);
        $view->setKomunikat($weryfikacjaLG->zaloguj());
        // Rozłączenie z bazą.
        $polaczenieDB->close();
        // Wyświetlanie stronay.
        $view->render($this->strona);
    }

    /**
     * WYLOGOWANIE AKCJA
     * Kasowanie sesji i przekierowanie.
     */
    public function wyloguj() {
        session_unset();
        header('Location: ?strona=logowanie');
    }

    /**
     * DODAWANIE OSOBY AKCJA
     * Uruchamia obiekty i metody aby dodać osobę do bazy.
     */
    public function dodawanieOsoby() {
        require_once 'model/dodawanieOsoby.php';
        $view = $this->loadView();
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $dodawanieOsoby = new DodawanieOsoby($connection, $_POST['imie'], $_POST['nazwisko'], $_POST['pesel'], $_POST['klasa'], $_POST['login'], $_POST['haslo1'], $_POST['haslo2']);
        // Weryfikacja danych z formularza.
        $this->weryfikujWszystko($dodawanieOsoby);
        // Zamknięcie połączenia z bazą.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        $view->setKomunikat($dodawanieOsoby->getKomunikat());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Weryfikuje wszystkie pola.
     * Jeżeli wszystko się powiedzie dodaje dane do bazy.
     * @param obiekt $dodawanieOsoby obiekt dodający odobę do bazy
     */
    public function weryfikujWszystko($dodawanieOsoby) {
        // Tablica do zamianki $_get[strona] na profil (U lub N)
        $profilUN['dodajUA'] = 'U';
        $profilUN['dodajNA'] = 'N';
        // Każda metoda ma zwrócić TRUE.
        $wynik[] = $dodawanieOsoby->weryfikacjaImie();
        $wynik[] = $dodawanieOsoby->weryfikacjaNazwisko();
        $wynik[] = $dodawanieOsoby->weryfikacjaPESEL($profilUN[$_GET['strona']]);
        $wynik[] = $dodawanieOsoby->weryfikacjaLoginu();
        $wynik[] = $dodawanieOsoby->weryfikacjaHasla();
        if (($_POST['klasa'] != 'brak') and ( $profilUN[$_GET['strona']] == 'N')) {
            $wynik[] = $dodawanieOsoby->weryfikacjaWychowawcy();
        }

        // Sprawdzenie czy wszystkie testy wyszły poprawnie (TRUE)
        $test = TRUE;
        foreach ($wynik as $value) {
            if ($value == FALSE) {
                $test = FALSE;
            }
        }
        if ($test) {
            $dodawanieOsoby->dodajOsobe($profilUN[$_GET['strona']]);
        }
    }

    /**
     * USUWANIE OSOBY AKCJA
     * Uruchamia obiekty i metody aby usunąc osobę z bazy.
     */
    public function usuwanieOsoby() {
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        // Jeżeli wciśnięto przycisk TAK potwierdzający na stronie.
        if (isset($_POST['tak'])) {
            require_once 'model/usuwanieOsoby.php';
            $usuwanieOsoby = new UsuwanieOsoby($connection);
            $usuwanieOsoby->usunOsobe($_POST['iduzytkownika'], $_POST['tabela']);
            $view->setKomunikat($usuwanieOsoby->getKomunikat());
        }
        else {
            require_once 'model/pobierzOsobe.php';
            $pobierzOsobe = new PobierzOsobe($connection);
            $pobierzOsobe->wyszukajOsobe($_POST['tabela'], $_POST['pesel']);
            $view->setDaneBaza($pobierzOsobe->getDaneBaza());
            $view->setKomunikat($pobierzOsobe->getKomunikat());
        }
        $polaczenieDB->close();
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * WYŚWIETLANIE UCZNIÓW AKCJA
     * Uruchamia obiekty i metody przygotowujące informacje do wyświetlenia.
     * Pobiera dane o uczniach w danej klasie.
     * Uruchamia widok.
     */
    public function wyswietlUczniow() {
        require_once 'model/wyszukiwanieUczniow.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();

        $wyszukiwanieUczniow = new WyszukiwanieUczniow($connection);
        $wyszukiwanieUczniow->wyszukajUczniow($_POST['klasa']);
        // Zamknięcie połączenia.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        $view->setKomunikat($wyszukiwanieUczniow->getKomunikat());
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieUczniow->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Przypisuje przedmiot i klasę do nauczyciela.
     */
    public function przypiszPrzedmiot() {
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        // Jeżeli wciśnięto przycisk TAK potwierdzający na stronie.
        if (isset($_POST['tak'])) {
            // Kliknięto TAK dodanie wpisu do bazy.
            require_once 'model/przypisywaniePrzedmiotu.php';
            $przypiszPrzedmiot = new PrzypisywaniePrzedmiotu($connection);
            $przypiszPrzedmiot->dopiszPrzedmiot($_POST['pesel'], $_POST['klasa'], $_POST['przedmiot']);
            $view->setKomunikat($przypiszPrzedmiot->getKomunikat());
        }
        else {
            // Nic nie wciśnięto.
            require_once 'model/pobierzOsobe.php';
            $pobierzOsobe = new PobierzOsobe($connection);

            // Jeżeli wyszuka kogoś w bazie.
            if ($pobierzOsobe->wyszukajOsobe($_POST['tabela'], $_POST['pesel'])) {
                // Wyszukano, kontynuacja sprawdzania
                // Sprawdzenie czy przedmiot i klasa są już przypisane do innego nauczyciela.
                require_once 'model/przypisywaniePrzedmiotu.php';
                $przypiszPrzedmiot = new PrzypisywaniePrzedmiotu($connection);
                $przypiszPrzedmiot->sprawdzMozliwoscPrzypisania($_POST['klasa'], $_POST['przedmiot']);
                // Ustawianie komunikatów.
                $view->setKomunikat($przypiszPrzedmiot->getKomunikat());
                $dane = $pobierzOsobe->getDaneBaza();
                $dane['klasa'] = $_POST['klasa'];
                $dane['przedmiot'] = $_POST['przedmiot'];
                $view->setDaneBaza($dane);
            }
            else {
                $view->setKomunikat($pobierzOsobe->getKomunikat());
            }
        }
        // Rozłączenie z bazą.
        $polaczenieDB->close();
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Pobiera dane o nauczycielach z bazy, przekazuje do view i wywołuje
     * stronę html.
     */
    public function wyswietlNauczycieli() {
        require_once 'model/wyszukiwanieNauczycieli.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $wyszukiwanieNauczycieli = new WyszukiwanieNauczycieli($connection);
        $wyszukiwanieNauczycieli->wyszukajNauczycieli();
        // Zamknięcie połączenia.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        $view->setKomunikat($wyszukiwanieNauczycieli->getKomunikat());
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieNauczycieli->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Pobierze i wyświetli szczegółowe informacje o wybranym nauczycielu.
     */
    public function wyswietlNszczegoly() {
        require_once 'model/wyszukiwanieNauczSzczegolowe.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $wyszukiwanieNauczSzczegolowe = new WyszukiwanieNauczSzczegolowe($connection);
        $wyszukiwanieNauczSzczegolowe->wyszukajNauczyciela($_POST['idnauczyciela']);
        // Zamknięcie połączenia.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        $view->setKomunikat($wyszukiwanieNauczSzczegolowe->getKomunikat());
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieNauczSzczegolowe->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Odpina przedmiot i klasę od nauczyciela.
     * Wczytuje ponownie listę klas i przedmiotów i wywołuje widok.
     */
    public function odepnijPrzedmiot() {
        require_once 'model/wyszukiwanieNauczSzczegolowe.php';
        require_once 'model/odpinaniePrzedmiotu.php';
        $view = $this->loadView();
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $odpinaniePrzedmiotu = new OdpinaniePrzedmiotu($connection);
        $odpinaniePrzedmiotu->odepnijPrzedmiot($_POST['idnauczprzedm']);
        $wyszukiwanieNauczSzczegolowe = new WyszukiwanieNauczSzczegolowe($connection);
        $wyszukiwanieNauczSzczegolowe->wyszukajNauczyciela($_POST['idnauczyciela']);
        $polaczenieDB->close();
        $view->setKomunikat($odpinaniePrzedmiotu->getKomunikat());
        $view->setDaneBaza($wyszukiwanieNauczSzczegolowe->getDaneBaza());
        $view->render($this->strona);
    }

    /**
     * Dodawanie oceny do dziennika.
     * Pobieranie danych do wyświetlenia.
     * Wywołwnie strony.
     */
    public function dodajUsunOcene() {
        require_once 'model/wyszukiwanieUczniowOcen.php';
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();

        if (isset($_POST['dodaj'])) {
            $oc = $_POST['ocena'];
            if (($oc != '') and ( strlen($oc) > 0) and ( strlen($oc) < 3)) {
                require_once 'model/dodawanieUsuwanieOceny.php';
                $dodawanieUsuwanieOceny = new dodawanieUsuwanieOceny($connection);
                $dodawanieUsuwanieOceny->dodajOcene($_POST['iducznia'], $_POST['idnauczyciela'], $_POST['idprzedmiotu'], $oc);
            }
        }
        elseif (isset($_POST['usun'])) {
            if (isset($_POST['usunOcene'])) {
                require_once 'model/dodawanieUsuwanieOceny.php';
                $dodawanieUsuwanieOceny = new dodawanieUsuwanieOceny($connection);
                $dodawanieUsuwanieOceny->usunOcene($_POST['usunOcene']);
            }
        }

        $wyszukiwanieUczniowOcen = new WyszukiwanieUczniowOcen($connection);
        $wyszukiwanieUczniowOcen->wyszukajOcenyUczniow($_POST['klasa'], $_POST['idprzedmiotu']);

        $view = $this->loadView();
        $view->setDaneBaza($wyszukiwanieUczniowOcen->getDaneBaza());
        $view->render($this->strona);
        $polaczenieDB->close();
    }

    /**
     * Pobiera dane o uczniach, ocenach.
     */
    public function wyswietlUczniowOcen() {
        require_once 'model/wyszukiwanieUczniowOcen.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $wyszukiwanieUczniowOcen = new WyszukiwanieUczniowOcen($connection);
        $wyszukiwanieUczniowOcen->wyszukajOcenyUczniow($_POST['klasa'], $_POST['idprzedmiotu']);
        // Zamknięcie połączenia.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        //$view->setKomunikat();
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieUczniowOcen->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Pobiera i wyświetla dane o przedmiotach.
     */
    public function wyswietlNprzedm() {
        require_once 'model/wyszukiwanieNauczSzczegolowe.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $wyszukiwanieNauczSzczegolowe = new WyszukiwanieNauczSzczegolowe($connection);
        $wyszukiwanieNauczSzczegolowe->wyszukajNauczyciela($_SESSION['id']);
        // Zamknięcie połączenia.
        $polaczenieDB->close();
        // Przekazanie komunikatów.
        $view->setKomunikat($wyszukiwanieNauczSzczegolowe->getKomunikat());
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieNauczSzczegolowe->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Pobiera i wyświetla oceny.
     */
    public function wyswietlOceny() {
        require_once 'model/wyszukiwanieOcen.php';
        // loadView zwróci obiekt view.
        $view = $this->loadView();
        // Połączenie z bazą.
        $polaczenieDB = $this->polaczenie();
        $polaczenieDB->connect();
        $connection = $polaczenieDB->getConnection();
        $wyszukiwanieOcen = new WyszukiwanieOcen($connection);
        $wyszukiwanieOcen->wyszukajOceny($_SESSION['id']);
        $polaczenieDB->close();
        // Przekazanie danych z bazy
        $view->setDaneBaza($wyszukiwanieOcen->getDaneBaza());
        // Wyświetlanie strony.
        $view->render($this->strona);
    }

    /**
     * Pobiera pliki potrzebne do utworzenia połącznia z bazą.
     * Tworzy obiekt przygotowany do wykonania połączenia z bazą.
     * @return object
     */
    protected function polaczenie() {
        require_once 'model/configDB.php';
        require_once 'model/polaczenieDB.php';
        return new PolaczenieDB($host, $dbUser, $dbPassword, $dbName);
    }

    /**
     * Ładuje pliki potrzebne do wyświetlenia strony
     * @param string $path ścieżka do pliku.
     * @return obiekt View
     * @throws Exception
     */
    protected function loadView($path = 'view/view.php') {
        try {
            if (is_file($path)) {
                require $path;
                $view = new View();
            }
            else {
                throw new Exception('Nie można otworzyć pliku ' . $path);
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $view;
    }

}
