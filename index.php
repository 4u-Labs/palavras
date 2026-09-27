<?php
header('Cache-Control: no-cache, must-revalidate');
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palavreado — Dicionário Inteligente, Rimas & Jogos de Palavras | 4U.IA.BR</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="apple-touch-icon" href="icon-192.png">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e40af">
    <meta name="description" content="Palavreado é um dicionário inteligente completo e 100% offline da língua portuguesa, com busca por letras, rimas, analisador léxico, estatísticas e curiosidades.">
    <meta property="og:title" content="Palavreado — Dicionário Inteligente & Rimas">
    <meta property="og:description" content="Dicionário de português offline com busca avançada por letras, rimas e análise linguística.">
    <meta property="og:image" content="icon-512.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .word-tag {
            transition: all 0.2s ease;
        }
        .word-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        .letter-btn.active {
            background: #3b82f6;
            color: white;
        }
        .length-btn.active {
            background: #10b981;
            color: white;
        }
        .accent-btn.active {
            background: #8b5cf6;
            color: white;
        }
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Tabs */
        .main-tab {
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        .main-tab.active {
            border-bottom-color: #3b82f6;
            color: #3b82f6;
        }
        .main-tab:hover {
            color: #3b82f6;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        
        /* Curiosidades Styles */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: pulse-glow 2s infinite;
        }
        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(245, 158, 11, 0.6);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4); }
            50% { box-shadow: 0 4px 30px rgba(245, 158, 11, 0.7); }
        }
        
        .curiosity-modal, .word-modal, .rhyme-modal, .definition-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 1001;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
        }
        .curiosity-modal.active, .word-modal.active, .rhyme-modal.active, .definition-modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-card {
            background: white;
            border-radius: 24px;
            max-width: 600px;
            width: 100%;
            padding: 30px;
            position: relative;
            animation: slideUp 0.4s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .curiosity-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .curiosity-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .curiosity-item.expanded .curiosity-content {
            max-height: 500px;
            padding-top: 15px;
        }
        .curiosity-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .curiosity-item .chevron {
            transition: transform 0.3s ease;
        }
        .curiosity-item.expanded .chevron {
            transform: rotate(180deg);
        }
        
        .category-tab {
            transition: all 0.3s ease;
        }
        .category-tab.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .curiosity-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            border: 2px solid #fde68a;
        }
        
        .stat-card .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #d97706;
        }
        
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: #92400e;
        }
        
        /* Word Syllable Styles */
        .word-tag {
            position: relative;
        }
        
        .word-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 100;
            pointer-events: none;
            margin-bottom: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .word-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #334155;
        }
        
        .word-tag:hover .word-tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        .syllable-separator {
            color: #60a5fa;
            font-weight: bold;
            margin: 0 1px;
        }
        
        .syllable-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 2px solid #93c5fd;
            border-radius: 10px;
            padding: 8px 14px;
            margin: 4px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e40af;
            transition: all 0.2s ease;
        }
        
        .syllable-box:hover {
            transform: scale(1.1);
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }
        
        .syllable-box.tonic {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: #f59e0b;
            color: #92400e;
        }
        
        .classification-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
        }
        
        .classification-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }
        
        .classification-item .label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .classification-item .value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 4px;
        }
        
        /* Stats Dashboard */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        
        .dashboard-stat {
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
        }
        
        .dashboard-stat .number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Rhyme tag */
        .rhyme-tag {
            background: linear-gradient(135deg, #fdf4ff, #fae8ff);
            border: 1px solid #e879f9;
            color: #a21caf;
        }
        
        /* Text analysis */
        .analysis-result {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 16px;
        }
        
        /* Etymology badge */
        .etymology-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .etymology-badge.latin { background: #dbeafe; color: #1e40af; }
        .etymology-badge.tupi { background: #d1fae5; color: #065f46; }
        .etymology-badge.arabic { background: #fef3c7; color: #92400e; }
        .etymology-badge.african { background: #fce7f3; color: #9d174d; }
        .etymology-badge.greek { background: #e0e7ff; color: #3730a3; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
        <!-- Header -->
    <header class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="container mx-auto px-4 py-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white text-blue-700 flex items-center justify-center font-black text-2xl shadow-lg border border-blue-200">
                        P
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl md:text-3xl font-black tracking-tight">Palavreado</h1>
                            <span class="px-2 py-0.5 rounded-full bg-blue-500/30 border border-blue-300/40 text-[10px] font-bold uppercase tracking-wider text-blue-200">v2.0 PWA</span>
                        </div>
                        <p class="text-xs text-blue-200 mt-0.5">Dicionário Inteligente, Rimas & Análise Linguística 100% Offline</p>
                    </div>
                </div>

                <!-- Action Controls -->
                <div class="flex items-center flex-wrap justify-center gap-2">
                    <button id="btnInstallApp" style="display: none;" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs shadow-lg transition-all flex items-center gap-1.5 animate-pulse">
                        <i class="fas fa-download"></i> Instalar App
                    </button>
                    <a href="suporte.php" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold text-white transition-all flex items-center gap-1.5">
                        <i class="fas fa-headset"></i> Ajuda & FAQ
                    </a>
                    <a href="privacidade.php" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold text-white transition-all flex items-center gap-1.5">
                        <i class="fas fa-shield-alt"></i> Privacidade
                    </a>
                    <a href="https://github.com/4u-Labs/palavras" target="_blank" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all" title="Código Aberto no GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Tabs -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex gap-1 overflow-x-auto">
                <button class="main-tab active px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="search">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
                <button class="main-tab px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="rhymes">
                    <i class="fas fa-music mr-2"></i>Rimas
                </button>
                <button class="main-tab px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="advanced">
                    <i class="fas fa-sliders-h mr-2"></i>Avançado
                </button>
                <button class="main-tab px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="statistics">
                    <i class="fas fa-chart-bar mr-2"></i>Estatísticas
                </button>
                <button class="main-tab px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="analyzer">
                    <i class="fas fa-file-alt mr-2"></i>Analisador
                </button>
                <button class="main-tab px-6 py-4 font-medium text-gray-600 whitespace-nowrap" data-tab="curiosities">
                    <i class="fas fa-lightbulb mr-2"></i>Curiosidades
                </button>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Status do Dicionário -->
        <div id="dictionaryStatus" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <div class="loading-spinner"></div>
            <div>
                <p class="font-medium text-blue-800">Carregando dicionário...</p>
                <p class="text-sm text-blue-600">Aguarde enquanto baixamos as palavras</p>
            </div>
        </div>

        <!-- ==================== TAB: SEARCH ==================== -->
        <div id="tab-search" class="tab-content active">
            <!-- Filtros Principais -->
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-blue-500"></i>
                    Tipo de Busca
                </h2>
                <div class="flex flex-wrap gap-3 mb-6">
                    <button class="filter-btn active px-5 py-3 rounded-xl font-medium border-2 border-blue-500 transition-all" data-filter="starts">
                        <i class="fas fa-arrow-right mr-2"></i>Começam com
                    </button>
                    <button class="filter-btn px-5 py-3 rounded-xl font-medium border-2 border-gray-300 text-gray-600 hover:border-blue-500 transition-all" data-filter="ends">
                        <i class="fas fa-arrow-left mr-2"></i>Terminam com
                    </button>
                    <button class="filter-btn px-5 py-3 rounded-xl font-medium border-2 border-gray-300 text-gray-600 hover:border-blue-500 transition-all" data-filter="contains">
                        <i class="fas fa-search mr-2"></i>Contêm
                    </button>
                </div>

                <!-- Input de busca -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2" id="searchLabel">Palavras que começam com:</label>
                    <div class="flex gap-3">
                        <input type="text" id="searchInput" placeholder="Digite letras..." 
                            class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg">
                        <button id="searchBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                    </div>
                </div>

                <!-- Alfabeto -->
                <div>
                    <h3 class="text-gray-700 font-medium mb-3">Ou escolha uma letra:</h3>
                    <div class="flex flex-wrap gap-2" id="alphabetContainer"></div>
                </div>
            </section>

            <!-- Filtro por Tamanho -->
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-ruler text-green-500"></i>
                    Filtrar por Tamanho
                </h2>
                <div class="flex flex-wrap gap-2" id="lengthContainer"></div>
            </section>

            <!-- Filtro por Acentuação -->
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-volume-up text-purple-500"></i>
                    Filtrar por Acentuação
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button class="accent-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-purple-500 transition-all" data-accent="all">
                        Todas
                    </button>
                    <button class="accent-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-purple-500 transition-all" data-accent="oxitona">
                        🔴 Oxítonas
                    </button>
                    <button class="accent-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-purple-500 transition-all" data-accent="paroxitona">
                        🟡 Paroxítonas
                    </button>
                    <button class="accent-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-purple-500 transition-all" data-accent="proparoxitona">
                        🟢 Proparoxítonas
                    </button>
                </div>
            </section>

            <!-- Resultados -->
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-list text-purple-500"></i>
                        Resultados
                    </h2>
                    <span id="resultCount" class="bg-blue-100 text-blue-800 px-4 py-1 rounded-full font-medium">0 palavras</span>
                </div>
                
                <div id="syllableTip" class="hidden mb-4 p-3 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💡</span>
                        <span class="text-purple-700">
                            <strong>Dica:</strong> Passe o mouse sobre uma palavra para ver a separação silábica, ou clique para ver detalhes completos!
                        </span>
                    </div>
                </div>
                
                <div id="resultsContainer" class="flex flex-wrap gap-3">
                    <p class="text-gray-500 italic">Carregue o dicionário e selecione um filtro para ver as palavras...</p>
                </div>

                <div id="pagination" class="flex justify-center gap-2 mt-6 hidden">
                    <button id="prevPage" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-all">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </button>
                    <span id="pageInfo" class="px-4 py-2 text-gray-600"></span>
                    <button id="nextPage" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-all">
                        Próxima <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>
        </div>

        <!-- ==================== TAB: RHYMES ==================== -->
        <div id="tab-rhymes" class="tab-content">
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-music text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Encontrar Rimas</h2>
                        <p class="text-gray-500 text-sm">Descubra palavras que rimam para poesia e música</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Digite uma palavra:</label>
                    <div class="flex gap-3">
                        <input type="text" id="rhymeInput" placeholder="Ex: amor, coração, cantar..." 
                            class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-pink-500 focus:outline-none text-lg">
                        <button id="rhymeBtn" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all">
                            <i class="fas fa-search mr-2"></i>Buscar Rimas
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tipo de rima:</label>
                    <div class="flex flex-wrap gap-2">
                        <button class="rhyme-type-btn active px-4 py-2 rounded-lg border-2 border-pink-300 bg-pink-50 text-pink-700 font-medium" data-type="perfect">
                            Rima Perfeita (última sílaba)
                        </button>
                        <button class="rhyme-type-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-600 font-medium hover:border-pink-300" data-type="rich">
                            Rima Rica (2 sílabas)
                        </button>
                        <button class="rhyme-type-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-600 font-medium hover:border-pink-300" data-type="assonant">
                            Rima Toante (só vogais)
                        </button>
                    </div>
                </div>

                <div id="rhymeResults" class="mt-6">
                    <p class="text-gray-500 italic">Digite uma palavra para encontrar rimas...</p>
                </div>
            </section>
        </div>

        <!-- ==================== TAB: ADVANCED ==================== -->
        <div id="tab-advanced" class="tab-content">
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-sliders-h text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Filtros Avançados</h2>
                        <p class="text-gray-500 text-sm">Busca avançada com múltiplos critérios</p>
                    </div>
                </div>

                <!-- Conter Letras Específicas -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <h3 class="font-semibold text-blue-800 mb-3">
                        <i class="fas fa-check-circle mr-2"></i>Deve conter estas letras (em qualquer ordem):
                    </h3>
                    <input type="text" id="mustContainInput" placeholder="Ex: xyz (encontra palavras com X, Y e Z)" 
                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:border-blue-500 focus:outline-none">
                </div>

                <!-- Excluir Letras -->
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-200">
                    <h3 class="font-semibold text-red-800 mb-3">
                        <i class="fas fa-times-circle mr-2"></i>Não pode conter estas letras:
                    </h3>
                    <input type="text" id="excludeLettersInput" placeholder="Ex: aeiou (exclui palavras com vogais)" 
                        class="w-full px-4 py-3 border-2 border-red-300 rounded-xl focus:border-red-500 focus:outline-none">
                    <p class="text-sm text-red-600 mt-2">💡 Útil para jogos como Wordle!</p>
                </div>

                <!-- Padrão de busca -->
                <div class="mb-6 p-4 bg-green-50 rounded-xl border border-green-200">
                    <h3 class="font-semibold text-green-800 mb-3">
                        <i class="fas fa-puzzle-piece mr-2"></i>Padrão com curingas:
                    </h3>
                    <input type="text" id="patternInput" placeholder="Ex: c_sa (encontra casa, cosa, cisa...)" 
                        class="w-full px-4 py-3 border-2 border-green-300 rounded-xl focus:border-green-500 focus:outline-none">
                    <p class="text-sm text-green-600 mt-2">Use _ para representar qualquer letra</p>
                </div>

                <button id="advancedSearchBtn" class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-medium transition-all text-lg">
                    <i class="fas fa-search mr-2"></i>Buscar com Filtros Avançados
                </button>

                <div id="advancedResults" class="mt-6">
                    <p class="text-gray-500 italic">Configure os filtros e clique em buscar...</p>
                </div>
            </section>
        </div>

        <!-- ==================== TAB: STATISTICS ==================== -->
        <div id="tab-statistics" class="tab-content">
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-chart-bar text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Dashboard de Estatísticas</h2>
                        <p class="text-gray-500 text-sm">Análise completa do dicionário português</p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="dashboard-stat">
                        <div class="number" id="statTotalWords">-</div>
                        <div class="text-sm text-gray-500">Total de Palavras</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="number" id="statLongestWord">-</div>
                        <div class="text-sm text-gray-500">Maior Palavra</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="number" id="statAvgLength">-</div>
                        <div class="text-sm text-gray-500">Tamanho Médio</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="number" id="statMostCommonStart">-</div>
                        <div class="text-sm text-gray-500">Letra Inicial +Comum</div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="dashboard-card">
                        <h3 class="font-semibold text-gray-800 mb-4">📊 Distribuição por Tamanho</h3>
                        <canvas id="lengthChart" height="250"></canvas>
                    </div>
                    <div class="dashboard-card">
                        <h3 class="font-semibold text-gray-800 mb-4">🔤 Frequência de Letras Iniciais</h3>
                        <canvas id="letterChart" height="250"></canvas>
                    </div>
                </div>

                <div class="dashboard-card mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">📈 Frequência de Letras no Português</h3>
                    <canvas id="frequencyChart" height="200"></canvas>
                </div>

                <!-- Top Words -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="dashboard-card">
                        <h3 class="font-semibold text-gray-800 mb-4">📏 Palavras Mais Longas</h3>
                        <div id="longestWordsList" class="space-y-2"></div>
                    </div>
                    <div class="dashboard-card">
                        <h3 class="font-semibold text-gray-800 mb-4">📝 Palavras Mais Curtas (2 letras)</h3>
                        <div id="shortestWordsList" class="space-y-2"></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- ==================== TAB: ANALYZER ==================== -->
        <div id="tab-analyzer" class="tab-content">
            <section class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Analisador de Texto</h2>
                        <p class="text-gray-500 text-sm">Cole um texto para análise completa</p>
                    </div>
                </div>

                <textarea id="textAnalyzerInput" rows="6" placeholder="Cole ou digite seu texto aqui para análise..."
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-cyan-500 focus:outline-none resize-none mb-4"></textarea>

                <button id="analyzeTextBtn" class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-medium transition-all text-lg mb-6">
                    <i class="fas fa-microscope mr-2"></i>Analisar Texto
                </button>

                <div id="textAnalysisResults" class="hidden">
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="analysis-result text-center">
                            <div class="text-2xl font-bold text-green-700" id="analysisTotalWords">0</div>
                            <div class="text-sm text-green-600">Palavras</div>
                        </div>
                        <div class="analysis-result text-center">
                            <div class="text-2xl font-bold text-green-700" id="analysisUniqueWords">0</div>
                            <div class="text-sm text-green-600">Palavras Únicas</div>
                        </div>
                        <div class="analysis-result text-center">
                            <div class="text-2xl font-bold text-green-700" id="analysisChars">0</div>
                            <div class="text-sm text-green-600">Caracteres</div>
                        </div>
                        <div class="analysis-result text-center">
                            <div class="text-2xl font-bold text-green-700" id="analysisComplexity">-</div>
                            <div class="text-sm text-green-600">Complexidade</div>
                        </div>
                    </div>

                    <!-- Word Frequency -->
                    <div class="dashboard-card mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">📊 Palavras Mais Frequentes</h3>
                        <div id="wordFrequencyList" class="flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Letter Frequency in Text -->
                    <div class="dashboard-card">
                        <h3 class="font-semibold text-gray-800 mb-4">🔤 Frequência de Letras no Texto</h3>
                        <canvas id="textLetterChart" height="150"></canvas>
                    </div>
                </div>
            </section>
        </div>

        <!-- ==================== TAB: CURIOSITIES ==================== -->
        <div id="tab-curiosities" class="tab-content">
            <section class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg p-6 mb-8 border border-amber-200">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-lightbulb text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-amber-800">Curiosidades da Língua Portuguesa</h2>
                            <p class="text-amber-600 text-sm"><span id="curiosityCount">50</span> fatos incríveis sobre nosso idioma!</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button id="randomCuriosityBtn" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-xl font-medium hover:from-amber-600 hover:to-orange-600 transition-all flex items-center gap-2">
                            <i class="fas fa-dice"></i>Aleatória
                        </button>
                        <button id="shuffleCuriositiesBtn" class="bg-white text-amber-600 border-2 border-amber-300 px-4 py-2 rounded-xl font-medium hover:bg-amber-50 transition-all flex items-center gap-2">
                            <i class="fas fa-random"></i>Embaralhar
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="category-tab active px-4 py-2 rounded-xl font-medium border-2 border-amber-300 text-amber-700" data-category="all">Todas</button>
                    <button class="category-tab px-4 py-2 rounded-xl font-medium border-2 border-amber-200 text-amber-600 hover:border-amber-400" data-category="words"><i class="fas fa-spell-check mr-1"></i>Palavras</button>
                    <button class="category-tab px-4 py-2 rounded-xl font-medium border-2 border-amber-200 text-amber-600 hover:border-amber-400" data-category="grammar"><i class="fas fa-pencil-alt mr-1"></i>Gramática</button>
                    <button class="category-tab px-4 py-2 rounded-xl font-medium border-2 border-amber-200 text-amber-600 hover:border-amber-400" data-category="origin"><i class="fas fa-globe mr-1"></i>Origens</button>
                    <button class="category-tab px-4 py-2 rounded-xl font-medium border-2 border-amber-200 text-amber-600 hover:border-amber-400" data-category="history"><i class="fas fa-history mr-1"></i>História</button>
                    <button class="category-tab px-4 py-2 rounded-xl font-medium border-2 border-amber-200 text-amber-600 hover:border-amber-400" data-category="fun"><i class="fas fa-star mr-1"></i>Diversas</button>
                </div>

                <div class="curiosity-stats mb-6" id="curiosityStats">
                    <div class="stat-card"><div class="stat-number" id="statWords">10</div><div class="stat-label">📝 Palavras</div></div>
                    <div class="stat-card"><div class="stat-number" id="statGrammar">10</div><div class="stat-label">✏️ Gramática</div></div>
                    <div class="stat-card"><div class="stat-number" id="statOrigin">10</div><div class="stat-label">🌍 Origens</div></div>
                    <div class="stat-card"><div class="stat-number" id="statHistory">10</div><div class="stat-label">📚 História</div></div>
                    <div class="stat-card"><div class="stat-number" id="statFun">10</div><div class="stat-label">⭐ Diversas</div></div>
                </div>

                <div id="curiositiesContainer" class="grid gap-4 md:grid-cols-2"></div>
            </section>
        </div>
    </main>

    <!-- Footer -->
        <!-- Standard 4U.IA.BR Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 py-12 mt-16">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg">P</div>
                        <span class="text-xl font-bold text-white">Palavreado</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                        Dicionário avançado, solucionador de palavras, buscador de rimas e analisador léxico da língua portuguesa. Software livre, soberano e 100% executado no navegador.
                    </p>
                    <div>
                        <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0070ba] hover:bg-[#005ea6] text-white font-bold text-xs shadow-lg transition-all">
                            <i class="fab fa-paypal text-sm"></i> Apoiar o Projeto (Doar com PayPal)
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs uppercase tracking-wider font-bold text-slate-400 mb-4">Institucional</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="privacidade.php" class="hover:text-blue-400 transition-colors">Privacidade (LGPD/GDPR)</a></li>
                        <li><a href="termos.php" class="hover:text-blue-400 transition-colors">Termos de Uso</a></li>
                        <li><a href="suporte.php" class="hover:text-blue-400 transition-colors">Central de Suporte & FAQ</a></li>
                        <li><a href="https://github.com/4u-Labs/palavras" target="_blank" class="hover:text-blue-400 transition-colors">Repositório GitHub</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs uppercase tracking-wider font-bold text-slate-400 mb-4">Ecossistema 4U</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="https://4u.ia.br/app/office/" target="_blank" class="hover:text-blue-400 transition-colors">OfficeClone Suite</a></li>
                        <li><a href="https://4u.ia.br/app/conversor/" target="_blank" class="hover:text-blue-400 transition-colors">UniConvert Pro 2.0</a></li>
                        <li><a href="https://4u.ia.br/app/fotolaudo/" target="_blank" class="hover:text-blue-400 transition-colors">FotoLaudo Técnico</a></li>
                        <li><a href="https://4u.ia.br" target="_blank" class="hover:text-blue-400 transition-colors">Portal 4U.IA.BR (80+ Apps)</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; <?php echo $currentYear; ?> <strong>4U.IA.BR</strong> por <a href="https://github.com/4u-Labs" target="_blank" class="text-blue-400 hover:underline">4U Labs</a>. Licença Livre e Aberta.</p>
                <p id="footerStats" class="text-slate-400 font-mono"></p>
            </div>
        </div>
    </footer>

    <!-- Floating Button -->
    <button class="floating-btn" id="floatingCuriosityBtn" title="Curiosidade aleatória">
        <i class="fas fa-lightbulb text-2xl"></i>
    </button>

    <!-- Word Detail Modal -->
    <div class="word-modal" id="wordModal">
        <div class="modal-card" style="max-width: 500px;">
            <button class="closeModal absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-all flex items-center justify-center">
                <i class="fas fa-times text-gray-600"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white mb-3">
                    <i class="fas fa-spell-check text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800" id="wordModalTitle"></h3>
                <p class="text-gray-500 text-sm mt-1">Análise completa da palavra</p>
            </div>
            
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-5 mb-5">
                <p class="text-center text-sm text-gray-500 mb-3">Separação silábica</p>
                <div class="flex flex-wrap justify-center" id="syllablesDisplay"></div>
            </div>
            
            <div class="classification-grid" id="wordInfoGrid"></div>
            
            <!-- Etymology -->
            <div class="mt-5 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200" id="etymologySection">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">🌍</span>
                    <div>
                        <p class="font-semibold text-indigo-800">Possível origem etimológica</p>
                        <p class="text-sm text-indigo-700 mt-1" id="etymologyText"></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200" id="pronunciationTip"></div>
            
            <!-- Action Buttons -->
            <div class="mt-5 flex gap-3">
                <button id="findRhymesBtn" class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 text-white px-4 py-3 rounded-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-music mr-2"></i>Encontrar Rimas
                </button>
                <button id="findDefinitionBtn" class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-3 rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all">
                    <i class="fas fa-book mr-2"></i>Ver Definição
                </button>
            </div>
        </div>
    </div>

    <!-- Definition Modal -->
    <div class="definition-modal" id="definitionModal">
        <div class="modal-card">
            <button class="closeModal absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-all flex items-center justify-center">
                <i class="fas fa-times text-gray-600"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white mb-3">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800" id="definitionModalTitle"></h3>
                <p class="text-gray-500 text-sm mt-1">Definição e significado</p>
            </div>
            
            <div id="definitionContent" class="space-y-4">
                <div class="flex justify-center">
                    <div class="loading-spinner"></div>
                </div>
                <p class="text-center text-gray-500">Buscando definição...</p>
            </div>
        </div>
    </div>

    <!-- Curiosity Modal -->
    <div class="curiosity-modal" id="curiosityModal">
        <div class="modal-card" style="max-width: 500px;">
            <button class="closeModal absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-all flex items-center justify-center">
                <i class="fas fa-times text-gray-600"></i>
            </button>
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl text-white mb-4">
                    <i class="fas fa-lightbulb text-3xl"></i>
                </div>
                <span class="block text-sm text-amber-600 font-medium" id="modalNumber">Curiosidade #1</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 text-center mb-4" id="modalTitle"></h3>
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 text-gray-700" id="modalContent"></div>
            <div class="flex justify-center gap-3 mt-6">
                <button id="prevCuriosity" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button id="nextCuriosity" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all flex items-center gap-2">
                    Próxima <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>



    <script>
        // ==================== CURIOSIDADES DATA ====================
        const curiosidades = [
            { id: 1, category: 'words', emoji: '📏', title: 'A maior palavra do dicionário', highlight: 'pneumoultramicroscopicossilicovulcanoconiótico', content: 'Tem <strong>46 letras</strong> e se refere a quem sofre de uma doença pulmonar causada por poeira vulcânica microscópica.' },
            { id: 2, category: 'words', emoji: '🔤', title: 'A menor palavra da língua', highlight: 'o', content: 'A menor palavra é simplesmente a letra <strong>"o"</strong> — um artigo definido completo!' },
            { id: 3, category: 'words', emoji: '😰', title: '"Paralelepípedo" assusta estrangeiros', highlight: 'paralelepípedo', content: 'É considerada uma das palavras mais difíceis de pronunciar do português. Tente falar 5 vezes rápido!' },
            { id: 4, category: 'words', emoji: '🔄', title: 'Palíndromos perfeitos', highlight: 'arara • radar • osso • reviver • salas', content: 'Palavras que podem ser lidas de trás pra frente e continuam iguais. O português tem várias!' },
            { id: 5, category: 'words', emoji: '✨', title: 'Palavra longa sem repetir letras', highlight: 'subcontinentalmente', content: 'Tem <strong>20 letras diferentes</strong> sem nenhuma repetição. Impressionante, né?' },
            { id: 6, category: 'words', emoji: '〰️', title: 'O til (ã õ) é quase exclusivo', highlight: 'ã õ', content: 'O til quase não existe em outras línguas. É uma marca muito característica do português!' },
            { id: 7, category: 'words', emoji: '😱', title: 'Medo de palavras grandes', highlight: 'hipopotomonstrosesquipedaliofobia', content: 'Ironicamente, essa é a palavra que descreve o medo de palavras grandes! Tem <strong>33 letras</strong>.' },
            { id: 8, category: 'words', emoji: '💪', title: 'O músculo do pescoço', highlight: 'esternocleidomastóideo', content: 'É o nome de um músculo do pescoço e tem <strong>22 letras</strong>. Usado em concursos de soletração!' },
            { id: 9, category: 'words', emoji: '🔡', title: 'A letra X tem 5 sons diferentes', highlight: 'xícara • hexágono • máximo • exame • texto', content: 'O X pode soar como CH, KS, S, Z ou CS dependendo da palavra. Nenhuma outra letra é tão versátil!' },
            { id: 10, category: 'words', emoji: '🌟', title: '"Luz" é especial', highlight: 'luz', content: 'É a única palavra monossílaba terminada em Z no português. Única mesmo!' },
            { id: 11, category: 'grammar', emoji: '📝', title: 'O verbo mais irregular', highlight: 'ser', content: 'O verbo "ser" é o mais irregular do português. Conjugue: eu sou, tu és, ele é... nada se parece!' },
            { id: 12, category: 'grammar', emoji: '🚫', title: 'Verbos defectivos não conjugam tudo', highlight: 'colorir • abolir • falir', content: 'Alguns verbos não têm todas as conjugações. Não se diz "eu coloro" nem "eu falo" (de falir)!' },
            { id: 13, category: 'grammar', emoji: '⏰', title: '9 tempos verbais principais', highlight: '9 tempos', content: 'O português tem mais tempos verbais do que muitas línguas europeias. Conjugar é uma arte!' },
            { id: 14, category: 'grammar', emoji: '🎭', title: 'Uma palavra, múltiplos sentidos', highlight: 'manga', content: '<strong>Manga</strong> pode ser a fruta, parte da camisa, ou estilo de desenho japonês. Contexto é tudo!' },
            { id: 15, category: 'grammar', emoji: '👔', title: 'Heterônimos: mesma escrita, som diferente', highlight: 'colher (verbo) vs colher (utensílio)', content: 'Escrevemos igual, mas falamos diferente: "Vou colhER as flores" vs "Me passa a COlher".' },
            { id: 16, category: 'grammar', emoji: '🔧', title: 'O único verbo terminado em "-or"', highlight: 'pôr', content: 'É o único verbo do português que termina em "-or". Todos os outros são -ar, -er ou -ir!' },
            { id: 17, category: 'grammar', emoji: '👥', title: 'Plural irregular curioso', highlight: 'cidadão → cidadãos', content: 'Diferente de "cão → cães", "cidadão" faz plural apenas com S. Muita gente erra isso!' },
            { id: 18, category: 'grammar', emoji: '♀️', title: 'Palavras em "-ção" são femininas', highlight: 'a ação, a canção, a nação', content: 'Toda palavra terminada em "-ção" é feminina, sem exceção. É uma regra 100% confiável!' },
            { id: 19, category: 'grammar', emoji: '🇧🇷', title: 'A crase é quase exclusiva', highlight: 'à', content: 'A crase (à) praticamente só existe no português! É a fusão de duas letras "a" (preposição + artigo).' },
            { id: 20, category: 'grammar', emoji: '🤔', title: '"Ai" tem três funções', highlight: 'ai (interjeição) • ai (verbo ir) • aí (lugar)', content: 'Uma palavrinha de duas letras pode ser interjeição, verbo ou advérbio!' },
            { id: 21, category: 'origin', emoji: '🥜', title: '"Caju" é internacional', highlight: 'caju → cashew', content: 'A palavra vem do <strong>tupi "acaiu"</strong> e existe em dezenas de idiomas, incluindo inglês (cashew).' },
            { id: 22, category: 'origin', emoji: '🕌', title: 'Palavras do árabe', highlight: 'azeite • açúcar • alfândega • almofada', content: 'Mais de 1000 palavras portuguesas vêm do árabe, herança de 800 anos de presença moura na Península Ibérica!' },
            { id: 23, category: 'origin', emoji: '🌿', title: 'Palavras indígenas brasileiras', highlight: 'mandioca • jacaré • capivara • abacaxi', content: 'Milhares de palavras vieram do tupi-guarani: nomes de animais, plantas, lugares e comidas!' },
            { id: 24, category: 'origin', emoji: '🌍', title: 'Palavras africanas no Brasil', highlight: 'moleque • caçula • samba • dengue • batuque', content: 'A cultura africana contribuiu com milhares de palavras, especialmente na música e no cotidiano.' },
            { id: 25, category: 'origin', emoji: '🔥', title: '"Brasil" vem de "brasa"', highlight: 'brasil = terra cor de brasa', content: 'O nome vem do pau-brasil, madeira avermelhada como brasa, muito valiosa na época colonial!' },
            { id: 26, category: 'origin', emoji: '🚗', title: '"Carro" e "carruagem" — mesma raiz', highlight: 'carrus (latim)', content: 'Ambas vêm do latim <strong>carrus</strong>, que significa veículo de rodas.' },
            { id: 27, category: 'origin', emoji: '🚌', title: '"Ônibus" significa "para todos"', highlight: 'omnibus (latim)', content: 'Vem do latim <strong>omnibus</strong> = "para todos". Um transporte para todas as pessoas!' },
            { id: 28, category: 'origin', emoji: '🍫', title: '"Chocolate" é asteca', highlight: 'xocolatl', content: 'Vem do náuatle (língua asteca) <strong>xocolatl</strong>, que significa "água amarga".' },
            { id: 29, category: 'origin', emoji: '📱', title: '"Telefone" é grego', highlight: 'tele (longe) + phone (som)', content: 'Significa literalmente "som de longe". Muitas palavras técnicas vêm do grego!' },
            { id: 30, category: 'origin', emoji: '🦈', title: '"Tubarão" é tupi', highlight: 'tupi: iperú', content: 'Vem do tupi e passou para outras línguas. O inglês "shark" tem origem diferente!' },
            { id: 31, category: 'history', emoji: '👑', title: '"Você" já foi título de nobreza', highlight: 'Vossa Mercê → vosmecê → você', content: 'A palavra "você" evoluiu de uma forma respeitosa de tratamento à nobreza!' },
            { id: 32, category: 'history', emoji: '📚', title: 'O alfabeto já teve 23 letras', highlight: 'K, W e Y', content: 'As letras K, W e Y não existiam oficialmente até o <strong>Acordo Ortográfico de 2009</strong>.' },
            { id: 33, category: 'history', emoji: '🏛️', title: 'Português vem do latim vulgar', highlight: 'latim vulgar', content: 'Não do latim clássico usado por escritores romanos, mas sim do latim falado pelo povo comum!' },
            { id: 34, category: 'history', emoji: '📜', title: 'O til já foi um N', highlight: 'ã = an / õ = on', content: 'O til (~) era originalmente um N pequeno escrito sobre a vogal! "Irmão" = "irmano".' },
            { id: 35, category: 'history', emoji: '🔠', title: 'O trema foi abolido', highlight: 'lingüiça → linguiça', content: 'O trema (¨) foi eliminado pelo Acordo Ortográfico de 2009. Antes era "freqüente", agora é "frequente".' },
            { id: 36, category: 'history', emoji: '📖', title: 'Primeira gramática: 1536', highlight: 'Fernão de Oliveira', content: 'A primeira gramática do português foi escrita por Fernão de Oliveira em 1536!' },
            { id: 37, category: 'history', emoji: '✍️', title: 'Camões eternizou o português', highlight: 'Os Lusíadas (1572)', content: 'Luís de Camões, com "Os Lusíadas", consolidou o português como língua literária mundial.' },
            { id: 38, category: 'history', emoji: '🗣️', title: '"Fofoca" veio de um nome próprio', highlight: 'Foca', content: 'Dizem que vem de <strong>Foca</strong>, uma escrava fofoqueira do Rio de Janeiro antigo! 😅' },
            { id: 39, category: 'history', emoji: '📝', title: 'O hífen mudou muito', highlight: 'auto-escola → autoescola', content: 'O Acordo Ortográfico de 2009 mudou milhares de palavras com hífen. Ainda confunde muita gente!' },
            { id: 40, category: 'history', emoji: '🌐', title: 'Língua oficial mais antiga da Europa', highlight: 'português (1290)', content: 'O português foi oficializado em 1290 pelo rei D. Dinis, sendo uma das línguas oficiais mais antigas!' },
            { id: 41, category: 'fun', emoji: '💔', title: '"Saudade" não tem tradução perfeita', highlight: 'saudade', content: 'Nenhuma língua possui uma palavra única que traduza exatamente "saudade". É considerada uma das palavras mais bonitas do mundo!' },
            { id: 42, category: 'fun', emoji: '🔠', title: 'Frase com todas as letras sem repetir', highlight: 'Blitz prende ex-vesgo com cheque fajuto.', content: 'Essa frase é um <strong>pangrama perfeito</strong> — usa todas as letras do alfabeto sem repetir nenhuma!' },
            { id: 43, category: 'fun', emoji: '🌍', title: '5ª língua mais falada do mundo', highlight: '265 milhões', content: 'Mais de <strong>265 milhões de pessoas</strong> falam português! É a língua oficial de 9 países.' },
            { id: 44, category: 'fun', emoji: '🇧🇷', title: 'Palavras exclusivas do Brasil', highlight: 'cafuné • gambiarra • xodó', content: 'Palavras como cafuné, gambiarra, xodó e saudade são típicas do português brasileiro!' },
            { id: 45, category: 'fun', emoji: '🇵🇹', title: 'Palavras mudam por país', highlight: 'autocarro vs ônibus', content: 'Portugal e Brasil usam palavras diferentes: rapariga/moça, autocarro/ônibus, telemóvel/celular...' },
            { id: 46, category: 'fun', emoji: '🎵', title: 'O som nasal é raro', highlight: 'ã • ão • õe', content: 'O português tem muitos sons nasais (pão, mãe, põe). Isso é raro em outras línguas e difícil para estrangeiros!' },
            { id: 47, category: 'fun', emoji: '😮', title: 'Trava-línguas impossíveis', highlight: 'O rato roeu a roupa do rei de Roma', content: 'O português tem trava-línguas desafiadores! Tente: "Três tigres tristes para três pratos de trigo".' },
            { id: 48, category: 'fun', emoji: '🎲', title: 'Anagramas curiosos', highlight: 'amor = roma = mora = ramo', content: 'Rearranjando as letras, uma palavra vira outra! "Iracema" de José de Alencar é anagrama de "América".' },
            { id: 49, category: 'fun', emoji: '🤯', title: 'Onomatopeias variadas', highlight: 'tchibum • ploft • vrum • miau', content: 'Sons são representados diferente em cada língua. O galo brasileiro faz "cocoricó", o americano faz "cock-a-doodle-doo"!' },
            { id: 50, category: 'fun', emoji: '🏆', title: 'Português em expansão', highlight: '4ª língua mais estudada', content: 'O português é a 4ª língua mais estudada no mundo, depois do inglês, espanhol e chinês!' }
        ];

        // ==================== ETYMOLOGY DATA ====================
        const etymologyPatterns = [
            { patterns: ['ção', 'são', 'mente', 'dade', 'tude'], origin: 'latin', label: 'Latim', desc: 'Origem latina, através do latim vulgar' },
            { patterns: ['açu', 'mirim', 'oca', 'ara', 'peba', 'tinga', 'iba', 'uba'], origin: 'tupi', label: 'Tupi', desc: 'Origem indígena brasileira (tupi-guarani)' },
            { patterns: ['al', 'aza', 'afe', 'xeque', 'alm'], origin: 'arabic', label: 'Árabe', desc: 'Origem árabe, herança mourisca' },
            { patterns: ['ung', 'ang', 'eng', 'amba', 'ongo'], origin: 'african', label: 'Africana', desc: 'Origem africana (banto, iorubá)' },
            { patterns: ['logia', 'grafia', 'fone', 'scope', 'teca', 'fobia'], origin: 'greek', label: 'Grego', desc: 'Origem grega, geralmente científica' }
        ];

        // ==================== APPLICATION STATE ====================
        let palavras = [];
        let currentFilter = 'starts';
        let selectedLength = null;
        let selectedAccent = null;
        let currentPage = 1;
        const itemsPerPage = 100;
        let filteredWords = [];
        let isLoading = true;
        let currentCuriosityIndex = 0;
        let filteredCuriosities = [...curiosidades];
        let currentWordForModal = '';
        let rhymeType = 'perfect';
        let charts = {};

        // ==================== DOM ELEMENTS ====================
        const dictionaryStatus = document.getElementById('dictionaryStatus');
        const footerStats = document.getElementById('footerStats');

        // ==================== DICTIONARY LOADING ====================
        const dictionaryURLs = [
            'https://raw.githubusercontent.com/pythonprobr/palavras/master/palavras.txt'
        ];

        function normalizeWord(word) {
            return word.trim().toLowerCase();
        }

        function isValidWord(word) {
            return /^[a-záàâãéèêíïóôõöúçñ]+$/i.test(word) && word.length >= 2 && word.length <= 30;
        }

        async function loadDictionary() {
            try {
                // 1. Carregamento prioritário local offline instantâneo
                const localResp = await fetch('./palavras.json');
                if (localResp.ok) {
                    palavras = await localResp.json();
                    isLoading = false;
                    updateDictionaryStatus();
                    updateStatistics();
                    return palavras.length;
                }
            } catch (err) {
                console.warn('[Palavreado] Carregamento local falhou, tentando fallback remoto:', err);
            }

            // 2. Fallback remoto
            let loadedWords = new Set();
            for (const url of dictionaryURLs) {
                try {
                    const response = await fetch(url);
                    if (response.ok) {
                        const text = await response.text();
                        const words = text.split('\n').map(normalizeWord).filter(isValidWord);
                        words.forEach(word => loadedWords.add(word));
                    }
                } catch (error) {
                    console.log(`Erro ao carregar ${url}:`, error);
                }
            }

            if (loadedWords.size === 0) {
                loadedWords = new Set(getBackupDictionary());
            }

            palavras = Array.from(loadedWords).sort((a, b) => a.localeCompare(b, 'pt-BR'));
            isLoading = false;
            updateDictionaryStatus();
            updateStatistics();
            return palavras.length;
        }

        function getBackupDictionary() {
            return ["abacate","abacaxi","abelha","aberto","abolir","abóbora","abraçar","abril","absoluto","abundante","acabar","academia","aceitar","acelerar","acender","achar","acidente","acordar","acreditar","açúcar","adaptar","adeus","admirar","adorar","adulto","advogado","aeroporto","afastar","afetar","afinal","agência","agenda","agir","agora","agosto","agradar","agradecer","água","aguardar","ainda","ajudar","alarme","alcançar","alegre","alegria","além","alfabeto","alguém","algum","alimento","alma","almoço","alto","altura","aluno","amanhã","amar","amarelo","ambiente","amigo","amizade","amor","análise","andar","animal","aniversário","ano","antes","antigo","anunciar","aparecer","apartamento","apenas","aplicar","apoiar","aprender","apresentar","aprovar","aquele","aqui","ar","área","areia","armário","arquivo","arroz","arte","artigo","artista","árvore","assim","assistir","assumir","assunto","atenção","atender","atividade","atleta","atmosfera","atrás","através","atual","aula","aumentar","ausência","autor","avaliar","avançar","ave","avenida","avião","avisar","azul","banana","banco","bandeira","banheiro","barato","barco","base","bebê","beber","beijo","beleza","belo","bem","biblioteca","bicicleta","bilhete","bloco","boca","bola","bolo","bolsa","bom","bonito","braço","branco","brasil","brasileiro","brilhar","brincar","buraco","buscar","cabeça","cabelo","cachorro","cada","cadeira","café","cair","caixa","calma","calor","cama","caminho","camisa","campo","canção","caneta","cantar","cão","capacidade","capital","cara","caráter","carne","caro","carro","carta","casa","casal","caso","causa","cavalo","cedo","cem","cena","centro","céu","chamar","chance","chão","chave","chefe","chegar","cheio","chocolate","chorar","chuva","cidade","ciência","cinco","cinema","claro","classe","cliente","clima","clube","código","coisa","colocar","com","começar","comer","comida","como","companhia","comprar","comum","comunidade","condição","conhecer","conseguir","conselho","construir","conta","contar","continuar","contra","conversa","coração","cor","corpo","correr","correto","cortar","costa","costume","cozinha","criar","criança","crime","crise","cruz","cuidado","cultura","curso","dado","dança","dar","data","decidir","decisão","dedo","deixar","dentro","depois","desafio","descobrir","desejo","destino","deus","dever","dia","diferença","diferente","difícil","dinheiro","direito","dirigir","disco","distância","diversão","dividir","dizer","doce","doença","dois","domingo","dor","dormir","doutor","durante","duro","dúvida","economia","educação","efeito","ela","ele","elefante","eleição","elemento","em","emoção","emprego","empresa","encontrar","energia","enfim","enorme","ensinar","então","entender","entrada","entrar","entre","enviar","época","equipe","erro","escada","escola","escolher","escrever","esforço","espaço","especial","espelho","esperar","esporte","esposa","esse","estado","estar","este","estilo","estrada","estrela","estudar","estudo","eu","evento","evitar","exame","exemplo","exercício","existir","experiência","explicar","fábrica","faca","face","fácil","falar","falta","fama","família","famoso","fase","fato","favor","fazer","fechar","feliz","festa","filho","filme","fim","final","fino","flor","fogo","folha","fome","fonte","fora","força","forma","forte","foto","francês","frente","frio","fruta","fugir","função","fundo","futebol","futuro","ganhar","garantir","gastar","gato","geral","gesto","governo","grande","grave","gritar","grupo","guardar","guerra","hora","hoje","homem","hospital","hotel","humano","idade","ideia","igreja","igual","ilha","imagem","imaginar","imenso","importante","incluir","indicar","indústria","informação","início","insistir","instante","inteiro","interesse","interior","internet","ir","irmã","irmão","isso","isto","já","janeiro","janela","jantar","jardim","jogo","jogador","jogar","jornal","jovem","juiz","julho","junho","junto","justo","lado","lago","lápis","lar","laranja","lavar","leão","legal","lei","leite","lembrar","ler","letra","levantar","levar","leve","liberdade","líder","ligar","limite","limpo","lindo","língua","linha","lista","livre","livro","local","logo","loja","longe","longo","lua","lugar","luta","luz","mãe","maior","mais","mal","mão","mapa","máquina","mar","marca","março","marido","mas","matar","matemática","matéria","material","máximo","médico","medo","meio","melhor","memória","menina","menino","menor","menos","mensagem","mente","mercado","mês","mesa","mesmo","meta","método","meu","mil","milhão","militar","minuto","missão","moda","modelo","moderno","modo","moeda","momento","montanha","moral","morrer","morte","mostrar","motivo","motor","mover","movimento","mudança","mudar","muito","mulher","mundo","muro","museu","música","nadar","não","nariz","nascer","natural","natureza","necessário","negócio","negro","nem","nenhum","neve","ninguém","noite","nome","normal","norte","nota","notícia","nove","novo","número","nunca","objetivo","objeto","obra","observar","obter","oceano","ocupar","oferecer","olhar","olho","onde","ontem","opção","operação","opinião","ordem","orelha","organização","origem","ouro","outro","outubro","ouvir","ovo","paciente","padre","pagar","página","pai","país","paisagem","palavra","pão","papel","par","para","parar","parecer","parede","parque","parte","partir","passado","passar","passo","paz","pé","peça","pedir","pedra","peito","pele","pelo","pena","pensar","pequeno","perder","perfeito","pergunta","perigo","permitir","perna","perto","pesado","pesquisa","pessoa","pior","plano","planta","poder","poema","poesia","pois","polícia","política","político","ponto","popular","por","porta","portanto","porto","português","posição","possível","posto","pouco","povo","praça","praia","prática","prato","prazer","preço","preciso","preferir","prêmio","preparar","presente","presidente","preto","primeiro","principal","problema","processo","procurar","produção","produto","professor","profissão","programa","projeto","prometer","pronto","próprio","proteger","provar","próximo","público","puro","quadro","qual","qualidade","qualquer","quando","quanto","quarto","quase","quatro","que","quebrar","quem","quente","querer","questão","rádio","raiva","rapaz","rápido","razão","real","realidade","realizar","realmente","receber","recente","reconhecer","recordar","rede","reduzir","região","regra","rei","relação","religião","remédio","repetir","representar","república","resolver","respeito","responder","resposta","resto","resultado","reunião","revelar","revista","rico","rio","rir","risco","ritmo","rocha","roda","rosa","rosto","roupa","rua","rural","saber","sair","sala","salário","sangue","santo","são","saúde","se","secretário","século","seguir","segundo","segurança","seguro","seis","sem","semana","sempre","senhor","senhora","sensação","sentar","sentido","sentir","ser","sério","serviço","servir","sete","setembro","seu","sexual","si","significar","silêncio","sim","simples","sistema","situação","só","sob","sobre","social","sociedade","sofrer","sol","soldado","solo","solução","som","sombra","sonhar","sonho","sorrir","sorriso","sorte","sua","subir","sucesso","sul","superior","surgir","surpresa","tal","talvez","tamanho","também","tão","tarde","teatro","técnica","tecnologia","tela","televisão","tema","tempo","tender","tentar","ter","terra","terreno","teste","texto","tipo","tirar","título","tocar","todo","tomar","tom","tornar","total","trabalhar","trabalho","tradição","trazer","três","triste","trocar","tudo","último","um","uma","único","unir","universidade","universo","usar","uso","útil","valer","valor","vários","vazio","velho","vencer","vender","vento","ver","verão","verdade","verde","vez","via","viagem","vida","vidro","vila","vinho","vinte","violência","vir","visão","visita","vista","viver","vivo","vizinho","você","volta","voltar","vontade","voz","zero","zona"];
        }

        function updateDictionaryStatus() {
            if (palavras.length > 0) {
                dictionaryStatus.className = 'bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3';
                dictionaryStatus.innerHTML = `
                    <div class="text-green-500 text-2xl"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <p class="font-medium text-green-800">Dicionário carregado com sucesso!</p>
                        <p class="text-sm text-green-600">${palavras.length.toLocaleString('pt-BR')} palavras disponíveis</p>
                    </div>
                `;
                footerStats.textContent = `Banco de dados: ${palavras.length.toLocaleString('pt-BR')} palavras em português`;
            }
        }

        // ==================== SYLLABLE FUNCTIONS ====================
        const VOWELS = 'aáàâãeéêiíoóôõuúü';
        const CONSONANTS = 'bcçdfghjklmnñpqrstvwxyz';
        const DIGRAPHS = ['ch', 'lh', 'nh', 'gu', 'qu'];
        const INSEPARABLE_CLUSTERS = ['bl', 'cl', 'dl', 'fl', 'gl', 'pl', 'tl', 'vl', 'br', 'cr', 'dr', 'fr', 'gr', 'pr', 'tr', 'vr'];

        function isVowel(char) { 
            if (!char || typeof char !== 'string') return false;
            return VOWELS.includes(char.toLowerCase()); 
        }
        function isConsonant(char) { 
            if (!char || typeof char !== 'string') return false;
            return CONSONANTS.includes(char.toLowerCase()); 
        }
        function hasVowel(str) { 
            if (!str || typeof str !== 'string') return false;
            return str.split('').some(c => c && isVowel(c)); 
        }

        function isDiphthong(v1, v2) {
            const diphthongs = ['ai', 'au', 'ei', 'eu', 'iu', 'oi', 'ou', 'ui', 'ãe', 'ão', 'õe', 'éi', 'éu', 'ói', 'uí', 'ia', 'ie', 'io', 'ua', 'ue', 'uo'];
            return diphthongs.includes((v1 + v2).toLowerCase());
        }

        function separateSyllables(word) {
            if (!word || typeof word !== 'string') return [''];
            word = word.toLowerCase().trim();
            if (word.length === 0) return [''];
            if (word.length === 1) return [word];
            
            // Simple fallback for very short words
            if (word.length <= 3) return [word];
            
            const syllables = [];
            let currentSyllable = '';
            let i = 0;
            
            try {
            while (i < word.length) {
                const char = word[i] || '';
                const next = word[i + 1] || '';
                const afterNext = word[i + 2] || '';
                
                if (!char) { i++; continue; }
                
                currentSyllable += char;
                
                if (isVowel(char)) {
                    if (isVowel(next)) {
                        if (isDiphthong(char, next)) {
                            currentSyllable += next;
                            i++;
                        } else {
                            syllables.push(currentSyllable);
                            currentSyllable = '';
                        }
                    } else if (isConsonant(next)) {
                        const twoConsonants = next + afterNext;
                        const isDigraph = DIGRAPHS.includes(twoConsonants.toLowerCase());
                        const isInseparable = INSEPARABLE_CLUSTERS.includes(twoConsonants.toLowerCase());
                        
                        if (isDigraph || isInseparable) {
                            if (isVowel(word[i + 3])) {
                                syllables.push(currentSyllable);
                                currentSyllable = '';
                            }
                        } else if (isConsonant(next) && isConsonant(afterNext)) {
                            currentSyllable += next;
                            i++;
                            syllables.push(currentSyllable);
                            currentSyllable = '';
                        } else if (isConsonant(next) && isVowel(afterNext)) {
                            syllables.push(currentSyllable);
                            currentSyllable = '';
                        } else if (isConsonant(next) && !afterNext) {
                            currentSyllable += next;
                            i++;
                        }
                    }
                } else if (isConsonant(char) && currentSyllable.length > 0) {
                    const potentialDigraph = char + next;
                    if (DIGRAPHS.includes(potentialDigraph.toLowerCase())) {
                        currentSyllable += next;
                        i++;
                    }
                }
                i++;
            }
            
            if (currentSyllable) syllables.push(currentSyllable);
            return cleanSyllables(syllables);
            } catch (e) {
                console.error('Erro ao separar sílabas:', e);
                return [word];
            }
        }

        function cleanSyllables(syllables) {
            if (!syllables || !Array.isArray(syllables)) return [''];
            const cleaned = syllables.filter(s => s && s.length > 0);
            if (cleaned.length === 0) return [''];
            
            const result = [];
            
            for (let i = 0; i < cleaned.length; i++) {
                const syl = cleaned[i];
                if (!syl) continue;
                if (!hasVowel(syl) && result.length > 0) {
                    result[result.length - 1] += syl;
                } else if (!hasVowel(syl) && i < cleaned.length - 1) {
                    cleaned[i + 1] = syl + (cleaned[i + 1] || '');
                } else {
                    result.push(syl);
                }
            }
            
            if (result.length > 1 && result[result.length - 1] && !hasVowel(result[result.length - 1])) {
                const last = result.pop();
                if (result.length > 0) {
                    result[result.length - 1] += last;
                } else {
                    result.push(last);
                }
            }
            
            return result.length > 0 ? result : [syllables.join('') || ''];
        }

        function findTonicSyllable(word, syllables) {
            if (!syllables || syllables.length === 0) return 0;
            if (!word || typeof word !== 'string') return 0;
            
            const accentedVowels = 'áéíóúâêôã';
            for (let i = 0; i < syllables.length; i++) {
                if (syllables[i]) {
                    for (const char of syllables[i]) {
                        if (char && accentedVowels.includes(char)) return i;
                    }
                }
            }
            
            const lastChar = word.slice(-1);
            const lastTwo = word.slice(-2);
            
            if (syllables.length === 1) return 0;
            if (['i', 'u', 'l', 'r', 'z', 'x'].includes(lastChar) || ['im', 'um', 'om'].includes(lastTwo)) {
                return syllables.length - 1;
            }
            return Math.max(0, syllables.length - 2);
        }

        function classifyBySyllableCount(count) {
            switch(count) {
                case 1: return { name: 'Monossílaba', emoji: '1️⃣' };
                case 2: return { name: 'Dissílaba', emoji: '2️⃣' };
                case 3: return { name: 'Trissílaba', emoji: '3️⃣' };
                default: return { name: 'Polissílaba', emoji: '🔢' };
            }
        }

        function classifyByTonic(syllables, tonicIndex) {
            const fromEnd = syllables.length - 1 - tonicIndex;
            switch(fromEnd) {
                case 0: return { name: 'Oxítona', desc: 'última sílaba', emoji: '🔴', type: 'oxitona' };
                case 1: return { name: 'Paroxítona', desc: 'penúltima sílaba', emoji: '🟡', type: 'paroxitona' };
                case 2: return { name: 'Proparoxítona', desc: 'antepenúltima sílaba', emoji: '🟢', type: 'proparoxitona' };
                default: return { name: 'Especial', desc: 'classificação especial', emoji: '⚪', type: 'especial' };
            }
        }

        function getWordAccentType(word) {
            try {
                if (!word || typeof word !== 'string') return 'paroxitona';
                word = word.trim().toLowerCase();
                if (!word) return 'paroxitona';
                
                const syllables = separateSyllables(word);
                if (!syllables || !Array.isArray(syllables) || syllables.length === 0) return 'paroxitona';
                
                const tonicIndex = findTonicSyllable(word, syllables);
                const classification = classifyByTonic(syllables, tonicIndex);
                return classification ? classification.type : 'paroxitona';
            } catch (e) {
                return 'paroxitona';
            }
        }

        // ==================== ETYMOLOGY FUNCTIONS ====================
        function guessEtymology(word) {
            word = word.toLowerCase();
            for (const pattern of etymologyPatterns) {
                for (const p of pattern.patterns) {
                    if (word.includes(p)) {
                        return { origin: pattern.origin, label: pattern.label, desc: pattern.desc };
                    }
                }
            }
            return { origin: 'latin', label: 'Latim', desc: 'Provável origem latina (mais comum no português)' };
        }

        // ==================== RHYME FUNCTIONS ====================
        function getWordEnding(word, syllableCount = 1) {
            try {
                if (!word || typeof word !== 'string') return '';
                word = word.trim().toLowerCase();
                if (!word) return '';
                
                const syllables = separateSyllables(word);
                if (!syllables || !Array.isArray(syllables) || syllables.length === 0) return word.slice(-2) || '';
                
                if (syllableCount === 1) {
                    return syllables[syllables.length - 1] || word.slice(-2) || '';
                } else if (syllableCount === 2 && syllables.length >= 2) {
                    return syllables.slice(-2).join('') || word.slice(-4) || '';
                }
                return syllables[syllables.length - 1] || word.slice(-2) || '';
            } catch (e) {
                return word ? word.slice(-2) : '';
            }
        }

        function getVowelPattern(word) {
            try {
                if (!word || typeof word !== 'string') return '';
                const vowels = [];
                for (const c of word) {
                    if (c && isVowel(c)) vowels.push(c);
                }
                return vowels.slice(-3).join('');
            } catch (e) {
                return '';
            }
        }

        function findRhymes(word, type = 'perfect') {
            try {
                if (!word || typeof word !== 'string' || palavras.length === 0) return [];
                
                word = word.toLowerCase().trim();
                if (!word) return [];
                
                let rhymes = [];
                
                if (type === 'perfect') {
                    const ending = getWordEnding(word, 1);
                    if (!ending) return [];
                    rhymes = palavras.filter(w => {
                        if (!w || typeof w !== 'string' || w === word) return false;
                        try {
                            return getWordEnding(w, 1) === ending;
                        } catch (e) { return false; }
                    });
                } else if (type === 'rich') {
                    const ending = getWordEnding(word, 2);
                    if (!ending) return [];
                    rhymes = palavras.filter(w => {
                        if (!w || typeof w !== 'string' || w === word) return false;
                        try {
                            return getWordEnding(w, 2) === ending;
                        } catch (e) { return false; }
                    });
                } else if (type === 'assonant') {
                    const vowelPattern = getVowelPattern(word);
                    if (!vowelPattern) return [];
                    rhymes = palavras.filter(w => {
                        if (!w || typeof w !== 'string' || w === word) return false;
                        try {
                            return getVowelPattern(w) === vowelPattern;
                        } catch (e) { return false; }
                    });
                }
                
                return rhymes.sort((a, b) => a.length - b.length).slice(0, 200);
            } catch (e) {
                console.error('Erro ao buscar rimas:', e);
                return [];
            }
        }

        // ==================== ADVANCED SEARCH ====================
        function advancedSearch(mustContain, exclude, pattern) {
            return palavras.filter(word => {
                if (mustContain) {
                    const letters = mustContain.toLowerCase().split('');
                    if (!letters.every(letter => word.includes(letter))) return false;
                }
                
                if (exclude) {
                    const letters = exclude.toLowerCase().split('');
                    if (letters.some(letter => word.includes(letter))) return false;
                }
                
                if (pattern) {
                    const regex = new RegExp('^' + pattern.toLowerCase().replace(/_/g, '.') + '$');
                    if (!regex.test(word)) return false;
                }
                
                return true;
            });
        }

        // ==================== STATISTICS ====================
        function updateStatistics() {
            if (palavras.length === 0) return;

            // Basic stats
            document.getElementById('statTotalWords').textContent = palavras.length.toLocaleString('pt-BR');
            
            const longest = palavras.reduce((a, b) => a.length >= b.length ? a : b);
            document.getElementById('statLongestWord').textContent = longest.length;
            
            const avgLength = (palavras.reduce((sum, w) => sum + w.length, 0) / palavras.length).toFixed(1);
            document.getElementById('statAvgLength').textContent = avgLength;
            
            // Most common starting letter
            const startLetterCount = {};
            palavras.forEach(w => {
                const first = w[0].toUpperCase();
                startLetterCount[first] = (startLetterCount[first] || 0) + 1;
            });
            const mostCommonStart = Object.entries(startLetterCount).sort((a, b) => b[1] - a[1])[0];
            document.getElementById('statMostCommonStart').textContent = mostCommonStart[0];

            // Longest words list
            const longestWords = [...palavras].sort((a, b) => b.length - a.length).slice(0, 10);
            document.getElementById('longestWordsList').innerHTML = longestWords.map((w, i) => 
                `<div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                    <span class="font-medium">${i + 1}. ${w}</span>
                    <span class="text-sm text-gray-500">${w.length} letras</span>
                </div>`
            ).join('');

            // Shortest words list
            const shortestWords = palavras.filter(w => w.length === 2).slice(0, 20);
            document.getElementById('shortestWordsList').innerHTML = shortestWords.map(w => 
                `<span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium m-1">${w}</span>`
            ).join('');

            // Create charts
            createCharts(startLetterCount);
        }

        function createCharts(startLetterCount) {
            // Length distribution chart
            const lengthDist = {};
            palavras.forEach(w => {
                const len = w.length;
                lengthDist[len] = (lengthDist[len] || 0) + 1;
            });
            
            const lengthLabels = Object.keys(lengthDist).sort((a, b) => a - b);
            const lengthData = lengthLabels.map(l => lengthDist[l]);

            if (charts.lengthChart) charts.lengthChart.destroy();
            charts.lengthChart = new Chart(document.getElementById('lengthChart'), {
                type: 'bar',
                data: {
                    labels: lengthLabels.map(l => l + ' letras'),
                    datasets: [{
                        label: 'Quantidade de palavras',
                        data: lengthData,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Letter frequency chart
            const sortedLetters = Object.entries(startLetterCount).sort((a, b) => b[1] - a[1]).slice(0, 15);
            
            if (charts.letterChart) charts.letterChart.destroy();
            charts.letterChart = new Chart(document.getElementById('letterChart'), {
                type: 'doughnut',
                data: {
                    labels: sortedLetters.map(l => l[0]),
                    datasets: [{
                        data: sortedLetters.map(l => l[1]),
                        backgroundColor: [
                            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
                            '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1',
                            '#14b8a6', '#f43f5e', '#a855f7', '#22c55e', '#eab308'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Overall letter frequency
            const letterFreq = {};
            palavras.forEach(w => {
                w.split('').forEach(c => {
                    if (/[a-záàâãéèêíïóôõöúçñ]/i.test(c)) {
                        const letter = c.toLowerCase();
                        letterFreq[letter] = (letterFreq[letter] || 0) + 1;
                    }
                });
            });
            
            const sortedFreq = Object.entries(letterFreq).sort((a, b) => b[1] - a[1]);
            
            if (charts.frequencyChart) charts.frequencyChart.destroy();
            charts.frequencyChart = new Chart(document.getElementById('frequencyChart'), {
                type: 'bar',
                data: {
                    labels: sortedFreq.map(l => l[0].toUpperCase()),
                    datasets: [{
                        label: 'Frequência',
                        data: sortedFreq.map(l => l[1]),
                        backgroundColor: 'rgba(139, 92, 246, 0.5)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // ==================== TEXT ANALYZER ====================
        function analyzeText(text) {
            const words = text.toLowerCase().match(/[a-záàâãéèêíïóôõöúçñ]+/gi) || [];
            const uniqueWords = [...new Set(words)];
            const chars = text.replace(/\s/g, '').length;
            
            // Word frequency
            const wordFreq = {};
            words.forEach(w => {
                wordFreq[w] = (wordFreq[w] || 0) + 1;
            });
            const topWords = Object.entries(wordFreq).sort((a, b) => b[1] - a[1]).slice(0, 20);
            
            // Letter frequency in text
            const letterFreq = {};
            text.toLowerCase().split('').forEach(c => {
                if (/[a-záàâãéèêíïóôõöúçñ]/i.test(c)) {
                    letterFreq[c] = (letterFreq[c] || 0) + 1;
                }
            });
            
            // Complexity score (based on average word length and unique word ratio)
            const avgWordLength = words.length > 0 ? words.reduce((sum, w) => sum + w.length, 0) / words.length : 0;
            const uniqueRatio = words.length > 0 ? uniqueWords.length / words.length : 0;
            let complexity = 'Simples';
            if (avgWordLength > 6 && uniqueRatio > 0.6) complexity = 'Complexo';
            else if (avgWordLength > 5 || uniqueRatio > 0.5) complexity = 'Médio';
            
            return {
                totalWords: words.length,
                uniqueWords: uniqueWords.length,
                chars,
                complexity,
                topWords,
                letterFreq
            };
        }

        // ==================== MODAL FUNCTIONS ====================
        function showWordModal(word) {
            currentWordForModal = word;
            const syllables = separateSyllables(word);
            const tonicIndex = findTonicSyllable(word, syllables);
            const countClass = classifyBySyllableCount(syllables.length);
            const tonicClass = classifyByTonic(syllables, tonicIndex);
            const etymology = guessEtymology(word);

            document.getElementById('wordModalTitle').textContent = word;
            
            document.getElementById('syllablesDisplay').innerHTML = syllables.map((syl, idx) => `
                <div class="syllable-box ${idx === tonicIndex ? 'tonic' : ''}">
                    ${syl}${idx === tonicIndex ? '<span class="ml-1 text-xs">🔊</span>' : ''}
                </div>
            `).join('');
            
            document.getElementById('wordInfoGrid').innerHTML = `
                <div class="classification-item"><div class="label">Nº de Sílabas</div><div class="value">${countClass.emoji} ${syllables.length}</div></div>
                <div class="classification-item"><div class="label">Classificação</div><div class="value">${countClass.name}</div></div>
                <div class="classification-item"><div class="label">Sílaba Tônica</div><div class="value">${tonicClass.emoji} ${syllables[tonicIndex] || '-'}</div></div>
                <div class="classification-item"><div class="label">Acentuação</div><div class="value">${tonicClass.name}</div></div>
            `;
            
            document.getElementById('etymologyText').innerHTML = `<span class="etymology-badge ${etymology.origin}">${etymology.label}</span> ${etymology.desc}`;
            
            document.getElementById('pronunciationTip').innerHTML = `
                <div class="flex items-start gap-3">
                    <span class="text-2xl">💡</span>
                    <div>
                        <p class="font-semibold text-amber-800">Dica de pronúncia</p>
                        <p class="text-sm text-amber-700 mt-1">
                            A palavra <strong>"${word}"</strong> é uma <strong>${countClass.name.toLowerCase()}</strong> 
                            ${tonicClass.name.toLowerCase()} — a sílaba forte é <strong>"${syllables[tonicIndex] || word}"</strong> 
                            (${tonicClass.desc}).
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('wordModal').classList.add('active');
        }

        function showDefinitionModal(word) {
            document.getElementById('definitionModalTitle').textContent = word;
            document.getElementById('definitionContent').innerHTML = `
                <div class="flex justify-center"><div class="loading-spinner"></div></div>
                <p class="text-center text-gray-500">Buscando definição...</p>
            `;
            document.getElementById('definitionModal').classList.add('active');
            
            // Use Dicio.com.br (web scraping approach via iframe or show link)
            setTimeout(() => {
                document.getElementById('definitionContent').innerHTML = `
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">Para ver a definição completa de <strong>"${word}"</strong>, clique no botão abaixo:</p>
                        <a href="https://www.dicio.com.br/${word}/" target="_blank" class="inline-block bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all">
                            <i class="fas fa-external-link-alt mr-2"></i>Ver no Dicio.com.br
                        </a>
                        <p class="text-sm text-gray-400 mt-4">Dicionário online de português</p>
                    </div>
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                        <h4 class="font-semibold text-blue-800 mb-2">📚 Outras fontes:</h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://michaelis.uol.com.br/busca?palavra=${word}" target="_blank" class="text-blue-600 hover:underline text-sm">Michaelis</a>
                            <a href="https://www.priberam.org/dlpo/${word}" target="_blank" class="text-blue-600 hover:underline text-sm">Priberam</a>
                            <a href="https://www.aulete.com.br/${word}" target="_blank" class="text-blue-600 hover:underline text-sm">Aulete</a>
                        </div>
                    </div>
                `;
            }, 500);
        }

        function closeAllModals() {
            document.querySelectorAll('.word-modal, .definition-modal, .curiosity-modal').forEach(m => m.classList.remove('active'));
        }

        // ==================== CURIOSITIES ====================
        function renderCuriosities(category = 'all') {
            filteredCuriosities = category === 'all' ? [...curiosidades] : curiosidades.filter(c => c.category === category);
            document.getElementById('curiosityCount').textContent = filteredCuriosities.length;
            
            const categoryLabels = { 'words': '📝 Palavras', 'grammar': '✏️ Gramática', 'origin': '🌍 Origens', 'history': '📚 História', 'fun': '⭐ Diversas' };
            
            document.getElementById('curiositiesContainer').innerHTML = filteredCuriosities.map((cur, index) => `
                <div class="curiosity-item bg-white rounded-xl p-4 border-2 border-amber-100 hover:border-amber-300" data-index="${index}">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">${cur.emoji}</span>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-amber-500 font-medium">#${cur.id}</span>
                                <span class="text-xs bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full">${categoryLabels[cur.category] || cur.category}</span>
                            </div>
                            <h4 class="font-semibold text-gray-800">${cur.title}</h4>
                        </div>
                        <i class="fas fa-chevron-down chevron text-amber-400"></i>
                    </div>
                    <div class="curiosity-content">
                        <div class="border-t border-amber-100 pt-3 mt-3">
                            <p class="text-lg font-bold text-amber-700 mb-2">${cur.highlight}</p>
                            <p class="text-gray-600">${cur.content}</p>
                        </div>
                    </div>
                </div>
            `).join('');

            document.querySelectorAll('.curiosity-item').forEach(item => {
                item.addEventListener('click', () => item.classList.toggle('expanded'));
            });
        }

        function showRandomCuriosity() {
            currentCuriosityIndex = Math.floor(Math.random() * curiosidades.length);
            showCuriosityModal(currentCuriosityIndex);
        }

        function showCuriosityModal(index) {
            currentCuriosityIndex = index;
            const cur = curiosidades[index];
            document.getElementById('modalNumber').textContent = `Curiosidade #${cur.id}`;
            document.getElementById('modalTitle').innerHTML = `${cur.emoji} ${cur.title}`;
            document.getElementById('modalContent').innerHTML = `
                <p class="text-xl font-bold text-amber-700 mb-3">${cur.highlight}</p>
                <p class="text-gray-700 leading-relaxed">${cur.content}</p>
            `;
            document.getElementById('curiosityModal').classList.add('active');
        }

        function updateCuriosityStats() {
            const stats = { words: 0, grammar: 0, origin: 0, history: 0, fun: 0 };
            curiosidades.forEach(c => stats[c.category]++);
            document.getElementById('statWords').textContent = stats.words;
            document.getElementById('statGrammar').textContent = stats.grammar;
            document.getElementById('statOrigin').textContent = stats.origin;
            document.getElementById('statHistory').textContent = stats.history;
            document.getElementById('statFun').textContent = stats.fun;
        }

        // ==================== SEARCH & DISPLAY ====================
        function performSearch() {
            if (isLoading) return;

            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            currentPage = 1;

            filteredWords = palavras.filter(word => {
                let matchesFilter = true;
                
                if (query) {
                    switch (currentFilter) {
                        case 'starts': matchesFilter = word.startsWith(query); break;
                        case 'ends': matchesFilter = word.endsWith(query); break;
                        case 'contains': matchesFilter = word.includes(query); break;
                    }
                }

                if (selectedLength !== null) {
                    matchesFilter = matchesFilter && word.length === selectedLength;
                }

                if (selectedAccent !== null) {
                    matchesFilter = matchesFilter && getWordAccentType(word) === selectedAccent;
                }

                return matchesFilter;
            });

            filteredWords.sort((a, b) => a.localeCompare(b, 'pt-BR'));
            displayResults();
        }

        function displayResults() {
            const totalWords = filteredWords.length;
            const totalPages = Math.ceil(totalWords / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageWords = filteredWords.slice(start, end);

            document.getElementById('resultCount').textContent = `${totalWords.toLocaleString('pt-BR')} palavra${totalWords !== 1 ? 's' : ''}`;

            if (totalWords === 0) {
                document.getElementById('resultsContainer').innerHTML = '<p class="text-gray-500 italic">Nenhuma palavra encontrada com os filtros selecionados.</p>';
                document.getElementById('pagination').classList.add('hidden');
                document.getElementById('syllableTip').classList.add('hidden');
                return;
            }
            
            document.getElementById('syllableTip').classList.remove('hidden');

            document.getElementById('resultsContainer').innerHTML = pageWords.map(word => {
                const syllables = separateSyllables(word);
                const formatted = syllables.join('<span class="syllable-separator">·</span>');
                return `<span class="word-tag bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-800 px-4 py-2 rounded-full font-medium cursor-pointer hover:from-blue-100 hover:to-indigo-100" data-word="${word}">
                    ${word}
                    <span class="word-tooltip">${formatted} <span class="text-blue-300 ml-1">(${syllables.length} síl.)</span></span>
                </span>`;
            }).join('');
            
            document.querySelectorAll('.word-tag[data-word]').forEach(tag => {
                tag.addEventListener('click', () => showWordModal(tag.dataset.word));
            });

            if (totalPages > 1) {
                document.getElementById('pagination').classList.remove('hidden');
                document.getElementById('pageInfo').textContent = `Página ${currentPage} de ${totalPages}`;
            } else {
                document.getElementById('pagination').classList.add('hidden');
            }
        }

        // ==================== INITIALIZATION ====================
        function createAlphabet() {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
            document.getElementById('alphabetContainer').innerHTML = letters.map(letter => 
                `<button class="letter-btn w-10 h-10 rounded-lg border-2 border-gray-300 font-semibold text-gray-600 hover:border-blue-500 hover:text-blue-500 transition-all" data-letter="${letter.toLowerCase()}">${letter}</button>`
            ).join('');
        }

        function createLengthButtons() {
            let html = '<button class="length-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-green-500 transition-all" data-length="all">Todas</button>';
            for (let i = 2; i <= 22; i++) {
                html += `<button class="length-btn px-4 py-2 rounded-lg border-2 border-gray-300 font-medium text-gray-600 hover:border-green-500 transition-all" data-length="${i}">${i} letras</button>`;
            }
            document.getElementById('lengthContainer').innerHTML = html;
        }

        function setupEventListeners() {
            // Tab navigation
            document.querySelectorAll('.main-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.main-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    tab.classList.add('active');
                    document.getElementById(`tab-${tab.dataset.tab}`).classList.add('active');
                });
            });

            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentFilter = btn.dataset.filter;
                    const labels = { 'starts': 'Palavras que começam com:', 'ends': 'Palavras que terminam com:', 'contains': 'Palavras que contêm:' };
                    document.getElementById('searchLabel').textContent = labels[currentFilter];
                    if (document.getElementById('searchInput').value) performSearch();
                });
            });

            // Search
            document.getElementById('searchBtn').addEventListener('click', performSearch);
            document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });

            // Alphabet
            document.getElementById('alphabetContainer').addEventListener('click', e => {
                if (e.target.classList.contains('letter-btn')) {
                    document.querySelectorAll('.letter-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    document.getElementById('searchInput').value = e.target.dataset.letter;
                    performSearch();
                }
            });

            // Length
            document.getElementById('lengthContainer').addEventListener('click', e => {
                if (e.target.classList.contains('length-btn')) {
                    document.querySelectorAll('.length-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    selectedLength = e.target.dataset.length === 'all' ? null : parseInt(e.target.dataset.length);
                    performSearch();
                }
            });

            // Accent filter
            document.querySelectorAll('.accent-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.accent-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    selectedAccent = btn.dataset.accent === 'all' ? null : btn.dataset.accent;
                    performSearch();
                });
            });

            // Pagination
            document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; displayResults(); } });
            document.getElementById('nextPage').addEventListener('click', () => { 
                const totalPages = Math.ceil(filteredWords.length / itemsPerPage);
                if (currentPage < totalPages) { currentPage++; displayResults(); }
            });

            // Rhymes
            document.getElementById('rhymeBtn').addEventListener('click', () => {
                const word = document.getElementById('rhymeInput').value.trim();
                if (!word) return;
                
                const rhymes = findRhymes(word, rhymeType);
                document.getElementById('rhymeResults').innerHTML = rhymes.length > 0 
                    ? `<p class="mb-4 text-gray-600"><strong>${rhymes.length}</strong> palavras que rimam com "<strong>${word}</strong>":</p>
                       <div class="flex flex-wrap gap-2">${rhymes.map(w => 
                           `<span class="rhyme-tag px-3 py-1.5 rounded-full font-medium cursor-pointer hover:shadow-md transition-all" onclick="showWordModal('${w}')">${w}</span>`
                       ).join('')}</div>`
                    : '<p class="text-gray-500 italic">Nenhuma rima encontrada para essa palavra.</p>';
            });

            document.querySelectorAll('.rhyme-type-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.rhyme-type-btn').forEach(b => {
                        b.classList.remove('active', 'bg-pink-50', 'text-pink-700', 'border-pink-300');
                        b.classList.add('text-gray-600', 'border-gray-300');
                    });
                    btn.classList.add('active', 'bg-pink-50', 'text-pink-700', 'border-pink-300');
                    btn.classList.remove('text-gray-600', 'border-gray-300');
                    rhymeType = btn.dataset.type;
                });
            });

            // Advanced search
            document.getElementById('advancedSearchBtn').addEventListener('click', () => {
                const mustContain = document.getElementById('mustContainInput').value.trim();
                const exclude = document.getElementById('excludeLettersInput').value.trim();
                const pattern = document.getElementById('patternInput').value.trim();
                
                const results = advancedSearch(mustContain, exclude, pattern);
                document.getElementById('advancedResults').innerHTML = results.length > 0
                    ? `<p class="mb-4 text-gray-600"><strong>${results.length}</strong> palavras encontradas:</p>
                       <div class="flex flex-wrap gap-2">${results.slice(0, 200).map(w => 
                           `<span class="word-tag bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 text-indigo-800 px-3 py-1.5 rounded-full font-medium cursor-pointer" onclick="showWordModal('${w}')">${w}</span>`
                       ).join('')}</div>
                       ${results.length > 200 ? `<p class="mt-4 text-gray-500 text-sm">Mostrando 200 de ${results.length} resultados</p>` : ''}`
                    : '<p class="text-gray-500 italic">Nenhuma palavra encontrada com esses critérios.</p>';
            });

            // Text analyzer
            document.getElementById('analyzeTextBtn').addEventListener('click', () => {
                const text = document.getElementById('textAnalyzerInput').value.trim();
                if (!text) return;
                
                const analysis = analyzeText(text);
                document.getElementById('textAnalysisResults').classList.remove('hidden');
                document.getElementById('analysisTotalWords').textContent = analysis.totalWords;
                document.getElementById('analysisUniqueWords').textContent = analysis.uniqueWords;
                document.getElementById('analysisChars').textContent = analysis.chars;
                document.getElementById('analysisComplexity').textContent = analysis.complexity;
                
                document.getElementById('wordFrequencyList').innerHTML = analysis.topWords.map(([word, count]) => 
                    `<span class="inline-block bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium m-1">${word} <span class="text-emerald-600">(${count})</span></span>`
                ).join('');

                // Create text letter chart
                const sortedLetters = Object.entries(analysis.letterFreq).sort((a, b) => b[1] - a[1]).slice(0, 26);
                if (charts.textLetterChart) charts.textLetterChart.destroy();
                charts.textLetterChart = new Chart(document.getElementById('textLetterChart'), {
                    type: 'bar',
                    data: {
                        labels: sortedLetters.map(l => l[0].toUpperCase()),
                        datasets: [{
                            label: 'Frequência',
                            data: sortedLetters.map(l => l[1]),
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });
            });

            // Curiosities
            document.getElementById('floatingCuriosityBtn').addEventListener('click', showRandomCuriosity);
            document.getElementById('randomCuriosityBtn').addEventListener('click', showRandomCuriosity);
            document.getElementById('shuffleCuriositiesBtn').addEventListener('click', () => {
                const shuffled = [...curiosidades].sort(() => Math.random() - 0.5);
                filteredCuriosities = shuffled;
                renderCuriosities('all');
            });
            
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    renderCuriosities(tab.dataset.category);
                });
            });

            document.getElementById('nextCuriosity').addEventListener('click', () => {
                currentCuriosityIndex = (currentCuriosityIndex + 1) % curiosidades.length;
                showCuriosityModal(currentCuriosityIndex);
            });
            document.getElementById('prevCuriosity').addEventListener('click', () => {
                currentCuriosityIndex = (currentCuriosityIndex - 1 + curiosidades.length) % curiosidades.length;
                showCuriosityModal(currentCuriosityIndex);
            });

            // Modal buttons
            document.getElementById('findRhymesBtn').addEventListener('click', () => {
                closeAllModals();
                document.querySelector('[data-tab="rhymes"]').click();
                document.getElementById('rhymeInput').value = currentWordForModal;
                document.getElementById('rhymeBtn').click();
            });

            document.getElementById('findDefinitionBtn').addEventListener('click', () => {
                showDefinitionModal(currentWordForModal);
            });

            // Close modals
            document.querySelectorAll('.closeModal').forEach(btn => {
                btn.addEventListener('click', closeAllModals);
            });
            document.querySelectorAll('.word-modal, .definition-modal, .curiosity-modal').forEach(modal => {
                modal.addEventListener('click', e => { if (e.target === modal) closeAllModals(); });
            });

            // Keyboard
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeAllModals();
            });
        }

        async function init() {
            createAlphabet();
            createLengthButtons();
            setupEventListeners();
            updateCuriosityStats();
            renderCuriosities();
            await loadDictionary();
        }

        init();

        // PWA Install Prompt & Service Worker Registration
        let deferredPrompt = null;
        const btnInstallApp = document.getElementById('btnInstallApp');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (btnInstallApp) {
                btnInstallApp.style.display = 'inline-flex';
            }
        });

        if (btnInstallApp) {
            btnInstallApp.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        btnInstallApp.style.display = 'none';
                    }
                    deferredPrompt = null;
                }
            });
        }

        window.addEventListener('appinstalled', () => {
            if (btnInstallApp) btnInstallApp.style.display = 'none';
            deferredPrompt = null;
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('./sw.js').catch((err) => {
                    console.warn('[PWA] Falha no registro do Service Worker:', err);
                });
            });
        }
    </script>
</body>
</html>
