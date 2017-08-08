<?php

/**
 * Steruje uruchamianiem obiektów i metod.
 * Na podstawie nazwy strony wybiera odpowienią stronę html do wyświetlenia.
 * Na podstawie nazwy akcji uruchamia odpowiednie metody AKCJE.
 * 
 * @author Bartłomiej Kulesa
 */
class Controller {

    /**
     * Uruchamia metodę pobierającą stronę html.
     * @param string $nazwa nazwa strony (bez .html)
     */ 
    public function wybierzStrone($nazwa) {
        $view = $this->loadView();
        $view->render($nazwa);
    }

    /**
     * 
     * @param string $strona nazwa strony (bez .html)
     * @param string $akcja nazwa metody AKCJI.
     * @throws Exception
     */
    public function wybierzAkcje($strona, $akcja) {
        require_once 'model/akcjeModel.php';
        $akcjaModel = new AkcjeModel($strona);
        // Wywołanie metody o nazwie ze zmiennej $akcja.
        try {
            if (method_exists($akcjaModel, $akcja)) {
                $akcjaModel->$akcja();
            }
            else {
                throw new Exception('Brak metody ' . $akcja);
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
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
