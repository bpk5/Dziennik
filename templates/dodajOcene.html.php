<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('N');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuN.html.php'; ?>


    <table class="tabelaWys">
        <tr class="trGora">
            <td>Lp.</td>
            <td>Imię</td>
            <td>Nazwisko</td>
            <td>Przedmiot</td>
            <td>Oceny</td>
            <td>Podaj ocenę</td>
        </tr>
        <?php
        $ile = count($this->getDaneBaza());

        for ($i = 0; $i < $ile; $i++) {
            echo '<tr class = "trDol">';
            echo '<td>' . ($i + 1)
            . '</td><td>' . $this->getDaneBaza()[$i][0]
            . '</td><td>' . $this->getDaneBaza()[$i][1]
            . '</td><td>' . $_POST['przedmiot']
            . '</td><td>
                <form action="?strona=dodajOcene&amp;akcja=dodajUsunOcene" method="post">'
                    . $this->getDaneBaza()[$i][5] . '
                        <input type="hidden" name="iducznia" value="' . $this->getDaneBaza()[$i][4] . '">
                        <input type="hidden" name="idnauczyciela" value="' . $_POST['idnauczyciela'] . '">
                        <input type="hidden" name="idprzedmiotu" value="' . $_POST['idprzedmiotu'] . '">
                        <input type="hidden" name="klasa" value="' . $_POST['klasa'] . '">
                        <input type="hidden" name="przedmiot" value="' . $_POST['przedmiot'] . '">
                        <input type="submit" name="usun" value="Usuń"> 
                </form>
                </td>';

            echo '<td>
                    <form action="?strona=dodajOcene&amp;akcja=dodajUsunOcene" method="post">
                        <input type="text" name="ocena">
                        <input type="hidden" name="iducznia" value="' . $this->getDaneBaza()[$i][4] . '">
                        <input type="hidden" name="idnauczyciela" value="' . $_POST['idnauczyciela'] . '">
                        <input type="hidden" name="idprzedmiotu" value="' . $_POST['idprzedmiotu'] . '">
                        <input type="hidden" name="klasa" value="' . $_POST['klasa'] . '">
                        <input type="hidden" name="przedmiot" value="' . $_POST['przedmiot'] . '">
                        <input type="submit" name="dodaj" value="Dodaj">
                    </form>
                </td>';

            echo '</tr>';
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="textPrawa">
                <form action="?strona=wybieranieN&amp;akcja=wyswietlNprzedm" method="post">
                    <input type="submit" value="Powrót">
                </form>
            </td>
        </tr>

    </table>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
