<div class="container">
    <div class="row">
        <div class="col-12">
            <style>
                /* ====== Reset e tokens ====== */
                *,
                *::before,
                *::after {
                    box-sizing: border-box;
                }

                html,
                body {
                    height: 100%;
                }

                :root {
                    --bg: #0b0c10;
                    --panel: #11141a;
                    --text: #e6e6e6;
                    --muted: #9aa4b2;
                    --brand: #7c5cff;
                    --brand-2: #00e0e0;
                    --ok: #30d158;
                    --danger: #ff4d4f;
                    --warning: #ffb020;
                    --radius: 16px;
                    --shadow: 0 10px 30px rgba(0, 0, 0, .35);
                }

                @media (prefers-color-scheme: light) {
                    :root {
                        --bg: #f6f7fb;
                        --panel: #ffffff;
                        --text: #242a31;
                        --muted: #5b6876;
                    }
                }



                main {
                    max-width: 1100px;
                    margin: 12px auto 60px;
                    padding: 0 20px;
                }

                .grid {
                    display: grid;
                    gap: 18px;
                    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
                }

                /* ====== Botão base ====== */
                .btn {
                    position: relative;
                    isolation: isolate;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: .65rem;
                    width: 100%;
                    min-height: 64px;
                    padding: 14px 18px;
                    border-radius: var(--radius);
                    border: 1px solid rgba(255, 255, 255, .08);
                    background: var(--panel);
                    color: var(--text);
                    cursor: pointer;
                    user-select: none;
                    -webkit-tap-highlight-color: transparent;
                    transition: transform .18s cubic-bezier(.2, .8, .2, 1), box-shadow .25s ease, border-color .25s ease, background .25s ease;
                    box-shadow: var(--shadow);
                    opacity: 0.7;
                }

                .btn:focus-visible {
                    outline: 3px solid color-mix(in oklab, var(--brand) 60%, transparent);
                    outline-offset: 2px;
                }

                .btn:active {
                    transform: translateY(1px) scale(.995);
                    opacity: 1;
                }

                .btn:hover {
                    transform: translateY(1px) scale(.995);
                    opacity: 1;
                }

                .icon {
                    width: 22px;
                    height: 22px;
                    display: inline-block;
                }

                /* ====== Variantes ====== */

                @keyframes ring {
                    from {
                        transform: scale(.96);
                        opacity: .75
                    }

                    to {
                        transform: scale(1.12);
                        opacity: 0
                    }
                }



                /* 10) Arco‑íris com borda animada */
                .btn--rainbow {
                    background: #fff;
                    border: none;
                }

                .btn--rainbow::before {
                    content: "";
                    position: absolute;
                    inset: -2px;
                    z-index: -1;
                    border-radius: inherit;
                    padding: 2px;
                    background: conic-gradient(from var(--ang, 0deg), #7c5cff, #00e0e0, #30d158, #ffb020, #ff4d4f, #7c5cff);
                    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                    -webkit-mask-composite: xor;
                    mask-composite: exclude;
                    animation: spin 6s linear infinite;
                }

                @keyframes spin {
                    to {
                        --ang: 360deg;
                    }
                }

                /* Ripple (injetado via JS em qualquer .btn com data-ripple) */
                .ripple {
                    position: absolute;
                    border-radius: 50%;
                    transform: translate(-50%, -50%) scale(0);
                    opacity: .7;
                    pointer-events: none;
                    width: 12px;
                    height: 12px;
                    background: #fff8;
                    filter: blur(1px);
                    animation: rip .7s ease-out forwards;
                }

                @keyframes rip {
                    to {
                        transform: translate(-50%, -50%) scale(16);
                        opacity: 0;
                    }
                }

                /* Desativar animações pesadas para usuários com movimento reduzido */
                @media (prefers-reduced-motion: reduce) {
                    * {
                        animation-duration: .001ms !important;
                        animation-iteration-count: 1 !important;
                        transition-duration: .001ms !important;
                    }
                }
            </style>
            </head>

            <body>
                <header>
                    <h1>Botões Gráficos Animados</h1>
                    <p>Passe o mouse e clique para ver os efeitos. Código em HTML, CSS e JavaScript puro (sem bibliotecas).</p>
                </header>

                <main>
                    <div class="grid">

                        <button class="btn btn--rainbow" data-ripple>
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M3 11h18v2H3z" />
                            </svg>
                            Pesquisadores
                        </button>

                        <button class="btn btn--rainbow" data-ripple>
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M3 11h18v2H3z" />
                            </svg>
                            Produções
                        </button>

                        <button class="btn btn--rainbow" data-ripple>
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M3 11h18v2H3z" />
                            </svg>
                            Produções Técnicas
                        </button>

                        <button class="btn btn--rainbow" data-ripple>
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M3 11h18v2H3z" />
                            </svg>
                            Produções Artistica
                        </button>
                    </div>
                </main>

                <script>
                    // Utilidades
                    const rand = (min, max) => Math.random() * (max - min) + min;

                    // ===== Toggle Play/Pause =====
                    document.querySelectorAll('[data-toggle]').forEach(btn => {
                        const path = btn.querySelector('.shape');
                        btn.addEventListener('click', () => {
                            const isOn = btn.getAttribute('aria-pressed') === 'true';
                            btn.setAttribute('aria-pressed', String(!isOn));
                            // Altera o path entre Play e Pause
                            if (isOn) {
                                path.setAttribute('d', 'M8 5v14l11-7-11-7Z'); // Play
                            } else {
                                path.setAttribute('d', 'M6 5h4v14H6V5Zm8 0h4v14h-4V5Z'); // Pause
                            }
                        });
                    });
                </script>
        </div>
    </div>
</div>