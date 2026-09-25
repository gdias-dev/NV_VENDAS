(function () {
    'use strict';

    // Menu mobile
    var btn = document.getElementById('menu-toggle');
    var menu = document.getElementById('menu-mobile');
    if (btn && menu) {
        btn.addEventListener('click', function () {
            var aberto = menu.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        });
    }

    // Revelar ao rolar (progressivo: sem JS o conteúdo já aparece)
    var itens = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && itens.length) {
        var io = new IntersectionObserver(function (entradas) {
            entradas.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        itens.forEach(function (el) { io.observe(el); });
    } else {
        itens.forEach(function (el) { el.classList.add('is-visible'); });
    }

    // Botão de enviar do contato: evita duplo clique
    var form = document.getElementById('form-contato');
    if (form) {
        form.addEventListener('submit', function () {
            var b = form.querySelector('button[type=submit]');
            if (b) { b.disabled = true; b.textContent = 'Enviando...'; }
        });
    }
})();
