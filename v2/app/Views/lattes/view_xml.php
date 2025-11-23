<!-- =======================
     ABA 9 - Ver XML
======================== -->
<div class="tab-pane fade" id="xml" role="tabpanel">

        <style>
            .xml-tree {
                font-family: monospace;
                font-size: 0.95rem;
            }

            .node {
                margin-left: 20px;
                cursor: pointer;
            }

            .tag {
                color: #4da6ff;
            }

            .attr {
                color: #ffcc80;
            }

            .value {
                color: #a5d6a7;
            }

            .hidden {
                display: none;
            }

            .caret::before {
                content: "▸ ";
                color: #6fb1ff;
            }

            .caret-down::before {
                content: "▾ ";
            }
        </style>

    </head>

    <body class="container py-4">

        <h3 class="mb-4 text-info">
            <i class="bi bi-diagram-3"></i> Estrutura Completa do XML
        </h3>

        <div class="xml-tree">
            <?= $treeHTML ?>
        </div>

        <script>
            document.querySelectorAll(".caret").forEach(function(item) {
                item.addEventListener("click", function() {
                    this.parentElement.querySelector(".nested").classList.toggle("hidden");
                    this.classList.toggle("caret-down");
                });
            });
        </script>


</div>