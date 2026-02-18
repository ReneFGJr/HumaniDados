<?php
$lattes_atualizados = $lattes_atualizados ?? [];
if (isset($lattes_atualizados[0])) {
    $year = $lattes_atualizados[0]['ano'];
    $month = $lattes_atualizados[0]['mes'];
}

echo 'ùltima atualização: ' . ($year ?? 'N/A') . '-' . ($month ?? 'N/A');

$meses = [];
$max = 10;
foreach ($lattes_atualizados as $atualizacao) {
    $yearA = $atualizacao['ano'];
    $monthA = $atualizacao['mes'];
    $total = $atualizacao['total'];
    $anos = $year - $yearA;
    $mesesA = $anos * 12 + ($month - $monthA);
    if ($mesesA > 12) {
        if ($mesesA > 24) {
            if ($mesesA > 36) {
                if ($mesesA > 48) {
                    if ($mesesA > 60) {
                        $mesesA = 60;
                    } else {
                        $mesesA = 48;
                    }
                } else {
                    $mesesA = 36;
                }
            } else {
                $mesesA = 24;
            }
        } else {
            $mesesA = 12;
        }
    }
    if (isset($meses[$mesesA])) {
        $meses[$mesesA] = $meses[$mesesA] + $total;
    } else {
        $meses[$mesesA] = $total;
    }
    if ($meses[$mesesA] > $max) {
        $max = $meses[$mesesA];
    }
}

/*********  */
for($r=0;$r < 12; $r++) {
    if (!isset($meses[$r])) {
        $meses[$r] = 0;
    }
}
if (!isset($meses[12])) {
    $meses[12] = 0;
}
if (!isset($meses[24])) {
    $meses[24] = 0;
}
if (!isset($meses[36])) {
    $meses[36] = 0;
}
if (!isset($meses[48])) {
    $meses[48] = 0;
}
if (!isset($meses[60])) {
    $meses[60] = 0;
}
$mult = 200 / $max;
ksort($meses);
?>
        <table class="table table-bordered" style="width: 100%; height: 200px;">
            <?= $max ?> valor máximo
            <tbody>
                <tr>
                    <th colspan="17" class="text-center bg-light">quantidade de meses da atualização dos currículos Lattes</th>
                </tr>
                <tr>
                    <?php foreach ($meses as $mes => $total) : ?>
                        <td valign="bottom" class="text-center bold small bg-light p-0">
                            <?php
                            if ($mes > 12) {
                                echo $mes.'+';
                            } else {
                                echo $mes;
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach ($meses as $mes => $total) :
                        $bgcolor = 'green';
                        if ($mes > 12) {
                            $bgcolor = 'orange';
                        }
                        if ($mes > 24) {
                            $bgcolor = 'red';
                        }
                    ?>
                        <td width="5%" valign="bottom" class="text-center bold small p-0">
                            <?= $total ?>
                            <div style="background-color: <?= $bgcolor; ?>; height: <?= round($total * $mult); ?>px;"></div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
