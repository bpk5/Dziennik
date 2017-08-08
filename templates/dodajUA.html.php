<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <form action="?strona=dodajUA&amp;akcja=dodawanieOsoby" method="post">

        <table class="tabelaWys">
            <tr class="trDol">
                <td>
                    Imię
                </td>
                <td>
                    <input type="text" name="imie">
                </td>
                <td class="textRed">
                    <?php echo $this->getKomunikat()['imie']; ?>
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Nazwisko
                </td>
                <td>
                    <input type="text" name="nazwisko">
                </td>
                <td class="textRed">
                    <?php echo $this->getKomunikat()['nazwisko']; ?>
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    PESEL
                </td>
                <td>
                    <input type="text" name="pesel">
                </td>
                <td class="textRed">
                    <?php echo $this->getKomunikat()['pesel']; ?>
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Klasa
                </td>
                <td>
                    <select name="klasa">
                        <option value="1A">1A</option>
                        <option value="1B">1B</option>
                        <option value="1C">1C</option>
                        <option value="2A">2A</option>
                        <option value="2B">2B</option>
                        <option value="2C">2C</option>
                        <option value="3A">3A</option>
                        <option value="3B">3B</option>
                        <option value="3C">3C</option>
                    </select>
                </td>
                <td>

                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Login
                </td>
                <td>
                    <input type="text" name="login">
                </td>
                <td class="textRed">
                    <?php echo $this->getKomunikat()['login']; ?>
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Hasło
                </td>
                <td>
                    <input type="password" name="haslo1">
                </td>
                <td>

                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Powtórz hasło
                </td>
                <td>
                    <input type="password" name="haslo2">
                </td>
                <td>
                    <?php echo $this->getKomunikat()['haslo']; ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="textPrawa">
                    <input type="submit" name="dodaj" value="Dodaj" class="przyciskWiekszy">
                </td>
            </tr>
        </table>


        <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>

    </form>
</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
