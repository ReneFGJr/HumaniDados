<div class="container">
    <div class="row">
        <h1>Indicadores</h1>
        <div class="col-md-4">
            <?php echo view('indicators/mods/lattes_update', ['lattes_atualizados' => $lattes_atualizados]); ?>
        </div>
        <div class="col-md-6">
            <?php echo view('indicators/mods/lattes_areas', ['lattes_areas' => $areas_conhecimento_all]); ?>
        </div>
    </div>
</div>