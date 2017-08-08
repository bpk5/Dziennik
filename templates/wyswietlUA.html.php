<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <form action="?strona=wyswietlUA&amp;akcja=wyswietlUczniow" method="post">
        <table class="tabelaWys">
            <tr class = "trDol">
                <td>
                    Wybierz klasę
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
                    <input type="submit" name="wyswietl" value="Wyświetl">
                </td>
            </tr>
        </table>
    </form>
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>

    <?php
    if ($this->getKomunikat()['flaga'] == '1') {
        
        echo '
        <table class="tabelaWys">
        <tr class = "trGora">
            <td>
                Lp.
            </td>
            <td>
                Imię
            </td>
            <td>
                Nazwisko
            </td>
            <td>
                PESEL
            </td>
        </tr>';
        
        $ile = count($this->getDaneBaza());

        for ($i = 0; $i < $ile; $i++) {
            echo '<tr class = "trDol">';
            echo '<td>' . ($i + 1) 
                    . '</td><td>' . $this->getDaneBaza()[$i][0] 
                    . '</td><td>' . $this->getDaneBaza()[$i][1] 
                    . '</td><td>' . $this->getDaneBaza()[$i][2] . '</td>'; 
            echo '</tr>';
            
        }
        
        
        echo '</table>';
    }
    ?>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
