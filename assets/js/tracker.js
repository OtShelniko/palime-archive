/* Palime Archive — tracker.js */
/* Трекинг чтения статей и времени на сайте для геймификации */

(function () {
    'use strict';

    var data = window.palimeData || {};
    if (!data.userId) return;

    var articleEl = document.querySelector('[data-article-id]');
    var articleId = 0;

    if (articleEl) {
        articleId = parseInt(articleEl.dataset.articleId, 10);
    } else {
        var match = document.body.className.match(/postid-(\d+)/);
        articleId = match ? parseInt(match[1], 10) : 0;
    }

    if (!articleId) return;

    var startTime = Date.now();
    var maxScroll = 0;
    var readTracked = false;

    // Отслеживаем максимальный скролл
    window.addEventListener('scroll', function () {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (docHeight > 0) {
            var percent = Math.round((scrollTop / docHeight) * 100);
            if (percent > maxScroll) {
                maxScroll = percent;
            }
        }
    });

    // Проверяем каждые 5 секунд: скролл >= 70% -> отправляем чтение
    // Отправляем реальное time_spent, лонгрид засчитывается серверной стороной при >= 120с
    var checkInterval = setInterval(function () {
        if (readTracked) {
            clearInterval(checkInterval);
            return;
        }

        if (maxScroll >= 70) {
            readTracked = true;
            clearInterval(checkInterval);

            var timeSpent = Math.round((Date.now() - startTime) / 1000);

            var body = new FormData();
            body.append('action', 'palime_track_read');
            body.append('nonce', data.nonce);
            body.append('article_id', articleId);
            body.append('scroll_percent', maxScroll);
            body.append('time_spent', timeSpent);

            fetch(data.ajaxUrl, { method: 'POST', body: body });
        }
    }, 5000);

    // Дополнительная проверка для лонгридов: если пользователь долго на странице,
    // но скролл ещё не достиг 70% — отправляем при уходе со страницы
    window.addEventListener('beforeunload', function () {
        clearInterval(checkInterval);
        clearInterval(sessionInterval);

        var timeSpent = Math.round((Date.now() - startTime) / 1000);

        // Если не трекнули чтение и скролл >= 70%
        if (!readTracked && maxScroll >= 70) {
            var body = new FormData();
            body.append('action', 'palime_track_read');
            body.append('nonce', data.nonce);
            body.append('article_id', articleId);
            body.append('scroll_percent', maxScroll);
            body.append('time_spent', timeSpent);

            navigator.sendBeacon(data.ajaxUrl, body);
        }

        // Финальный трек сессии
        var minutesSpent = Math.round(timeSpent / 60);
        if (minutesSpent >= 1) {
            var sessionBody = new FormData();
            sessionBody.append('action', 'palime_track_session');
            sessionBody.append('nonce', data.nonce);
            sessionBody.append('minutes', minutesSpent % 10 || 1);

            navigator.sendBeacon(data.ajaxUrl, sessionBody);
        }
    });

    // Трекинг сессии: каждые 10 минут
    var sessionInterval = setInterval(function () {
        var body = new FormData();
        body.append('action', 'palime_track_session');
        body.append('nonce', data.nonce);
        body.append('minutes', 10);

        fetch(data.ajaxUrl, { method: 'POST', body: body });
    }, 600000);

})();
