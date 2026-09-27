<?php
declare(strict_types=1);

$msgEnviada = false;
$msgErro = '';
$currentYear = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'duvida');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($nome === '' || $email === '' || $mensagem === '') {
        $msgErro = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgErro = 'Por favor, informe um endereço de e-mail válido.';
    } else {
        $logDir = __DIR__ . '/uploads';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/messages_log.json';
        $novoRegistro = [
            'data' => date('c'),
            'nome' => htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            'categoria' => htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'),
            'mensagem' => htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];

        $registros = [];
        if (file_exists($logFile)) {
            $conteudo = file_get_contents($logFile);
            if ($conteudo) {
                $registros = json_decode($conteudo, true) ?? [];
            }
        }
        $registros[] = $novoRegistro;
        @file_put_contents($logFile, json_encode($registros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Envio de email para contato@4u.ia.br
        $to = 'contato@4u.ia.br';
        $subject = "[Suporte Palavreado] {$categoria}: {$nome}";
        $body = "Mensagem recebida pelo suporte do Palavreado:\n\n";
        $body .= "Nome: {$nome}\n";
        $body .= "Email: {$email}\n";
        $body .= "Categoria: {$categoria}\n";
        $body .= "Data: " . date('d/m/Y H:i:s') . "\n\n";
        $body .= "Mensagem:\n{$mensagem}\n";
        $headers = "From: Palavreado <noreply@4u.ia.br>\r\n" .
                   "Reply-To: {$email}\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        @mail($to, $subject, $body, $headers);
        $msgEnviada = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte & Ajuda | Support & FAQ — Palavreado 4U.IA.BR</title>
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
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .faq-answer.open { max-height: 500px; }
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

        <!-- Main Card -->
        <main class="glass-card rounded-3xl p-6 md:p-10 space-y-10">
            <!-- Header Text -->
            <div class="border-b border-white/10 pb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold mb-3">
                    <i class="fas fa-headset"></i>
                    <span data-i18n-pt>Central de Atendimento & Dúvidas</span>
                    <span data-i18n-en>Help Center & Inquiries</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-pt>Suporte & Perguntas Frequentes</h2>
                <h2 class="text-2xl md:text-3xl font-extrabold" data-i18n-en>Support & Frequently Asked Questions</h2>
                <p class="text-sm text-gray-400 mt-2" data-i18n-pt>Tire suas dúvidas ou envie sugestões de melhoria para o time de desenvolvimento da 4U.IA.BR.</p>
                <p class="text-sm text-gray-400 mt-2" data-i18n-en>Get answers to common questions or reach out directly to the 4U.IA.BR engineering team.</p>
            </div>

            <!-- Feedback Alert -->
            <?php if ($msgEnviada): ?>
            <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span data-i18n-pt>Mensagem enviada com sucesso! Responderemos em breve no seu e-mail.</span>
                <span data-i18n-en>Message sent successfully! We will get back to your email shortly.</span>
            </div>
            <?php elseif ($msgErro !== ''): ?>
            <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-300 text-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-xl"></i>
                <span><?= htmlspecialchars($msgErro, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <?php endif; ?>

            <!-- Interactive FAQ Accordion -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-question-circle text-blue-400"></i>
                    <span data-i18n-pt>Perguntas Frequentes</span>
                    <span data-i18n-en>FAQ</span>
                </h3>

                <!-- FAQ 1 -->
                <div class="glass rounded-2xl p-4 transition-all cursor-pointer" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between font-semibold text-sm md:text-base">
                        <span data-i18n-pt>1. O Palavreado funciona sem internet?</span>
                        <span data-i18n-en>1. Does Palavreado work offline?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer text-sm text-gray-300 pt-3 leading-relaxed">
                        <p data-i18n-pt>Sim! O Palavreado possui um dicionário local integrado com mais de 260.000 palavras em português e um Service Worker ativo. Uma vez carregado, ele pode ser instalado como PWA e funciona perfeitamente mesmo no modo avião.</p>
                        <p data-i18n-en>Yes! Palavreado contains an embedded local lexicon of 260,000+ words and a Service Worker cache. Once opened, you can install it as a PWA and use it completely offline.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="glass rounded-2xl p-4 transition-all cursor-pointer" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between font-semibold text-sm md:text-base">
                        <span data-i18n-pt>2. As palavras digitadas ou analisadas são enviadas para algum servidor?</span>
                        <span data-i18n-en>2. Are my typed texts or searched words saved on a server?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer text-sm text-gray-300 pt-3 leading-relaxed">
                        <p data-i18n-pt>Não. O processamento é 100% Client-Side no seu navegador. Nem textos para análise nem consultas de palavras saem do seu computador ou celular.</p>
                        <p data-i18n-en>No. All processing happens 100% client-side in your device memory. Neither text submissions nor word queries ever leave your machine.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="glass rounded-2xl p-4 transition-all cursor-pointer" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between font-semibold text-sm md:text-base">
                        <span data-i18n-pt>3. Como instalar o Palavreado no meu celular ou PC?</span>
                        <span data-i18n-en>3. How do I install Palavreado on mobile or desktop?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer text-sm text-gray-300 pt-3 leading-relaxed">
                        <p data-i18n-pt>Basta clicar no botão "Instalar App" no topo da página ou, no menu do seu navegador (três pontinhos), selecionar "Adicionar à Tela Inicial" ou "Instalar Aplicativo".</p>
                        <p data-i18n-en>Simply tap the "Install App" button in the header, or select "Add to Home Screen" / "Install App" from your browser's options menu.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="glass rounded-2xl p-4 transition-all cursor-pointer" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between font-semibold text-sm md:text-base">
                        <span data-i18n-pt>4. O aplicativo é gratuito para uso em escolas e projetos?</span>
                        <span data-i18n-en>4. Is the application free for educational use?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer text-sm text-gray-300 pt-3 leading-relaxed">
                        <p data-i18n-pt>Sim, 100% gratuito e livre para uso pessoal, pedagógico em escolas, oficinas literárias e projetos de escrita.</p>
                        <p data-i18n-en>Yes, 100% free and open for personal, academic, and school classroom usage.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="border-t border-white/10 pt-8 space-y-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-envelope text-blue-400"></i>
                    <span data-i18n-pt>Envie uma Mensagem</span>
                    <span data-i18n-en>Send us a Message</span>
                </h3>

                <form method="POST" action="suporte.php" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-pt>Seu Nome *</label>
                            <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-en>Your Name *</label>
                            <input type="text" name="nome" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="Ex: Ana Silva">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-pt>Seu E-mail *</label>
                            <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-en>Your Email *</label>
                            <input type="email" name="email" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="seuemail@exemplo.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-pt>Assunto / Categoria *</label>
                        <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-en>Subject / Category *</label>
                        <select name="categoria" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors">
                            <option value="duvida" data-i18n-pt>Dúvida sobre o aplicativo</option>
                            <option value="sugestao" data-i18n-pt>Sugestão de nova funcionalidade</option>
                            <option value="bug" data-i18n-pt>Relato de erro ou problema</option>
                            <option value="parceria" data-i18n-pt>Outro assunto / Parceria</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-pt>Mensagem *</label>
                        <label class="block text-xs font-semibold text-gray-300 mb-1" data-i18n-en>Message *</label>
                        <textarea name="mensagem" rows="4" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="Descreva sua dúvida, sugestão ou feedback..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-95 font-bold text-white text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        <span data-i18n-pt>Enviar Mensagem</span>
                        <span data-i18n-en>Send Message</span>
                    </button>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="glass rounded-2xl p-6 text-center text-xs text-gray-400 space-y-3">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="termos.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Termos de Uso</a>
                <a href="termos.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Terms of Service</a>
                <span>•</span>
                <a href="privacidade.php" class="hover:text-blue-400 transition-colors" data-i18n-pt>Privacidade</a>
                <a href="privacidade.php" class="hover:text-blue-400 transition-colors" data-i18n-en>Privacy Policy</a>
                <span>•</span>
                <a href="suporte.php" class="text-blue-400 font-bold" data-i18n-pt>Suporte & Ajuda</a>
                <a href="suporte.php" class="text-blue-400 font-bold" data-i18n-en>Support & FAQ</a>
            </div>
            <p>© <?= $currentYear ?> 4U.IA.BR • Palavreado. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script>
        function toggleFaq(card) {
            const answer = card.querySelector('.faq-answer');
            const icon = card.querySelector('.fa-chevron-down');
            const isOpen = answer.classList.contains('open');
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
            document.querySelectorAll('.fa-chevron-down').forEach(i => i.style.transform = 'rotate(0deg)');
            if (!isOpen) {
                answer.classList.add('open');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        }

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
