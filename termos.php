<?php
declare(strict_types=1);
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR" data-lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso | Terms of Use — Palavreado 4U.IA.BR</title>
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
            <div class="border-b border-white/10 pb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold mb-3">
                    <i class="fas fa-file-contract"></i>
                    <span data-i18n-pt>Termos de Uso & Licença</span>
                    <span data-i18n-en>Terms of Service & License</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-pt>Termos de Uso</h2>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-en>Terms of Use</h2>
                <p class="text-sm text-gray-400 mt-2" data-i18n-pt>Licença de uso livre e gratuita para fins pessoais, educacionais e comerciais.</p>
                <p class="text-sm text-gray-400 mt-2" data-i18n-en>Free and permissive license for personal, educational, and commercial purposes.</p>
            </div>

            <!-- Item 1 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">1</span>
                    <span data-i18n-pt>Finalidade e Natureza do Serviço</span>
                    <span data-i18n-en>Scope and Purpose</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    O <strong>Palavreado</strong> é uma plataforma aberta de apoio à escrita, pesquisa lexical, jogos de palavras e análise linguística. O software é fornecido "no estado em que se encontra" (<em>as is</em>), de forma 100% gratuita, sem exigência de assinatura, mensalidade ou criação forçada de contas.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    <strong>Palavreado</strong> is an open-access lexical research, wordplay, and linguistic analysis suite. It is provided "as is", free of charge, with no subscriptions, paywalls, or mandatory user accounts.
                </p>
            </div>

            <!-- Item 2 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">2</span>
                    <span data-i18n-pt>Uso Permitido & Licença de Código</span>
                    <span data-i18n-en>Permitted Use & License</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    Você tem total liberdade para utilizar os resultados de buscas, rimas, análises de texto e ferramentas para fins acadêmicos, poéticos, literários, educacionais e comerciais sem restrições de royalties. O código e os recursos integram a iniciativa de software soberano da organização <strong>4U Labs</strong>.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    You are free to utilize the word queries, rhymes, text analysis, and solver results for academic, literary, educational, and commercial projects without licensing fees. The codebase is part of the sovereign open-source initiative of <strong>4U Labs</strong>.
                </p>
            </div>

            <!-- Item 3 -->
            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-sm">3</span>
                    <span data-i18n-pt>Isenção de Responsabilidade</span>
                    <span data-i18n-en>Disclaimer of Liability</span>
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-pt>
                    Embora nossos bancos léxicos sejam construídos com rigor e baseados em fontes consagradas da língua portuguesa, o usuário reconhece que variações dialetais, reformas ortográficas e neologismos podem conter divergências pontuais. Os desenvolvedores não se responsabilizam por eventuais erros tipográficos ou perdas decorrentes de decisões baseadas nas ferramentas.
                </p>
                <p class="text-sm text-gray-300 leading-relaxed" data-i18n-en>
                    While our lexicons are curated based on standard grammar and orthographic agreements, regional variations or neologisms may exhibit minor discrepancies. The developers accept no liability for any reliance placed on linguistic outputs.
                </p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="glass rounded-2xl p-6 text-center text-xs text-gray-400 space-y-3">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="termos.php" class="text-blue-400 font-bold" data-i18n-pt>Termos de Uso</a>
                <a href="termos.php" class="text-blue-400 font-bold" data-i18n-en>Terms of Service</a>
                <span>•</span>
                <a href="privacidade.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Privacidade</a>
                <a href="privacidade.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Privacy Policy</a>
                <span>•</span>
                <a href="suporte.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Suporte & Ajuda</a>
                <a href="suporte.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Support & FAQ</a>
            </div>
            <p>© <?= $currentYear ?> 4U.IA.BR • Palavreado. Todos os direitos reservados.</p>
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
