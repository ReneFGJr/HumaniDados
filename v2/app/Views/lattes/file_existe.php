<?php
$idLattes = $pesquisador['idlattes'];
$xmlPath  = ROOTPATH . '../database/xml/' . $idLattes . '.xml';
if (file_exists($xmlPath)) {
    echo '<span class="badge bg-success me-2"><i class="bi bi-check-circle me-1"></i> XML disponível</span>';
    echo '<a href="' . base_url('/lattes/process/' . $pesquisador['idlattes']) . '" class="text-success"><i class="bi bi-pc-display me-2"></i></a>';
} else {
    echo '<a href="'.base_url('/lattes/extractor/'.$pesquisador['idlattes']).'"><i class="bi bi-recycle me-2"></i></a>';
    echo '<span class="badge bg-danger me-2"><i class="bi bi-x-circle me-1"></i> XML não encontrado</span>';
}
