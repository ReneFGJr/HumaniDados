<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Árvore do Sitemap - PROPLAN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        h1 {
            color: #4b2e83;
        }

        ul {
            list-style-type: none;
            padding-left: 20px;
        }

        li {
            margin: 5px 0;
            cursor: pointer;
        }

        li span {
            padding: 4px 6px;
            border-radius: 4px;
        }

        li span:hover {
            background-color: #e0e6f0;
        }

        .collapsed>ul {
            display: none;
        }

        .folder::before {
            content: "📁 ";
        }

        .file::before {
            content: "📄 ";
        }
    </style>
</head>

<body>

    <h1>Árvore do Sitemap - PROPLAN</h1>
    <div id="tree"></div>

    <script>
        async function carregarSitemap() {
            const response = await fetch("proxy-sitemap.php");
            const text = await response.text();
            const parser = new DOMParser();
            const xml = parser.parseFromString(text, "application/xml");

            const urls = [...xml.getElementsByTagName("loc")].map(e => e.textContent);

            const treeData = {};

            urls.forEach(url => {
                const path = new URL(url).pathname.split("/").filter(Boolean);
                let current = treeData;

                path.forEach(part => {
                    if (!current[part]) current[part] = {};
                    current = current[part];
                });
            });

            return treeData;
        }

        function criarLista(obj) {
            const ul = document.createElement("ul");

            Object.keys(obj).forEach(key => {
                const li = document.createElement("li");
                const span = document.createElement("span");

                span.textContent = key;
                li.appendChild(span);

                if (Object.keys(obj[key]).length > 0) {
                    li.classList.add("folder", "collapsed");
                    const child = criarLista(obj[key]);
                    li.appendChild(child);

                    span.onclick = () => {
                        li.classList.toggle("collapsed");
                    };
                } else {
                    li.classList.add("file");
                }

                ul.appendChild(li);
            });

            return ul;
        }

        carregarSitemap().then(data => {
            const tree = criarLista(data);
            document.getElementById("tree").appendChild(tree);
        });
    </script>

</body>

</html>