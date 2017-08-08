<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <title>Dziennik elektroniczny</title>
    </head>
    <body>
        <header>
            <table>
                <tr>
                    <td>Dziennik Elektroniczny</td>
                    <td></td>
                    <td>
                        <?php
                        if (isset($_SESSION['imie'])) {
                            echo 'Zalogowany: ';
                            echo '<b>' . $_SESSION['imie'] . ' ' . $_SESSION['nazwisko'] . '</b>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <br>
            <?php
            echo '<b>' . $this->getInformacje($_GET['strona']) . '</b>';
            ?>
        </header>