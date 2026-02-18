<div class="tab-pane fade" id="knowledge" role="tabpanel">
    <h3 class="text-hd-info mb-4"><i class="bi bi-diagram-3"></i>
        Áreas de conhecimento</h3>
    <!---------------------------------------- Arvore de áreas de conhecimento ---------------------------------------- -->
    <?php
    $areasAll = $pesquisador['areas_conhecimento_all'];
    ?>
    <div class="tree bg-white p-4 rounded shadow-sm">

        <?php
        function renderTree($array)
        {
            echo "<ul>";

            foreach ($array as $key => $value) {

                if ($key === 'total') continue;

                $total = $value['total'] ?? null;

                echo "<li>";

                if (is_array($value)) {

                    echo "<span class='tree-toggle fw-semibold'>";
                    echo "<i class='bi bi-folder2-open me-1'></i>";
                    echo esc($key);

                    if ($total !== null) {
                        echo " <span class='badge bg-primary badge-total'>{$total}</span>";
                    }

                    echo "</span>";

                    renderTree($value);
                } else {
                    echo "<i class='bi bi-file-earmark-text me-1'></i>";
                    echo esc($key);
                }

                echo "</li>";
            }

            echo "</ul>";
        }

        renderTree($areasAll);
        ?>

    </div>


    <script>
        document.querySelectorAll('.tree-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {

                const nextUl = this.parentElement.querySelector('ul');

                if (nextUl) {
                    nextUl.classList.toggle('d-none');

                    const icon = this.querySelector('i');

                    if (icon) {
                        icon.classList.toggle('bi-folder2');
                        icon.classList.toggle('bi-folder2-open');
                    }
                }
            });
        });
    </script>
</div>