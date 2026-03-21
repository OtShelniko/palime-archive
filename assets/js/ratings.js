/* Palime Archive — ratings.js */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        PalimeRatings.init();
    });

    window.PalimeRatings = {

        // =========================================================
        // ИНИЦИАЛИЗАЦИЯ
        // =========================================================

        init() {
            this.bindVoteButtons();
        },

        // =========================================================
        // ГОЛОСОВАНИЕ
        // =========================================================

        bindVoteButtons() {
            document.querySelectorAll('.rating__vote-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!Palime.data.userId) {
                        this.showAuthPrompt(btn);
                        return;
                    }

                    const rankingId = btn.dataset.rankingId;
                    const itemId    = btn.dataset.itemId;
                    if (!rankingId || !itemId) return;

                    this.vote(btn, rankingId, itemId);
                });
            });
        },

        vote(btn, rankingId, itemId) {
            // Блокируем все кнопки рейтинга на время запроса
            const block = btn.closest('.rating');
            block?.querySelectorAll('.rating__vote-btn').forEach(b => b.disabled = true);

            const body = new FormData();
            body.append('action',     'palime_vote');
            body.append('nonce',      Palime.data.voteNonce);
            body.append('ranking_id', rankingId);
            body.append('item_id',    itemId);

            fetch(Palime.data.ajaxUrl, { method: 'POST', body })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        this.onVoteSuccess(btn, itemId, res.data.votes);
                    } else {
                        this.onVoteError(btn, block, res.data?.message);
                    }
                })
                .catch(() => {
                    block?.querySelectorAll('.rating__vote-btn').forEach(b => b.disabled = false);
                });
        },

        onVoteSuccess(btn, itemId, newCount) {
            const block = btn.closest('.rating');

            // Помечаем проголосованный элемент
            block?.querySelectorAll('.rating__item').forEach(item => {
                const voted = item.dataset.itemId === itemId;
                item.classList.toggle('voted', voted);
            });

            // Обновляем счётчик
            const counter = btn.closest('.rating__item')?.querySelector('.rating__count');
            if (counter) counter.textContent = newCount;

            // Скрываем все кнопки голосования — уже проголосовали
            block?.querySelectorAll('.rating__vote-btn').forEach(b => {
                b.style.display = 'none';
            });

            // Показываем подтверждение
            this.showThankYou(block);
        },

        onVoteError(btn, block, message) {
            block?.querySelectorAll('.rating__vote-btn').forEach(b => b.disabled = false);

            // Если уже голосовал — просто помечаем
            if (message === 'Вы уже проголосовали') {
                block?.querySelectorAll('.rating__vote-btn').forEach(b => {
                    b.style.display = 'none';
                });
                this.showAlreadyVoted(block);
            }
        },

        showThankYou(block) {
            if (!block) return;
            const el = document.createElement('p');
            el.className = 'rating__feedback text-mono text-sm fade-in';
            el.textContent = 'Голос учтён +10 очков';
            el.style.color = 'var(--accent)';
            block.appendChild(el);
            setTimeout(() => el.remove(), 4000);
        },

        showAlreadyVoted(block) {
            if (!block) return;
            const el = document.createElement('p');
            el.className = 'rating__feedback text-mono text-sm';
            el.textContent = 'Вы уже голосовали';
            el.style.opacity = '0.5';
            block.appendChild(el);
        },

        showAuthPrompt(btn) {
            const prompt = document.createElement('div');
            prompt.className = 'notice fade-in';
            prompt.innerHTML = 'Чтобы голосовать, <a href="/wp-login.php">войдите</a>';
            btn.closest('.rating__item')?.insertAdjacentElement('afterend', prompt);
            setTimeout(() => prompt.remove(), 4000);
        },

        // =========================================================
        // РЕДАКЦИОННЫЙ РЕЙТИНГ — анимация баров
        // =========================================================

        animateBars() {
            document.querySelectorAll('.rating__bar-fill').forEach(bar => {
                const target = bar.dataset.percent || '0';
                bar.style.width = '0%';
                // Даём браузеру применить нулевую ширину, потом анимируем
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        bar.style.transition = 'width 0.8s ease';
                        bar.style.width = target + '%';
                    });
                });
            });
        },
    };

    // Запускаем анимацию баров после загрузки
    document.addEventListener('DOMContentLoaded', () => {
        PalimeRatings.animateBars();
    });

})();
