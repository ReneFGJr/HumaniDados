(function () {
  const root = document.getElementById("xv-tree");
  const tpl = document.getElementById("tpl-node");

  function renderProps(node) {
    const rows = [];

    const push = (k, v) =>
      v != null &&
      v !== "" &&
      rows.push(`<tr><td>${k}</td><td>${escapeHtml(String(v))}</td></tr>`);

    push("Kind", node.kind);
    push("Name", node.name);
    if (node.type) push("Type", node.type);
    if (node.base) push("Base", node.base);
    if (node.minOccurs) push("minOccurs", node.minOccurs);
    if (node.maxOccurs) push("maxOccurs", node.maxOccurs);
    if (node.facets && node.facets.length)
      push("Facets", node.facets.join(", "));
    if (node.documentation) push("Doc", node.documentation);

    if (node.attributes && node.attributes.length) {
      const atts = node.attributes
        .map(
          (a) =>
            `${a.name} : ${a.type || "—"} (${a.use || "optional"})` +
            (a.default ? ` = ${a.default}` : "")
        )
        .join("<br>");
      push("Attributes", atts);
    }
    return `<table>${rows.join("")}</table>`;
  }

  function renderNode(node) {
    const li = tpl.content.firstElementChild.cloneNode(true);
    li.querySelector(".xv-name").textContent = node.name || "(anonymous)";
    li.querySelector(".xv-badge").textContent =
      node.kind === "element" ? node.type || "complex" : node.kind;
    li.querySelector(".xv-props").innerHTML = renderProps(node);

    const childrenUl = li.querySelector(".xv-children");
    if (node.children && node.children.length) {
      node.children.forEach((ch) => childrenUl.appendChild(renderNode(ch)));
    } else {
      li.querySelector(".xv-toggle").style.visibility = "hidden";
    }

    li.querySelector(".xv-row").addEventListener("click", () =>
      li.classList.toggle("is-open")
    );
    return li;
  }

  function escapeHtml(s) {
    return s.replace(
      /[&<>"']/g,
      (m) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        }[m])
    );
  }

  function mount(tree) {
    const ul = document.createElement("ul");
    ul.className = "xv-root";
    tree.forEach((n) => ul.appendChild(renderNode(n)));
    root.innerHTML = "";
    root.appendChild(ul);
  }

  function expandAll(on) {
    root
      .querySelectorAll(".xv-node")
      .forEach((n) => n.classList.toggle("is-open", on));
  }

  function search(q) {
    q = q.trim().toLowerCase();
    const names = root.querySelectorAll(".xv-name");
    names.forEach((el) => {
      const text = el.textContent;
      el.innerHTML = text;
      if (!q) return;
      const idx = text.toLowerCase().indexOf(q);
      if (idx >= 0) {
        const before = text.slice(0, idx);
        const match = text.slice(idx, idx + q.length);
        const after = text.slice(idx + q.length);
        el.innerHTML = `${before}<mark>${match}</mark>${after}`;

        // abre ancestrais
        let li = el.closest(".xv-node");
        while (li) {
          li.classList.add("is-open");
          li = li.parentElement.closest(".xv-node");
        }
      }
    });
  }

  // Inicializa
  mount(window.__XSD_TREE__ || []);

  // Controles
  document
    .getElementById("xv-expand")
    ?.addEventListener("click", () => expandAll(true));
  document
    .getElementById("xv-collapse")
    ?.addEventListener("click", () => expandAll(false));
  document
    .getElementById("xv-search")
    ?.addEventListener("input", (e) => search(e.target.value));
})();
