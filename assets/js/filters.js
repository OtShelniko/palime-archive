/* Palime Archive — filters.js
   AJAX-фильтры страницы архива /archive
   ================================================== */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('#archive-filters')) {
            PalimeFilters.init();
        }
    });

    window.PalimeFilters = {

        // ── Состояние ────────────────────────────────────────
        state: {
            section:  '',
            person:   '',
            era:      '',
            genre:    '',
            type:     '',
            status:   '',
            search:   '',
            sort:     'date',
            page:     1,
            loading:  false,
        },

        // ── DOM-ссылки ────────────────────────────────────────
        grid:          null,
        foundCount:    null,
        filterSidebar: null,

        // =========================================================
        // ИНИЦИАЛИЗАЦИЯ
        // =========================================================

        init() {
            this.grid          = document.querySelector('#archive-results');
            this.foundCount    = document.querySelector('#pa-found-count');
            this.filterSidebar = document.querySelector('#archive-filters');

            if (!this.grid) return;

            this.bindTagFilters();
            this.bindPersonAutocomplete();
            this.bindSortTabs();
            this.bindSearchInput();
            this.bindReset();
            this.bindPagination();
            this.readUrlParams();
            this.fetch();
        },

        // =========================================================
        // БИНДИНГИ
        // =========================================================

        /** Теги-кнопки (Раздел, Тип, Эпоха, Жанр, Статус) */
        bindTagFilters() {
            document.querySelectorAll('.pa-filter-tag[data-filter]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const key   = btn.dataset.filter;
                    const value = btn.dataset.value;

                    if (this.state[key] === value) {
                        this.state[key] = '';
                        btn.classList.remove('is-active');
                        btn.setAttribute('aria-pressed', 'false');
                    } else {
                        document.querySelectorAll(`.pa-filter-tag[data-filter="${key}"]`).forEach(b => {
                            b.classList.remove('is-active');
                            b.setAttribute('aria-pressed', 'false');
                        });
                        this.state[key] = value;
                        btn.classList.add('is-active');
                        btn.setAttribute('aria-pressed', 'true');
                    }

                    this.state.page = 1;
                    this.updateActiveTags();
                    this.fetch();
                });
            });
        },

        /** Автодополнение персоны */
        bindPersonAutocomplete() {
            const input       = document.querySelector('#pa-person-input');
            const suggestions = document.querySelector('#pa-person-suggestions');
            if (!input || !suggestions) return;

            const search = this._debounce((q) => {
                if (q.length < 2) {
                    suggestions.classList.remove('is-open');
                    suggestions.innerHTML = '';
                    return;
                }

                const restUrl = window.Palime && window.Palime.data ? window.Palime.data.restUrl : '/wp-json/';
                fetch(restUrl + 'palime/v1/persons?search=' + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        suggestions.innerHTML = '';
                        if (!data || !data.length) { suggestions.classList.remove('is-open'); return; }

                        data.forEach(function(person) {
                            var item = document.createElement('div');
                            item.className   = 'pa-filter-person__suggestion archive-filters__suggestion';
                            item.textContent = person.name;
                            item.setAttribute('role', 'option');
                            item.addEventListener('click', function() {
                                PalimeFilters.state.person = person.slug;
                                PalimeFilters.state.page   = 1;
                                input.value = person.name;
                                suggestions.classList.remove('is-open');
                                PalimeFilters.updateActiveTags();
                                PalimeFilters.fetch();
                            });
                            suggestions.appendChild(item);
                        });

                        suggestions.classList.add('is-open');
                    })
                    .catch(function() { suggestions.classList.remove('is-open'); });
            }, 300);

            input.addEventListener('input', function() { search(input.value); });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    PalimeFilters.state.person = input.value.trim();
                    PalimeFilters.state.page   = 1;
                    suggestions.classList.remove('is-open');
                    PalimeFilters.updateActiveTags();
                    PalimeFilters.fetch();
                }
                if (e.key === 'Escape') suggestions.classList.remove('is-open');
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.classList.remove('is-open');
                }
            });
        },

        /** Вкладки сортировки */
        bindSortTabs() {
            document.querySelectorAll('.pa-sort-tab[data-sort]').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.pa-sort-tab').forEach(t => t.classList.remove('is-active'));
                    tab.classList.add('is-active');
                    this.state.sort = tab.dataset.sort;
                    this.state.page = 1;
                    this.fetch();
                });
            });
        },

        /** Текстовый поиск */
        bindSearchInput() {
            const searchInput = document.querySelector('#pa-archive-search');
            if (!searchInput) return;

            const doSearch = this._debounce((val) => {
                this.state.search = val.trim();
                this.state.page   = 1;
                this.fetch();
            }, 400);

            searchInput.addEventListener('input', () => doSearch(searchInput.value));
        },

        /** Кнопка сброса */
        bindReset() {
            const btn = document.querySelector('#pa-filter-reset');
            if (!btn) return;

            btn.addEventListener('click', () => {
                Object.assign(this.state, {
                    section: '', person: '', era: '', genre: '',
                    type: '', status: '', search: '', sort: 'date', page: 1,
                });

                document.querySelectorAll('.pa-filter-tag.is-active').forEach(b => {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });

                const personInput = document.querySelector('#pa-person-input');
                if (personInput) personInput.value = '';

                const searchInput = document.querySelector('#pa-archive-search');
                if (searchInput) searchInput.value = '';

                document.querySelectorAll('.pa-sort-tab').forEach(t => t.classList.remove('is-active'));
                const defaultSort = document.querySelector('.pa-sort-tab[data-sort="date"]');
                if (defaultSort) defaultSort.classList.add('is-active');

                this.updateActiveTags();
                this.fetch();
            });
        },

        /** Пагинация — делегирование */
        bindPagination() {
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.pa-pagination__btn[data-page]');
                if (!btn) return;

                const page = parseInt(btn.dataset.page);
                if (!page || page === this.state.page) return;

                this.state.page = page;
                this.fetch();
                window.scrollTo({ top: this.grid.offsetTop - 120, behavior: 'smooth' });
            });
        },

        // =========================================================
        // URL-ПАРАМЕТРЫ ПРИ ЗАГРУЗКЕ
        // =========================================================

        readUrlParams() {
            var params = new URLSearchParams(window.location.search);
            var map    = {
                section: 'section', person: 'person', era: 'era',
                genre: 'genre',     type: 'type',     status: 'status',
                s: 'search',        sort: 'sort',
            };

            Object.keys(map).forEach(urlKey => {
                var stateKey = map[urlKey];
                var val = params.get(urlKey);
                if (!val) return;

                this.state[stateKey] = val;

                var tag = document.querySelector(`.pa-filter-tag[data-filter="${stateKey}"][data-value="${val}"]`);
                if (tag) { tag.classList.add('is-active'); tag.setAttribute('aria-pressed', 'true'); }

                if (stateKey === 'search') {
                    var si = document.querySelector('#pa-archive-search');
                    if (si) si.value = val;
                }
                if (stateKey === 'person') {
                    var pi = document.querySelector('#pa-person-input');
                    if (pi) pi.value = val;
                }
                if (stateKey === 'sort') {
                    document.querySelectorAll('.pa-sort-tab').forEach(t => t.classList.remove('is-active'));
                    var st = document.querySelector(`.pa-sort-tab[data-sort="${val}"]`);
                    if (st) st.classList.add('is-active');
                }
            });
        },

        // =========================================================
        // AJAX-ЗАПРОС
        // =========================================================

        fetch() {
            if (this.state.loading) return;
            this.state.loading = true;

            if (this.grid) {
                this.grid.classList.add('loading');
                this.grid.setAttribute('aria-busy', 'true');
            }

            var ajaxUrl = (window.Palime && window.Palime.data) ? window.Palime.data.ajaxUrl : '/wp-admin/admin-ajax.php';
            var nonce   = (window.Palime && window.Palime.data) ? window.Palime.data.nonce   : '';

            var params = new URLSearchParams({
                action:  'palime_filter_archive',
                nonce:   nonce,
                section: this.state.section,
                person:  this.state.person,
                era:     this.state.era,
                genre:   this.state.genre,
                type:    this.state.type,
                status:  this.state.status,
                search:  this.state.search,
                sort:    this.state.sort,
                paged:   this.state.page,
            });

            fetch(ajaxUrl, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    params.toString(),
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        this.renderPosts(res.data.posts);
                        this.renderPagination(res.data.max_pages);
                        this.updateCount(res.data.total);
                    } else {
                        this.renderError();
                    }
                })
                .catch(() => this.renderError())
                .finally(() => {
                    this.state.loading = false;
                    if (this.grid) {
                        this.grid.classList.remove('loading');
                        this.grid.setAttribute('aria-busy', 'false');
                    }
                });
        },

        // =========================================================
        // РЕНДЕР РЕЗУЛЬТАТОВ
        // =========================================================

        renderPosts(posts) {
            if (!posts || !posts.length) {
                this.grid.innerHTML = '<div class="pa-archive-empty">— Ничего не найдено. Попробуйте изменить фильтры —</div>';
                return;
            }

            var html = '<div class="pa-article-list fade-in">' + posts.map(p => this.listItemHTML(p)).join('') + '</div>';
            this.grid.innerHTML = html;

            // Клики по тегам персон — добавляют в фильтр
            this.grid.querySelectorAll('.pa-person-tag[data-person-slug]').forEach(tag => {
                tag.addEventListener('click', (e) => {
                    e.preventDefault();
                    var slug = tag.dataset.personSlug;
                    var name = tag.textContent.trim();
                    this.state.person = slug;
                    this.state.page   = 1;
                    var pi = document.querySelector('#pa-person-input');
                    if (pi) pi.value = name;
                    this.updateActiveTags();
                    this.fetch();
                });
            });
        },

        listItemHTML(post) {
            var sectionSlug  = post.section_slug  || '';
            var sectionName  = post.section_name  || '';
            var typeLabel    = post.type_label    || '';
            var statusLabel  = post.status_label  || '';
            var readingTime  = post.reading_time  ? post.reading_time + '\u00a0мин' : '';
            var lead         = this._esc(post.lead || post.excerpt || '');
            var title        = this._esc(post.title || '');
            var url          = this._esc(post.url || '#');
            var date         = this._esc(post.date || '');

            var sectionCSS = sectionSlug
                ? 'pa-article-row__section--' + sectionSlug
                : 'pa-article-row__section--default';

            var personsHTML = '';
            if (post.persons && post.persons.length) {
                personsHTML = post.persons.map(function(p) {
                    return '<a href="' + p.url + '" class="pa-person-tag" data-person-slug="' + p.slug + '">' + p.name + '</a>';
                }).join('');
            }

            return '<a href="' + url + '" class="pa-article-row" aria-label="' + title + '">'

                + '<div class="pa-article-row__meta">'
                + (sectionName ? '<span class="pa-article-row__section ' + sectionCSS + '">' + sectionName + '</span>' : '')
                + (typeLabel   ? '<span class="pa-article-row__type">' + typeLabel   + '</span>' : '')
                + (statusLabel ? '<span class="pa-article-row__status">' + statusLabel + '</span>' : '')
                + '</div>'

                + '<div class="pa-article-row__date-col">'
                + (date        ? '<span class="pa-article-row__date">'    + date        + '</span>' : '')
                + (readingTime ? '<span class="pa-article-row__readtime">' + readingTime + '</span>' : '')
                + '</div>'

                + '<div class="pa-article-row__title-wrap">'
                + '<span class="pa-article-row__title">' + title + '</span>'
                + '<span class="pa-article-row__arrow" aria-hidden="true">\u2192</span>'
                + '</div>'

                + (lead ? '<p class="pa-article-row__lead">' + lead + '</p>' : '')

                + (personsHTML ? '<div class="pa-article-row__persons">' + personsHTML + '</div>' : '')

                + '</a>';
        },

        renderPagination(maxPages) {
            var old = document.querySelector('.pa-pagination');
            if (old) old.remove();

            if (!maxPages || maxPages <= 1) return;

            var current = this.state.page;
            var html    = '<nav class="pa-pagination" aria-label="Страницы">';

            if (current > 1) html += '<button class="pa-pagination__btn" data-page="' + (current - 1) + '" aria-label="Назад">\u2190</button>';

            var range = 3;
            var start = Math.max(1, current - range);
            var end   = Math.min(maxPages, current + range);

            if (start > 1) {
                html += '<button class="pa-pagination__btn" data-page="1">1</button>';
                if (start > 2) html += '<span style="padding:0 4px;opacity:.3;font-family:var(--font-mono);font-size:.6rem;">\u2026</span>';
            }

            for (var i = start; i <= end; i++) {
                html += '<button class="pa-pagination__btn' + (i === current ? ' is-active' : '') + '" data-page="' + i + '">' + i + '</button>';
            }

            if (end < maxPages) {
                if (end < maxPages - 1) html += '<span style="padding:0 4px;opacity:.3;font-family:var(--font-mono);font-size:.6rem;">\u2026</span>';
                html += '<button class="pa-pagination__btn" data-page="' + maxPages + '">' + maxPages + '</button>';
            }

            if (current < maxPages) html += '<button class="pa-pagination__btn" data-page="' + (current + 1) + '" aria-label="Вперёд">\u2192</button>';

            html += '</nav>';
            this.grid.insertAdjacentHTML('afterend', html);
        },

        updateCount(total) {
            if (this.foundCount) this.foundCount.textContent = total;
        },

        updateActiveTags() {
            var container = document.querySelector('#pa-active-filters');
            if (!container) return;

            container.innerHTML = '';

            var labels = {
                section: 'Раздел',  person: 'Персона', era:    'Эпоха',
                genre:   'Жанр',    type:   'Тип',      status: 'Статус',
                search:  'Поиск',
            };

            Object.keys(labels).forEach(key => {
                if (!this.state[key]) return;

                var label = labels[key];
                var pill  = document.createElement('span');
                pill.className = 'pa-active-filter';
                pill.innerHTML = label + ': ' + this._esc(this.state[key])
                    + '<span class="pa-active-filter__remove" role="button" tabindex="0" aria-label="Убрать">×</span>';

                var removeBtn = pill.querySelector('[role="button"]');
                removeBtn.addEventListener('click', () => this.removeFilter(key));
                removeBtn.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') this.removeFilter(key);
                });

                container.appendChild(pill);
            });
        },

        removeFilter(key) {
            this.state[key] = '';
            this.state.page = 1;

            document.querySelectorAll('.pa-filter-tag[data-filter="' + key + '"]').forEach(b => {
                b.classList.remove('is-active');
                b.setAttribute('aria-pressed', 'false');
            });
            if (key === 'person') {
                var pi = document.querySelector('#pa-person-input');
                if (pi) pi.value = '';
            }
            if (key === 'search') {
                var si = document.querySelector('#pa-archive-search');
                if (si) si.value = '';
            }

            this.updateActiveTags();
            this.fetch();
        },

        renderError() {
            if (this.grid) {
                this.grid.innerHTML = '<div class="pa-archive-empty">— Ошибка загрузки. Обновите страницу —</div>';
            }
        },

        // =========================================================
        // УТИЛИТЫ
        // =========================================================

        _esc(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        },

        _debounce(fn, ms) {
            var timer;
            return function() {
                var args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() { fn.apply(this, args); }, ms);
            };
        },
    };

})();
