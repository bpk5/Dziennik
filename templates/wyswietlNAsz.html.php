<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <table class="tabelaWys">
        <tr class="trGora">
            <td>
                Imie
            </td>
            <td>
                Nazwisko
            </td>
            <td>
                PESEL
            </td>
            <td>
                Wychowawca
            </td>
            <td>
                Przypisana klasa
            </td>
            <td>
                Przypisany przedmiot
            </td>
            <td>
                
            </td>

        </tr>
        <tr class="trDol">
            <td>
                <?php echo $this->getDaneBaza()[0][0]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][1]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][2]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][3]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][4]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][5]; ?>
            </td>
            <td>
                <form action="?strona=odepnijNKP" method="post">
                    <input type="hidden" name="imie" value="<?php echo $this->getDaneBaza()[0][0]; ?>">
                    <input type="hidden" name="nazwisko" value="<?php echo $this->getDaneBaza()[0][1]; ?>">
                    <input type="hidden" name="przedmiot" value="<?php echo $this->getDaneBaza()[0][5]; ?>">
                    <input type="hidden" name="klasa" value="<?php echo $this->getDaneBaza()[0][4]; ?>">
                    <input type="hidden" name="idnauczyciela" value="<?php echo $this->getDaneBaza()[0][7]; ?>">
                    <input type="hidden" name="idnauczprzedm" value="<?php echo $this->getDaneBaza()[0][6]; ?>">
                    <input type="submit" name="odepnij" value="Odepnij">
                </form>
            </td>

        </tr>
        <?php
        // trzeba wcześniej wywołąć metodę wyświetlającą.

        $ile = count($this->getDaneBaza());

        for ($i = 1; $i < $ile; $i++) {
            echo '<tr class = "trDol">';
            echo '<td>' . $this->getDaneBaza()[$i][0]
            . '</td><td>' . $this->getDaneBaza()[$i][1]
            . '</td><td>' . $this->getDaneBaza()[$i][2]
            . '</td><td>' . $this->getDaneBaza()[$i][3]
            . '</td><td>' . $this->getDaneBaza()[$i][4]
            . '</td><td>' . $this->getDaneBaza()[$i][5]
            . '</td><td>
                <form action="?strona=odepnijNKP" method="post">
                    <input type="hidden" name="imie" value="' . $this->getDaneBaza()[0][0] .'">
                    <input type="hidden" name="nazwisko" value="' . $this->getDaneBaza()[0][1] .'">
                    <input type="hidden" name="przedmiot" value="' . $this->getDaneBaza()[$i][5] .'">
                    <input type="hidden" name="klasa" value="' . $this->getDaneBaza()[$i][4] .'">
                    <input type="hidden" name="idnauczyciela" value="' . $this->getDaneBaza()[$i][7] .'">
                    <input type="hidden" name="idnauczprzedm" value="' . $this->getDaneBaza()[$i][6] .'">
                    <input type="submit" name="odepnij" value="Odepnij">
                </form></td>';
            echo '</tr>';
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="textPrawa">
                <form action="?strona=wyswietlNA&amp;akcja=wyswietlNauczycieli" method="post">
                    <input type="submit" value="Powrót">
                </form>
            </td>
        </tr>
    </table>
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>
</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
