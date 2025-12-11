    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        body {
            background: #111;
            color: #eee;
            font-family: Arial, sans-serif;
        }
        h2 { text-align: center; color: #fff; margin-top: 20px; }
        #sankey_chart {
            width: 1024px;
            height: 700px;
            margin-top: 30px;
        }
    </style>

<h2>Fluxo da Produção Artística / Cultural</h2>

<div id="sankey_chart" ></div>

<script>
    google.charts.load('current', {packages:['sankey']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Origem');
        data.addColumn('string', 'Destino');
        data.addColumn('number', 'Peso');

        // ============================================
        // INSERINDO OS DADOS DO PHP PARA O JS
        // ============================================
        var rows = [
            <?php foreach ($producao_artistica as $item):
                $tipo = $item["tipo"] ?: "Sem Tipo";
                $natureza = $item["natureza"] ?: "Sem Natureza";
                $evento = $item["tipo_evento"] ?: "Sem Evento";
                $total = (int)$item["total"];
            ?>
                ["<?= $tipo ?>", "<?= $natureza ?>", <?= $total ?>],
                ["<?= $natureza ?>", "<?= $evento ?>", <?= $total ?>],
            <?php endforeach; ?>
        ];

        data.addRows(rows);

        // ============================================
        // OPÇÕES DO GRÁFICO
        // ============================================
        var options = {
            width: "100%",
            height: 700,
            sankey: {
                node: {
                    label: { color: '#fff', fontSize: 14 },
                    nodePadding: 20
                },
                link: {
                    colorMode: 'gradient',
                    colors: ['#4da3ff', '#4ade80', '#ff7070', '#a0aec0']
                }
            },
            backgroundColor: '#111'
        };

        var chart = new google.visualization.Sankey(document.getElementById('sankey_chart'));
        chart.draw(data, options);
    }
</script>

