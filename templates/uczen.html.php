<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('U');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuU.html.php'; ?>
    
    <table class="tabelaWys">
        <tr class="trGora">
            <td>Przedmiot</td>
            <td>Oceny</td>
        </tr>
        <tr class="trDol">
            <td>
                J.polski
            </td>
            <td>
                <?php echo $this->podajOceny(0); ?>
            </td>
        </tr>
        <tr class="trDol">
            <td>
                Matematyka
            </td>
            <td>
                <?php echo $this->podajOceny(1); ?>
            </td>
        </tr>
        <tr class="trDol">
            <td>
                Fizyka
            </td>
            <td>
                <?php echo $this->podajOceny(2); ?>
            </td>
        </tr>
        <tr class="trDol">
            <td>
                Plastyka
            </td>
            <td>
                <?php echo $this->podajOceny(3); ?>
            </td>
        </tr>
        <tr class="trDol">
            <td>
                Wf
            </td>
            <td>
                <?php echo $this->podajOceny(4); ?>
            </td>
        </tr>
        <tr class="trDol">
            <td>
                Geografia
            </td>
            <td>
                <?php echo $this->podajOceny(5); ?>
            </td>
        </tr>
    </table>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>


