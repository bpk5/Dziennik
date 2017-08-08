<?php

/**
 * Sprawdza profil, zabezpiecza przed niepowołąnym wejściem na stronę,
 * przekierowuje na odpowiednie strony.
 * 
 * @author Bartłomiej Kulesa
 */
class WeryfikacjaProfilu {

    /**
     * Na podstawie zmiennej sesyjnej ustawia odpowiednią stronę.
     * Nauczyciel nie wejdzie na strony Ucznia itd.
     * 
     * @param char $profil A, U, N, L
     */
    public function weryfikujTyp($profil) {
        if (isset($_SESSION['profil'])) {

            if (($_SESSION['profil'] == 'A') and ( $profil != 'A')) {
                header('Location: ?strona=admin');
                exit();
            }
            elseif (($_SESSION['profil'] == 'U') and ( $profil != 'U')) {
                header('Location: ?strona=uczen&akcja=wyswietlOceny');
                exit();
            }
            elseif (($_SESSION['profil'] == 'N') and ( $profil != 'N')) {
                header('Location: ?strona=nauczyciel');
                exit();
            }
            elseif (($_SESSION['profil'] == 'L') and ( $profil != 'L')) {
                header('Location: ?strona=logowanie');
                exit();
            }
        }
        else {
            $_SESSION['profil'] = 'L';
            header('Location: ?strona=logowanie');
            exit();
        }
    }
}
