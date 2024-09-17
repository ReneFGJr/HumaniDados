<?php
require("header/cab.php");

$ind = $_GET['ind'];


switch ($ind) {
    default:
        $indicadores = ['?ind=0' => 'Pesquisadores', '?ind=1' => 'Produção Científica', '?ind=2' => 'Produção Artistica', '?ind=3' => 'Produção Técnica'];
        echo '
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <img src="/assets/logo/logo_humanidados.png" style="height: 100px">
                        </div>
                        <div class="col-12 col-lg-9">
                            <h1 class="text-center">PAINEL</h1>
                        </div>';

        foreach ($indicadores as $link => $label) {
            echo '<div class="box col-12 col-sm-3 border border-secondary shadow rounded p-5 big text-center m-3" onclick="goURL(\'' . $link . '\');">';
            echo $label;
            echo '</div>';
        }

        echo '</div>';
        break;

    case '0':
        echo '<div class="container"><div class="row">';
        echo '<h1>Pesquisador</h1>';
        echo '<iframe src="https://dashboard.brapci.inf.br/goto/2c166350-7521-11ef-8ce1-a98ecc8d01af" height="600" width="800"></iframe>';
        echo '</div></div>';
        break;
}


require("header/foot.php");
?>

<script>
    function goURL(url) {
        window.location.href = url;
    }
</script>

<style>
    .box:hover {
        background-color: #F8F8F8;
        cursor: pointer;
    }
</style>