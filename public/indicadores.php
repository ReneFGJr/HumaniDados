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
            <div class="box col-12 col-sm-3 border border-secondary shadown rounded p-5 big text-center m-3">
                <?php echo $label; ?>
            </div>
        <?php } ?>
    </div>
</div>
<style>
    .box:hover {
        background-color: #F8F8F8;
        cursor: pointer;
    }
</style>
<?php
require("header/foot.php");
?>