    <style>
        body {
            background: #111;
            color: #eee;
            font-family: monospace;
            padding: 20px;
        }

        #output {
            background: #000;
            padding: 20px;
            border-radius: 8px;
            min-height: 300px;
            white-space: pre-line;
            font-size: 15px;
            line-height: 1.4em;
            overflow-y: auto;
            height: 70vh;
        }
    </style>
</head>

<body>

    <h2>Painel de Harvesting Lattes</h2>

    <div id="output">Aguardando início...</div>

    <script>
        // Carrega streaming do processamento
        const source = new EventSource("<?= base_url('lattes/run-harvesting') ?>");

        source.onmessage = function(event) {
            document.getElementById("output").innerHTML = event.data;
        };

        source.onerror = function() {
            console.log("Conexão encerrada.");
            source.close();
        };
    </script>
