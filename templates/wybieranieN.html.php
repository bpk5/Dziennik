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
                <?php echo $this->getDaneBaza()[0][4]; ?>
            </td>
            <td>
                <?php echo $this->getDaneBaza()[0][5]; ?>
            </td>
            <td>
                <form action="?strona=dodajOcene&amp;akcja=wyswietlUczniowOcen" method="post">
                    <input type="hidden" name="przedmiot" value="<?php echo $this->getDaneBaza()[0][5]; ?>">
                    <input type="hidden" name="idnauczyciela" value="<?php echo $this->getDaneBaza()[0][7]; ?>">
                    <input type="hidden" name="idprzedmiotu" value="<?php echo $this->getDaneBaza()[0][8]; ?>">
                    <input type="hidden" name="idklasy" value="<?php echo $this->getDaneBaza()[0][9]; ?>">
                    <input type="hidden" name="klasa" value="<?php echo $this->getDaneBaza()[0][4]; ?>">
                    <input type="submit" name="uczniowie" value="Uczniowie">
                </form>
            </td>

        </tr>
        <?php
        // trzeba wcześniej wywołąć metodę wyświetlającą.

        $ile = count($this->getDaneBaza());

        for ($i = 1; $i < $ile; $i++) {
            echo '<tr class = "trDol">';
            echo '<td>' . $this->getDaneBaza()[$i][4]
            . '</td><td>' . $this->getDaneBaza()[$i][5]
            . '</td><td>
                <form action="?strona=dodajOcene&amp;akcja=wyswietlUczniowOcen" method="post">
                    
                    <input type="hidden" name="przedmiot" value="' . $this->getDaneBaza()[$i][5] .'">
                    <input type="hidden" name="idnauczyciela" value="' . $this->getDaneBaza()[$i][7] .'">
                    <input type="hidden" name="idprzedmiotu" value="' . $this->getDaneBaza()[$i][8] .'">
                    <input type="hidden" name="idklasy" value="' . $this->getDaneBaza()[$i][9] .'">
                    <input type="hidden" name="klasa" value="' . $this->getDaneBaza()[$i][4] .'">
                    <input type="submit" name="uczniowie" value="Uczniowie">
                </form></td>';
            echo '</tr>';
        }
        ?>

    </table>
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>
</article>

<?php require_once 'templates/footer/footer.html.php'; ?>
