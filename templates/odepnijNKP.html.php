<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>

<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <form action="?strona=odepnijNKP&amp;akcja=odepnijPrzedmiot" method="post">
        <table class="tabelaWys">
            <tr class="trGora">
                <td>
                    Imię
                </td>
                <td>
                    Nazwisko
                </td>
                <td>
                    Klasa
                </td>
                <td>
                    Przedmiot
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    <?php echo $_POST['imie']; ?>
                </td>
                <td>
                    <?php echo $_POST['nazwisko']; ?>
                </td>
                <td>
                    <?php echo $_POST['klasa']; ?>
                </td>
                <td>
                    <?php echo $_POST['przedmiot']; ?>
                </td>
            </tr>
        </table>
    </form>
    <table class = "tabelaWys">
        <tr>
            <td class="textRed">
                Czy na pewno odpiąć przedmiot i klasę od nauczyciela?
            </td>
            <td>
                <form action="?strona=wyswietlNAsz&amp;akcja=odepnijPrzedmiot" method="post">
                    <input type="hidden" name="idnauczyciela" value="<?php echo $_POST['idnauczyciela']; ?>">
                    <input type="hidden" name="idprzedmiotu" value="<?php echo $_POST['idprzedmiotu']; ?>">
                    <input type="hidden" name="idnauczprzedm" value="<?php echo $_POST['idnauczprzedm']; ?>">
                    <input type="hidden" name="idklasy" value="<?php echo $_POST['idklasy']; ?>">
                    <input type="hidden" name="odpinaj" value="1">
                    <input type="submit" name="tak" value="TAK" class="przyciskZielony">
                </form>
            </td>
            <td>
                <form action="?strona=wyswietlNAsz&amp;akcja=wyswietlNszczegoly" method="post">
                    <input type="hidden" name="idnauczyciela" value="<?php echo $_POST['idnauczyciela']; ?>">
                    <input type="submit" name="nie" value="NIE" class="przyciskCzerwony">
                </form>
            </td>
        </tr>
    </table>

    <br>
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>


</article>

<?php require_once 'templates/footer/footer.html.php'; ?>