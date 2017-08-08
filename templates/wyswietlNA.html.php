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
            <td>
                Wychowawca
            </td>
            <td>
                
            </td>
        </tr>
        <?php
        // trzeba wcześniej wywołąć metodę wyświetlającą.

        $ile = count($this->getDaneBaza());

        for ($i = 0; $i < $ile; $i++) {
            echo '<tr class = "trDol">';
            echo '<td>' . ($i + 1)
            . '</td><td>' . $this->getDaneBaza()[$i][1]
            . '</td><td>' . $this->getDaneBaza()[$i][2]
            . '</td><td>' . $this->getDaneBaza()[$i][3]
            . '</td><td>' . $this->getDaneBaza()[$i][4] . '</td>';
            echo '<td>
                <form action="?strona=wyswietlNAsz&amp;akcja=wyswietlNszczegoly" method="post">
                    <input type="hidden" name="idnauczyciela" value="' . $this->getDaneBaza()[$i][0] .'">
                    <input type="submit" name="szczegoly" value="Szczegóły">
                </form></td>';
            echo '</tr>';
        }
        ?>

    </table>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
