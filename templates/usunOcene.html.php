<?PHP
require_once 'controller/weryfikacjaProfilu.php';
$ob = new WeryfikacjaProfilu();
$ob->weryfikujTyp('N');
?>
<?php require_once 'templates/header.html.php'; ?>

<article>
    <?php require_once 'templates/menu/menuN.html.php'; ?>
    
    <h4>usun ocenę!</h4>
    
    
    <span class="spanCenter"><?php echo $this->getKomunikat()['ogolny']; ?></span>
</article>

<?php require_once 'templates/footer/footer.html.php'; ?>