<div class="container py-5">

    <h2 class="mb-4 text-center">Glossário de Termos</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-end">Termo</th>
                        <th>Descrição</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($glossario)): ?>
                        <?php foreach ($glossario as $item): ?>
                            <tr valign="top">
                                <td class="text-end"><strong><?= $item['term_termo'] ?></strong></td>
                                <td><?= $item['term_description'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan=" 4" class="text-center text-muted py-3">
                                Nenhum termo encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>