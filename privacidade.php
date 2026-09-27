<?php
declare(strict_types=1);
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade | Privacy Policy — Palavreado 4U.IA.BR</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="apple-touch-icon" href="icon-192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); min-height: 100vh; color: #f1f5f9; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); }
        [data-lang="en"] [data-i18n-pt], [data-lang="pt"] [data-i18n-en] { display: none !important; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Nav -->
        <header class="flex items-center justify-between glass rounded-2xl px-6 py-4">
            <a href="index.php" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg font-bold text-xl">
                    P
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight">Palavr<span class="text-blue-400">eado</span></h1>
                    <p class="text-[11px] text-gray-400">4U.IA.BR Ecosystem</p>
                </div>
            </a>
            
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center p-0.5 rounded-xl bg-gray-900/90 border border-gray-700/80 shadow-inner text-xs font-semibold">
                    <button id="btnLangPt" onclick="setLang('pt')" class="px-2.5 py-1 rounded-lg transition-all text-white bg-blue-600">🇧🇷 PT</button>
                    <button id="btnLangEn" onclick="setLang('en')" class="px-2.5 py-1 rounded-lg transition-all text-gray-400 hover:text-white">🇺🇸 EN</button>
                </div>
                <a href="index.php" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-sm font-semibold transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span data-i18n-pt>Voltar</span>
                    <span data-i18n-en>Back</span>
                </a>
            </div>
        </header>

        <!-- Content Card -->
        <main class="glass-card rounded-3xl p-6 md:p-10 space-y-8">
            <!-- Header Text -->
            <div class="border-b border-white/10 pb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold mb-3">
                    <i class="fas fa-shield-alt"></i>
                    <span data-i18n-pt>Privacidade & LGPD / GDPR</span>
                    <span data-i18n-en>Privacy & Zero-Retention</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-pt>Política de Privacidade</h2>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-en>Privacy Policy</h2>
                <p class="text-sm text-gray-400 mt-2" data-i18n-pt>Última atualização: <?= $currentYear ?> • Plataforma Soberana e 100% Local</p>
                <p class="text-sm text-gray-400 mt-2" data-i18n-en>Last updated: <?= $currentYear ?> • Sovereign & 100% Client-Side Platform</p>
            </div>

            <!-- Section 1 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">1</span>
                    <span data-i18n-pt>Arquitetura 100% Client-Side (Sem Servidor)</span>
                    <span data-i18n-en>100% Client-Side Architecture (Serverless)</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    O <strong>Palavreado</strong> foi projetado segundo o princípio de <em>Privacy by Design</em>. Todas as pesquisas de palavras, rimas, cálculos de anagramas, estatísticas e textos analisados são processados <strong>exclusivamente na memória do navegador do seu dispositivo</strong>. Nenhuma consulta, texto digitado ou termo pesquisado é transmitido, armazenado ou monitorado em servidores remotos.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    <strong>Palavreado</strong> was engineered around the <em>Privacy by Design</em> doctrine. All dictionary lookups, rhyme searches, anagram solving, and text analysis take place <strong>strictly inside your browser memory</strong>. No queries or typed texts are ever uploaded, recorded, or monitored on remote servers.
                </p>
            </div>

            <!-- Section 2 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">2</span>
                    <span data-i18n-pt>Ausência de Rastreamento & Cookies Invasivos</span>
                    <span data-i18n-en>No Tracking & No Invasive Cookies</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    Não utilizamos cookies de terceiros, pixels de rastreamento, scripts de publicidade direcionada ou ferramentas de perfil comportamental. As únicas informações salvas no seu navegador residem no armazenamento local (<code class="text-blue-300 bg-white/5 px-1.5 py-0.5 rounded">localStorage</code>) para memorizar suas preferências cosméticas (como idioma e tema) e cache do dicionário offline via Service Worker.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    We do not deploy third-party cookies, tracking pixels, retargeting scripts, or behavioral profiling tools. The only data saved in your browser resides in <code class="text-blue-300 bg-white/5 px-1.5 py-0.5 rounded">localStorage</code> and Service Worker Cache to persist your interface preferences and offline word cache.
                </p>
            </div>

            <!-- Section 3 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-sm">3</span>
                    <span data-i18n-pt>Conformidade Rigorosa com LGPD & GDPR</span>
                    <span data-i18n-en>Full Compliance with LGPD & GDPR</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    Por não coletar nem processar dados pessoais identificáveis em nossos servidores, a plataforma cumpre por padrão todas as exigências da Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018) e do Regulamento Geral sobre a Proteção de Dados da União Europeia (GDPR).
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    By refusing to collect, store, or process identifiable user information on our infrastructure, the service inherently meets and exceeds all global privacy mandates, including Brazilian LGPD and European GDPR regulations.
                </p>
            </div>

            <!-- Section 4 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">4</span>
                    <span data-i18n-pt>Contato e Encarregado de Dados (DPO)</span>
                    <span data-i18n-en>Contact & Inquiries</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    Para dúvidas sobre esta política ou sugestões técnicas, entre em contato através de nossa <a href="suporte.php" class="text-blue-400 underline hover:text-blue-300">Central de Suporte</a> ou pelo e-mail oficial: <code class="text-blue-300">contato@4u.ia.br</code>.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    For questions regarding this policy or feedback, please reach out via our <a href="suporte.php" class="text-blue-400 underline hover:text-blue-300">Support Center</a> or directly at <code class="text-blue-300">contato@4u.ia.br</code>.
                </p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="glass rounded-2xl p-6 text-center text-xs text-gray-400 space-y-3">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="termos.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Termos de Uso</a>
                <a href="termos.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Terms of Service</a>
                <span>•</span>
                <a href="privacidade.php" class="text-blue-400 font-bold" data-i18n-pt>Privacidade</a>
                <a href="privacidade.php" class="text-blue-400 font-bold" data-i18n-en>Privacy Policy</a>
                <span>•</span>
                <a href="suporte.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Suporte & Ajuda</a>
                <a href="suporte.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Support & FAQ</a>
            </div>
            <p>© <?= $currentYear ?> 4U.IA.BR • Palavreado. Desenvolvido com soberania e privacidade.</p>
        </footer>
    </div>

    <script>
        function setLang(l) {
            document.documentElement.setAttribute('data-lang', l);
            localStorage.setItem('palavreado_lang', l);
            document.getElementById('btnLangPt').className = l === 'pt' ? 'px-2.5 py-1 rounded-lg transition-all text-white bg-blue-600' : 'px-2.5 py-1 rounded-lg transition-all text-gray-400 hover:text-white';
            document.getElementById('btnLangEn').className = l === 'en' ? 'px-2.5 py-1 rounded-lg transition-all text-white bg-blue-600' : 'px-2.5 py-1 rounded-lg transition-all text-gray-400 hover:text-white';
        }
        const savedLang = localStorage.getItem('palavreado_lang') || 'pt';
        setLang(savedLang);
    </script>
</body>
</html>
