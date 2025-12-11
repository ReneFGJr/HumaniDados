<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Indicadores de Produção Artística</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f5f7fa;
        }

        .card-indicador {
            border-left: 6px solid #0d6efd;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }
    </style>

</head>

<body>

    <div class="container my-4">

        <h2 class="mb-4 text-center">Indicadores da Produção Artística</h2>

        <!-- ================= INDICADORES ================= -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-indicador p-3">
                    <h5>Total Geral</h5>
                    <span class="fs-3 fw-bold" id="indTotal"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-indicador p-3">
                    <h5>Naturezas Distintas</h5>
                    <span class="fs-3 fw-bold" id="indNaturezas"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-indicador p-3">
                    <h5>Atividades Distintas</h5>
                    <span class="fs-3 fw-bold" id="indAtividades"></span>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- =============== GRÁFICO: NATUREZA =============== -->
            <div class="mb-4 p-4" style="height: 100px;">
                <h5 class="text-center">Totais por Natureza</h5>
                <canvas id="chartNatureza" height="120"></canvas>
            </div>

            <!-- =============== GRÁFICO: ATIVIDADES =============== -->
            <div class="col-md-6">
                <h5 class="text-center">Totais por Atividade</h5>
                <canvas id="chartAtividade" height="120"></canvas>
            </div>

            <!-- =============== TABELA ================= -->
            <div class="col-md-6">
                <h5 class="mb-3">Tabela Geral</h5>
                <table class="table table-striped small">
                    <thead class="table-dark">
                        <tr>
                            <th>Natureza</th>
                            <th>Atividade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // ====================== DADOS PHP → JS ======================
        const dados = <?php echo json_encode($array); ?>;

        // ====================== PROCESSAMENTO ======================
        let totalGeral = 0;
        let naturezaMap = {};
        let atividadeMap = {};

        dados.forEach(d => {
            totalGeral += parseInt(d.total);

            // Natureza
            naturezaMap[d.natureza] = (naturezaMap[d.natureza] || 0) + parseInt(d.total);

            // Atividade (vazias substituídas)
            const atv = d.atividade && d.atividade.trim() !== "" ? d.atividade : "Não Informado";
            atividadeMap[atv] = (atividadeMap[atv] || 0) + parseInt(d.total);
        });

        // ====================== SETANDO INDICADORES ======================
        document.getElementById("indTotal").innerHTML = totalGeral;
        document.getElementById("indNaturezas").innerHTML = Object.keys(naturezaMap).length;
        document.getElementById("indAtividades").innerHTML = Object.keys(atividadeMap).length;

        // ====================== TABELA ======================
        let tbody = "";
        dados.forEach(d => {
            tbody += `
        <tr>
            <td>${d.natureza}</td>
            <td>${d.atividade || "Não Informado"}</td>
            <td>${d.total}</td>
        </tr>`;
        });
        document.getElementById("tableBody").innerHTML = tbody;

        // ====================== GRÁFICO 1: NATUREZAS ======================
        new Chart(document.getElementById("chartNatureza"), {
            type: "bar",
            data: {
                labels: Object.keys(naturezaMap),
                datasets: [{
                    label: "Total",
                    data: Object.values(naturezaMap),
                    backgroundColor: "rgba(13,110,253,0.6)",
                    borderColor: "#0d6efd",
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // ====================== GRÁFICO 2: ATIVIDADES ======================
        new Chart(document.getElementById("chartAtividade"), {
            type: "pie",
            data: {
                labels: Object.keys(atividadeMap),
                datasets: [{
                    data: Object.values(atividadeMap)
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>

</html>