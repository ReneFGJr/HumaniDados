<div class="container mt-5">
    <h2 class="mb-4">📊 Produção Científica por Tipo e Número de Autores</h2>
    <canvas id="graficoCoautoriaTipo"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (function() {

        const dados = <?= json_encode($producaoCoautoria['cientifica']) ?>;

        // ===============================
        // Descobrir todos os números de autores existentes
        // ===============================
        let todosAutores = new Set();

        Object.keys(dados).forEach(tipo => {
            Object.keys(dados[tipo]).forEach(numAutores => {
                todosAutores.add(numAutores);
            });
        });

        const autoresOrdenados = Array.from(todosAutores).sort((a, b) => a - b);

        // ===============================
        // Criar datasets (um para cada tipo)
        // ===============================
        const cores = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(153, 102, 255, 0.8)'
        ];

        let i = 0;

        const datasets = Object.keys(dados).map(tipo => {

            const valores = autoresOrdenados.map(num => {
                return dados[tipo][num] ?? 0;
            });

            return {
                label: tipo.charAt(0).toUpperCase() + tipo.slice(1),
                data: valores,
                backgroundColor: cores[i++ % cores.length],
                borderWidth: 1
            };
        });

        // ===============================
        // Criar gráfico
        // ===============================
        const ctx = document.getElementById('graficoCoautoriaTipo');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: autoresOrdenados,
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
                        text: 'Distribuição da Produção Científica por Tipo e Coautoria'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Número de Autores'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade de Produções'
                        }
                    }
                }
            }
        });

    })();
</script>