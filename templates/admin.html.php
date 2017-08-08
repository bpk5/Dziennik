<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('A');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuA.html.php'; ?>

    <h4>Dzień dobry!</h4>
    <h4>Jesteś w oknie administratora.</h4>

</article>

<?php require_once 'templates/footer/footer.html.php'; ?>

