    document.getElementById('emailLogin').focus();

    const form   = document.getElementById('formLogin');
    const btn      = document.getElementById('btnLogin');
    const feedback = document.getElementById('feedbackLogin');

    function validarCampos() {
        const email = document.getElementById('emailLogin');
        const senha = document.getElementById('senhaLogin');

        [email, senha].forEach(el => el.style.boxShadow = '');

        if (email.value.trim() === '') {
            email.style.boxShadow = '0 0 5px #ffc107';
            email.focus();
            return 'Por favor, preencha o campo de e-mail.';
        }
        if (senha.value.trim() === '') {
            senha.style.boxShadow = '0 0 5px #ffc107';
            senha.focus();
            return 'Por favor, preencha o campo de senha.';
        }
        return null;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        feedback.style.display = 'none';

        const erroLocal = validarCampos();
        if (erroLocal) {
            feedback.className = 'feedback erro';
            feedback.innerHTML = '<span class="icone">⚠️</span>' + erroLocal;
            feedback.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Entrando…';

        try {
            const resposta = await fetch('autenticar.php', {
                method: 'POST',
                body: new FormData(form)
            });

            const json = await resposta.json();

            if (json.sucesso) {
                feedback.className = 'feedback sucesso';
                feedback.innerHTML =
                    '<span class="icone">✅</span>' +
                    '<strong>' + json.mensagem + '</strong>' +
                    '<br><small>Olá, <strong>' + json.usuario.nome + '</strong>!</small>';
                form.reset();
            } else {
                const erros = json.erros
                    ? json.erros.map(e => '<li>' + e + '</li>').join('')
                    : '<li>' + (json.erro || 'Erro desconhecido.') + '</li>';
                feedback.className = 'feedback erro';
                feedback.innerHTML =
                    '<span class="icone">❌</span>' +
                    '<strong>Não foi possível entrar:</strong>' +
                    '<ul style="margin:0.4rem 0 0 1rem;padding:0">' + erros + '</ul>';
            }

        } catch (_) {
            feedback.className = 'feedback erro';
            feedback.innerHTML = '<span class="icone">⚠️</span>Falha na comunicação com o servidor.';
        } finally {
            feedback.style.display = 'block';
            btn.disabled = false;
            btn.textContent = 'Entrar';
        }
    });
