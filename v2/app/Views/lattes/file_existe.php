<?php
$idLattes = $pesquisador['idlattes'];
$xmlPath  = ROOTPATH . '../database/xml/' . $idLattes . '.xml';
if (file_exists($xmlPath)) {
    echo '<span class="badge bg-success me-2"><i class="bi bi-check-circle me-1"></i> XML disponível</span>';
} else {
    echo '<span class="badge bg-danger me-2"><i class="bi bi-x-circle me-1"></i> XML não encontrado</span>';
}   
