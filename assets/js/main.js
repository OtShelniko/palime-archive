/* Palime Archive — main.js */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        Palime.nav.init();
        Palime.section.init();
        Palime.subscribe.init();
        Palime.save.init();
        Palime.share.init();
    });

    window.Palime = {

        data: window.palimeData || {},

        // ---------------------------------------------------------
        // НАВИГАЦИЯ — бургер + мобильное меню
        // ---------------------------------------------------------
        nav: {
            init() {
                const burger  = document.getElementById('burger-btn');
                const menu    = document.getElementById('mobile-menu');
                if (!burger || !menu) return;

                burger.addEventListener('click', () => {
                    const open = menu.classList.toggle('open');
                    burger.classList.toggle('active', open);
                    burger.setAttribute('aria-expanded', open);
                    menu.setAttribute('aria-hidden', !open);
                    document.body.style.overflow = open ? 'hidden' : '';
                });

                // Закрыть при клике на пункт меню
                menu.querySelectorAll('.nav__link').forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.remove('open');
                        burger.classList.remove('active');
                        burger.setAttribute('aria-expanded', false);
                        menu.setAttribute('aria-hidden', true);
                        document.body.style.overflow = '';
                    });
                });

                // Закрыть при клике вне
                document.addEventListener('click', (e) => {
                    if (!burger.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.remove('open');
                        burger.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        },

        // ---------------------------------------------------------
        // ПЕРЕКЛЮЧЕНИЕ АКЦЕНТА РАЗДЕЛА
        // ---------------------------------------------------------
        section: {
            init() {
                document.querySelectorAll('[data-go-section]').forEach(el => {
                    el.addEventListener('click', () => {
                        document.body.dataset.section = el.dataset.goSection;
                    });
                });
            }
        },

        // ---------------------------------------------------------
        // ФОРМА ПОДПИСКИ
        // ---------------------------------------------------------
        subscribe: {
            init() {
                document.querySelectorAll('.subscribe-form').forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.handle(form);
                    });
                });
            },
            handle(form) {
                const input = form.querySelector('input[type="email"]');
                const btn   = form.querySelector('button');
                const email = input?.value?.trim();
                if (!email) return;
                btn.disabled = true;
                btn.textContent = '...';
                Palime.ajax('palime_subscribe', { email })
                    .then(res => {
                        if (res.success) {
                            form.innerHTML = '<p class="text-mono text-sm">Вы подписаны — спасибо!</p>';
                        } else {
                            btn.disabled = false;
                            btn.textContent = '→';
                            Palime.notice(form, res.data?.message || 'Ошибка', 'error');
                        }
                    });
            }
        },

        // ---------------------------------------------------------
        // СОХРАНЕНИЕ СТАТЕЙ В ЗАКЛАДКИ
        // ---------------------------------------------------------
        save: {
            init() {
                document.querySelectorAll('[data-save-article]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (!Palime.data.userId) { window.location.href = '/wp-login.php'; return; }
                        this.toggle(btn, btn.dataset.saveArticle);
                    });
                });
            },
            toggle(btn, articleId) {
                btn.disabled = true;
                Palime.ajax('palime_toggle_save', { article_id: articleId })
                    .then(res => {
                        if (res.success) {
                            btn.classList.toggle('active', res.data.action === 'saved');
                        }
                        btn.disabled = false;
                    });
            }
        },

        // ---------------------------------------------------------
        // ШЕРИНГ
        // ---------------------------------------------------------
        share: {
            init() {
                document.querySelectorAll('[data-share]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const url   = btn.dataset.share || window.location.href;
                        const title = btn.dataset.shareTitle || document.title;
                        if (navigator.share) {
                            navigator.share({ title, url }).then(() => this.track(btn.dataset.shareArticle));
                        } else {
                            navigator.clipboard?.writeText(url);
                            Palime.notice(document.body, 'Ссылка скопирована');
                            this.track(btn.dataset.shareArticle);
                        }
                    });
                });
            },
            track(articleId) {
                if (!articleId || !Palime.data.userId) return;
                Palime.ajax('palime_track_share', { article_id: articleId });
            }
        },

        // =========================================================
        // УТИЛИТЫ
        // =========================================================

        ajax(action, data = {}) {
            const body = new FormData();
            body.append('action', action);
            body.append('nonce', Palime.data.nonce || '');
            Object.entries(data).forEach(([k, v]) => body.append(k, v));
            return fetch(Palime.data.ajaxUrl, { method: 'POST', body })
                .then(r => r.json())
                .catch(err => { console.error('Palime AJAX:', err); return { success: false }; });
        },

        notice(target, message, type = 'success') {
            const el = document.createElement('div');
            el.className = `notice notice--${type} fade-in`;
            el.textContent = message;
            document.body.appendChild(el);
            Object.assign(el.style, { position:'fixed', bottom:'24px', right:'24px', zIndex:9999 });
            setTimeout(() => el.remove(), 3000);
        },

        debounce(fn, delay = 300) {
            let timer;
            return (...args) => { clearTimeout(timer); timer = setTimeout(() => fn(...args), delay); };
        }
    };

})();
