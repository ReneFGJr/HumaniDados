<?php
require("header/cab.php");

$indicadores = ['?ind=1' => 'Produção Científica', '?ind=2' => 'Produção Artistica'];
?>
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-3">
            <img src="/assets/logo/logo_humanidados.png" style="height: 100px">
        </div>
        <div class="col-12 col-lg-9">
            <h1 class="text-center">PAINEL</h1>
        </div>
        <?php
        foreach ($indicadores as $link => $label) { ?>
            <div class="col-12 col-sm-3">
                <?php echo $label; ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php
require("header/foot.php");
?>