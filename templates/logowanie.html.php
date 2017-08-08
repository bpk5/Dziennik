<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('L');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>

    <form action="?strona=logowanie&amp;akcja=logowanie" method="post">
        <table class="tabelaWys">
            <tr class="trDol">
                <td>
                    Login
                </td>
                <td>
                    <input type="text" name="login">
                </td>
            </tr>
            <tr class="trDol">
                <td>
                    Hasło
                </td>
                <td>
                    <input type="password" name="haslo">
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td class="textPrawa">
                    <input type="submit" value="Zaloguj się">
                </td>
            </tr>
        </table>
    </form>
    <br>
    <span class="spanCenter textRed"><?php echo $this->getKomunikat()['ogolny']; ?></span>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>