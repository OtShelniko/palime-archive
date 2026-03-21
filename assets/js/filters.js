/* Palime Archive — filters.js */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        PalimeFilters.init();
    });

    window.PalimeFilters = {

        state: {
            section: '',
            person:  '',
            era:     '',
            genre:   '',
            page:    1,
            loading: false,
        },

        grid:    null,
        count:   null,
        filters: null,

        // =========================================================
        // ИНИЦИАЛИЗАЦИЯ
        // =========================================================

        init() {
            this.grid    = document.querySelector('.archive-results__grid');
            this.count   = document.querySelector('.archive-results__count');
            this.filters = document.querySelector('.archive-filters');

            if (!this.grid || !this.filters) return;

            this.bindSelects();
            this.bindPersonAutocomplete();
            this.bindReset();
            this.bindPagination();

            // Первая загрузка
            this.fetch();
        },

        // =========================================================
        // БИНДИНГИ
        // =========================================================

        bindSelects() {
            ['section', 'era', 'genre'].forEach(key => {
                const el = this.filters.querySelector(`[data-filter="${key}"]`);
                if (!el) return;

                el.addEventListener('change', () => {
                    this.state[key] = el.value;
                    this.state.page = 1;
                    this.updateActiveTags();
                    this.fetch();
                });
            });
        },

        bindPersonAutocomplete() {
            const input       = this.filters.querySelector('.archive-filters__person-input');
            const suggestions = this.filters.querySelector('.archive-filters__suggestions');
            if (!input || !suggestions) return;

            const search = Palime.debounce((q) => {
                if (q.length < 2) { suggestions.classList.remove('open'); return; }

                fetch(`${Palime.data.restUrl}palime/v1/persons?search=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        suggestions.innerHTML = '';
                        if (!data.length) { suggestions.classList.remove('open'); return; }

                        data.forEach(person => {
                            const item = document.createElement('div');
                            item.className = 'archive-filters__suggestion';
                            item.textContent = person.name;
                            item.addEventListener('click', () => {
                                this.state.person = person.slug;
                                this.state.page   = 1;
                                input.value = person.name;
                                suggestions.classList.remove('open');
                                this.updateActiveTags();
                                this.fetch();
                            });
                            suggestions.appendChild(item);
                        });

                        suggestions.classList.add('open');
                    });
            }, 300);

            input.addEventListener('input', () => search(input.value));

            // Закрыть при клике вне
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.classList.remove('open');
                }
            });
        },

        bindReset() {
            const btn = this.filters.querySelector('.archive-filters__reset');
            if (!btn) return;

            btn.addEventListener('click', () => {
                this.state = { section: '', person: '', era: '', genre: '', page: 1, loading: false };

                // Сбросить select-ы
                this.filters.querySelectorAll('[data-filter]').forEach(el => el.value = '');

                // Сбросить autocomplete
                const personInput = this.filters.querySelector('.archive-filters__person-input');
                if (personInput) personInput.value = '';

                this.updateActiveTags();
                this.fetch();
            });
        },

        bindPagination() {
            // Делегирование — кнопки появляются динамически
            this.grid.addEventListener('click', (e) => {
                const btn = e.target.closest('.pagination__item');
                if (!btn) return;

                const page = parseInt(btn.dataset.page);
                if (!page || page === this.state.page) return;

                this.state.page = page;
                this.fetch();
                window.scrollTo({ top: this.grid.offsetTop - 100, behavior: 'smooth' });
            });
        },

        // =========================================================
        // ЗАПРОС К API
        // =========================================================

        fetch() {
            if (this.state.loading) return;
            this.state.loading = true;
            this.grid.classList.add('loading');

            const params = new URLSearchParams({
                action:  'palime_filter_archive',
                nonce:   Palime.data.nonce,
                section: this.state.section,
                person:  this.state.person,
                era:     this.state.era,
                genre:   this.state.genre,
                paged:   this.state.page,
            });

            fetch(Palime.data.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString(),
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        this.renderPosts(res.data.posts);
                        this.renderPagination(res.data.max_pages);
                        this.updateCount(res.data.total);
                    }
                })
                .finally(() => {
                    this.state.loading = false;
                    this.grid.classList.remove('loading');
                });
        },

        // =========================================================
        // РЕНДЕР
        // =========================================================

        renderPosts(posts) {
            if (!posts.length) {
                this.grid.innerHTML = '<div class="archive-empty">Ничего не найдено</div>';
                return;
            }

            this.grid.innerHTML = `
                <div class="grid--cards fade-in">
                    ${posts.map(p => this.cardHTML(p)).join('')}
                </div>`;
        },

        cardHTML(post) {
            const thumb = post.thumbnail
                ? `<img src="${post.thumbnail}" alt="${this.esc(post.title)}" loading="lazy">`
                : `<div style="background:var(--color-second);width:100%;height:100%"></div>`;

            return `
                <article class="card">
                    <a href="${post.url}" class="card__image">${thumb}</a>
                    <div class="card__body">
                        <div class="card__meta">
                            <span>${post.date}</span>
                        </div>
                        <h3 class="card__title">
                            <a href="${post.url}">${this.esc(post.title)}</a>
                        </h3>
                        <p class="card__excerpt">${this.esc(post.excerpt)}</p>
                    </div>
                </article>`;
        },

        renderPagination(maxPages) {
            const current = this.state.page;
            if (maxPages <= 1) {
                const old = this.grid.nextElementSibling;
                if (old?.classList.contains('pagination')) old.remove();
                return;
            }

            let html = '<nav class="pagination">';
            for (let i = 1; i <= maxPages; i++) {
                html += `<button class="pagination__item${i === current ? ' active' : ''}" data-page="${i}">${i}</button>`;
            }
            html += '</nav>';

            const old = this.grid.nextElementSibling;
            if (old?.classList.contains('pagination')) old.remove();
            this.grid.insertAdjacentHTML('afterend', html);
        },

        updateCount(total) {
            if (this.count) {
                this.count.textContent = `${total} материал${this.plural(total)}`;
            }
        },

        updateActiveTags() {
            const container = document.querySelector('.archive-active-filters');
            if (!container) return;

            container.innerHTML = '';
            const labels = { section: 'Раздел', person: 'Персона', era: 'Эпоха', genre: 'Жанр' };

            Object.entries(labels).forEach(([key, label]) => {
                if (!this.state[key]) return;

                const tag = document.createElement('span');
                tag.className = 'archive-active-filter';
                tag.innerHTML = `${label}: ${this.esc(this.state[key])}
                    <span class="archive-active-filter__remove" data-remove="${key}">×</span>`;

                tag.querySelector('[data-remove]').addEventListener('click', () => {
                    this.state[key] = '';
                    this.state.page = 1;

                    const sel = this.filters.querySelector(`[data-filter="${key}"]`);
                    if (sel) sel.value = '';

                    if (key === 'person') {
                        const inp = this.filters.querySelector('.archive-filters__person-input');
                        if (inp) inp.value = '';
                    }

                    this.updateActiveTags();
                    this.fetch();
                });

                container.appendChild(tag);
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

        plural(n) {
            if (n % 10 === 1 && n % 100 !== 11) return '';
            if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) return 'а';
            return 'ов';
        },
    };

})();
