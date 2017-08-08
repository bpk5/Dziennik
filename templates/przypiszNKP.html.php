<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <form action="?strona=przypiszNKP&amp;akcja=przypiszPrzedmiot" method="post">
        <table class="tabelaWys">
            <tr class="trDol">
                <td>
                    PESEL nauczyciela
                </td>
                <td>
                    <input type="text" name="pesel">
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
            </tr>
            <tr class="trDol">
                <td>
                    Przedmiot
                </td>
                <td>
                    <select name="przedmiot">
                        <option value="j.polski">j.polski</option>
                        <option value="matematyka">matematyka</option>
                        <option value="fizyka">fizyka</option>
                        <option value="plastyka">plastyka</option>
                        <option value="wf">wf</option>
                        <option value="geografia">geografia</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td class="textPrawa">
                    <input type="hidden" name="tabela" value="nauczyciele">
                    <input type="submit" name="przypisz" value="przypisz">
                </td>
            </tr>
        </table>
    </form>
    <br>
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
                <td>Klasa</td>
                <td>Przedmiot</td>
            </tr>

            <tr class = "trDol">
                <td>1</td>
                <td>' . $this->getDaneBaza()['imie'] . '</td>
                <td>' . $this->getDaneBaza()['nazwisko'] . '</td>
                <td>' . $this->getDaneBaza()['pesel'] . '</td>
                <td>' . $this->getDaneBaza()['klasa'] . '</d>
                <td>' . $this->getDaneBaza()['przedmiot'] . '</d>
            </tr>
        </table>
        
        <table class = "tabelaWys">
            <tr>
                <td class="textRed">
                    Przypisać przedmiot i klasę nauczycielowi?
                </td>
                <td>
                    <form action="?strona=przypiszNKP&amp;akcja=przypiszPrzedmiot" method="post">
                        <input type="hidden" name="pesel" value="' . $this->getDaneBaza()['pesel'] . '">
                        <input type="hidden" name="klasa" value="' . $this->getDaneBaza()['klasa'] . '">
                        <input type="hidden" name="przedmiot" value="' . $this->getDaneBaza()['przedmiot'] . '">
                        <input type="submit" name="tak" value="TAK" class="przyciskZielony">
                    </form>
                </td>
                <td>
                    <form action="?strona=przypiszNKP" method="post">
                        <input type="submit" name="nie" value="NIE" class="przyciskCzerwony">
                    </form>
                </td>
            </tr>
        </table>';
    }
    ?>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>