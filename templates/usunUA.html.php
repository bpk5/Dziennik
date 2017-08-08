<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <form action="?strona=usunUA&amp;akcja=usuwanieOsoby" method="post">
        <table class="tabelaWys">
            <tr class = "trDol">
                <td>
                    PESEL:
                </td>
                <td>
                    <input type="text" name="pesel">
                    <input type="hidden" name="tabela" value="uczniowie">
                </td>
                <td>
                    <input type="submit" name="usun" value="Usuń">
                </td>
            </tr>
        </table>
    </form>
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>

    <?php
    if ($this->getKomunikat()['flaga'] == '1') {
        echo '
        <table class = "tabelaWys">
            <tr class = "trGora">
                <td>Lp.</td>
                <td>Imię</td>
                <td>Nazwisko</td>
                <td>PESEL</td>
            </tr>

            <tr class = "trDol">
                <td>1</td>
                <td>' . $this->getDaneBaza()['imie'] . '</td>
                <td>' . $this->getDaneBaza()['nazwisko'] . '</td>
                <td>' . $this->getDaneBaza()['pesel'] . '</td>
            </tr>
        </table>
        
        <table class = "tabelaWys">
            <tr>
                <td class="textRed">
                    Czy na pewno usunąć ucznia?
                </td>
                <td>
                    <form action="?strona=usunUA&amp;akcja=usuwanieOsoby" method="post">
                        <input type="hidden" name="iduzytkownika" value="' . $this->getDaneBaza()['iduzytkownika'] . '">
                        <input type="hidden" name="tabela" value="uczniowie">
                        <input type="submit" name="tak" value="TAK" class="przyciskZielony">
                    </form>
                </td>
                <td>
                    <form action="?strona=usunUA" method="post">
                        <input type="submit" name="nie" value="NIE" class="przyciskCzerwony">
                    </form>
                </td>
            </tr>
        </table>';
    }
    ?>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
