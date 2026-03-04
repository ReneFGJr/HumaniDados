<div class="container mt-5">
    <h2 class="mb-4">📊 Produção Científica por Ano e Número de Autores (Percentualmente %)</h2>
    <canvas id="graficoCoautoriaPercentual"></canvas>
</div>



<script>
    (function() {

        const dados = <?= json_encode($producaoCoautoriaPercentual['cientifica']) ?>;

        // ===============================
        // Extrair e ordenar anos corretamente (numérico)
        // ===============================
        const anos = Object.keys(dados)
            .map(ano => parseInt(ano))
            .sort((a, b) => a - b);

        // ===============================
        // Descobrir todos os tipos de autoria existentes
        // ===============================
        let tiposAutores = new Set();

        anos.forEach(ano => {
            Object.keys(dados[ano]).forEach(tipo => {
                tiposAutores.add(tipo);
            });
        });

        tiposAutores = Array.from(tiposAutores).sort((a, b) => a - b);

        // ===============================
        // Gerar datasets dinamicamente
        // ===============================
        const datasets = tiposAutores.map((tipo) => {

            const valores = anos.map(ano => {
                return dados[ano][tipo] ?? 0;
            });

            return {
                label: tipo == 1 ? '1 autor' : tipo + ' autores',
                data: valores,
                borderWidth: 1
            };
        });

        // ===============================
        // Criar gráfico
        // ===============================
        const ctx = document.getElementById('graficoCoautoriaPercentual');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: anos,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Distribuição da Produção Científica por Coautoria Percentual'
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        stacked: true,
                        ticks: {
                            autoSkip: false, // força mostrar todos os anos
                            maxRotation: 90,
                            minRotation: 45
                        },
                        title: {
                            display: true,
                            text: 'Ano'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade de Trabalhos'
                        }
                    }
                }
            }
        });

    })();
</script>