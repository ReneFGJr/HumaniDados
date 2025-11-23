<?php
// -------------------------------------------
// SISTEMA DE BUSCA NA VIEW
// -------------------------------------------
$busca = isset($_GET['q']) ? trim($_GET['q']) : '';

// Filtrar os pesquisadores antes da paginação
$pesquisadoresFiltrados = [];

if ($busca !== '') {
    foreach ($pesquisadores as $p) {
        if (
            stripos($p['nome_completo'], $busca) !== false ||
            stripos($p['idlattes'], $busca) !== false ||
            stripos($p['nacionalidade'], $busca) !== false ||
            stripos($p['situacao_coleta'], $busca) !== false
        ) {
            $pesquisadoresFiltrados[] = $p;
        }
    }
} else {
    $pesquisadoresFiltrados = $pesquisadores;
}

// -------------------------------------------
// PAGINAÇÃO
// -------------------------------------------
$porPagina = 50;
$paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$paginaAtual = max($paginaAtual, 1);

$totalItens = count($pesquisadoresFiltrados);
$totalPaginas = ceil($totalItens / $porPagina);

$inicio = ($paginaAtual - 1) * $porPagina;
$listaPaginada = array_slice($pesquisadoresFiltrados, $inicio, $porPagina);
?>

<div class="container py-4">

    <!-- Título e botões -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">Pesquisadores - Lattes</h3>

        <?php if (session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('lattes/create') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Novo</a>
            <a href="<?= base_url('lattes/import') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Importar</a>
            <a href="<?= base_url('lattes/harvesting') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Harvesting</a>
            <a href="<?= base_url('lattes/verify-files') ?>" class="btn btn-success"><i class="bi bi-recycle"></i></a>
        <?php endif; ?>
    </div>

    <!-- Caixa de busca -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Buscar por nome, ID Lattes, nacionalidade ou situação..."
                value="<?= esc($busca) ?>">

            <!-- Mantém a página sempre 1 quando filtra -->
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Buscar
            </button>

            <?php if ($busca): ?>
                <a href="<?= base_url('lattes') ?>" class="btn btn-secondary">
                    Limpar
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Tabela -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Lattes</th>
                <th>Nome</th>
                <th>Nacionalidade</th>
                <th>Doutorado</th>
                <th>Últ. Atualização</th>
                <th>Situação</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($listaPaginada)): ?>
                <tr>
                    <td colspan="7" class="text-center text-danger fw-bold py-3">
                        Nenhum registro encontrado.
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($listaPaginada as $p): ?>
                <tr>
                    <td><?= esc($p['idlattes']) ?></td>
                    <td><?= esc($p['nome_completo']) ?></td>
                    <td><?= esc($p['nacionalidade']) ?></td>
                    <td><?= esc($p['ano_doutorado']) ?></td>
                    <td><?= esc($p['data_atualizacao']) ?></td>

                    <td>
                        <?php
                        switch ($p['situacao_coleta']) {
                            case 'coletado':
                                $badgeClass = 'bg-success';
                                break;
                            case 'pendente':
                                $badgeClass = 'bg-secondary';
                                break;
                            case 'Erro':
                                $badgeClass = 'bg-danger';
                                break;
                            default:
                                $badgeClass = 'bg-info';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= esc($p['situacao_coleta']) ?></span>
                    </td>

                    <td class="text-center">
                        <a href="<?= base_url('lattes/view/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="<?= base_url('lattes/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <a href="<?= base_url('lattes/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Excluir este pesquisador?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINAÇÃO -->
    <?php if ($totalPaginas > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">

                <?php
                // Preservar termo de busca ao navegar
                $qParam = $busca ? "&q=" . urlencode($busca) : "";
                ?>

                <li class="page-item <?= ($paginaAtual <= 1 ? 'disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $paginaAtual - 1 . $qParam ?>">«</a>
                </li>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= ($paginaAtual == $i ? 'active' : '') ?>">
                        <a class="page-link" href="?page=<?= $i . $qParam ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($paginaAtual >= $totalPaginas ? 'disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $paginaAtual + 1 . $qParam ?>">»</a>
                </li>

            </ul>
        </nav>
    <?php endif; ?>

</div>