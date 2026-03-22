/* Palime Archive — profile.js */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        PalimeProfile.init();
    });

    window.PalimeProfile = {

        // =========================================================
        // ИНИЦИАЛИЗАЦИЯ
        // =========================================================

        init() {
            this.bindTabs();
            this.animateProgress();
            this.bindLogout();
            this.bindSettingsForm();
            this.bindRemoveSaved();
        },

        // =========================================================
        // ТАБЫ ПРОФИЛЯ
        // =========================================================

        bindTabs() {
            const navItems = document.querySelectorAll('.profile-nav__item');
            const sections = document.querySelectorAll('.profile-content__section');

            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    const target = item.dataset.tab;

                    navItems.forEach(i => i.classList.remove('active'));
                    sections.forEach(s => s.classList.remove('active'));

                    item.classList.add('active');
                    document.querySelector(`.profile-content__section[data-section="${target}"]`)
                        ?.classList.add('active');
                });
            });
        },

        // =========================================================
        // АНИМАЦИЯ ПРОГРЕСС-БАРА
        // =========================================================

        animateProgress() {
            const fill = document.querySelector('.profile-progress__fill');
            if (!fill) return;

            const target = fill.dataset.percent || '0';
            fill.style.width = '0%';

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    fill.style.transition = 'width 1s ease';
                    fill.style.width = target + '%';
                });
            });
        },

        // =========================================================
        // ФОРМА НАСТРОЕК
        // =========================================================

        bindSettingsForm() {
            const form = document.getElementById('profile-settings-form');
            if (!form) return;

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                const data = {
                    display_name: formData.get('display_name'),
                };

                fetch(`${palimeData.restBase}palime/v1/profile/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': palimeData.nonce,
                    },
                    body: JSON.stringify(data),
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            this.showNotice(form, 'Сохранено');
                        }
                    })
                    .catch(() => {
                        this.showNotice(form, 'Ошибка сохранения', true);
                    });
            });
        },

        // =========================================================
        // УДАЛЕНИЕ СОХРАНЁННЫХ СТАТЕЙ
        // =========================================================

        bindRemoveSaved() {
            document.querySelectorAll('.saved-article__remove').forEach(btn => {
                btn.addEventListener('click', () => {
                    const articleId = btn.dataset.articleId;
                    if (!articleId) return;

                    const body = new FormData();
                    body.append('action', 'palime_toggle_save');
                    body.append('nonce', palimeData.nonce);
                    body.append('article_id', articleId);

                    fetch(palimeData.ajaxUrl, { method: 'POST', body })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                const item = btn.closest('.saved-article');
                                if (item) {
                                    item.style.opacity = '0';
                                    item.style.transition = 'opacity 0.3s ease';
                                    setTimeout(() => item.remove(), 300);
                                }
                            }
                        });
                });
            });
        },

        // =========================================================
        // ВЫХОД
        // =========================================================

        bindLogout() {
            const btn = document.querySelector('[data-logout]');
            if (!btn) return;

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = btn.dataset.logout;
            });
        },

        // =========================================================
        // УТИЛИТЫ
        // =========================================================

        showNotice(container, message, isError) {
            const existing = container.querySelector('.profile-notice');
            if (existing) existing.remove();

            const el = document.createElement('div');
            el.className = 'profile-notice' + (isError ? ' profile-notice--error' : '');
            el.textContent = message;
            container.appendChild(el);
            setTimeout(() => el.remove(), 3000);
        },
    };

})();
