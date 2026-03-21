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
            this.loadProfileData();
            this.animateProgress();
            this.bindLogout();
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

            // Открыть первый таб по умолчанию
            navItems[0]?.click();
        },

        // =========================================================
        // ЗАГРУЗКА ДАННЫХ ПРОФИЛЯ
        // =========================================================

        loadProfileData() {
            if (!Palime.data.userId) return;

            fetch(`${Palime.data.restUrl}palime/v1/profile`, {
                headers: {
                    'X-WP-Nonce': Palime.data.nonce,
                }
            })
                .then(r => r.json())
                .then(data => {
                    this.renderPoints(data.points, data.level, data.progress);
                    this.renderLog(data.log);
                })
                .catch(err => console.error('Profile load error:', err));
        },

        renderPoints(points, level, progress) {
            // Очки
            const pointsEl = document.querySelector('.profile-level__points');
            if (pointsEl) pointsEl.textContent = points;

            // Бейдж уровня
            const badge = document.querySelector('.profile-level__badge');
            if (badge) badge.textContent = level?.name || '';

            // Прогресс-бар
            const fill = document.querySelector('.profile-progress__fill');
            if (fill && progress) {
                fill.dataset.percent = progress.percent;
            }

            // Лейбл прогресса
            const progressLabel = document.querySelector('.profile-progress__label');
            if (progressLabel && progress?.next_name) {
                progressLabel.textContent = `До уровня ${progress.next_name}: ${progress.next_min - progress.current} очков`;
            } else if (progressLabel) {
                progressLabel.textContent = 'Максимальный уровень';
            }
        },

        renderLog(log) {
            const container = document.querySelector('.points-log');
            if (!container || !log?.length) return;

            container.innerHTML = log.map(entry => `
                <div class="points-log__item">
                    <span>${this.esc(entry.reason || 'Действие')}</span>
                    <span class="points-log__date">${this.formatDate(entry.date)}</span>
                    <span class="points-log__amount">${entry.amount}</span>
                </div>
            `).join('');
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

        esc(str) {
            const d = document.createElement('div');
            d.textContent = str || '';
            return d.innerHTML;
        },

        formatDate(mysqlDate) {
            if (!mysqlDate) return '';
            const d = new Date(mysqlDate.replace(' ', 'T'));
            return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' });
        },
    };

})();
