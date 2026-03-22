/* Palime Archive — tracker.js */
/* Трекинг чтения статей и времени на сайте для геймификации */

(function () {
    'use strict';

    var data = window.palimeData || {};
    if (!data.userId) return;

    var articleId = document.querySelector('[data-article-id]');
    if (articleId) {
        articleId = parseInt(articleId.dataset.articleId, 10);
    } else {
        // Попробуем получить из body class
        var match = document.body.className.match(/postid-(\d+)/);
        articleId = match ? parseInt(match[1], 10) : 0;
    }

    if (!articleId) return;

    var startTime = Date.now();
    var maxScroll = 0;
    var tracked = false;

    // Отслеживаем максимальный скролл
    window.addEventListener('scroll', function () {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (docHeight > 0) {
            var percent = Math.round((scrollTop / docHeight) * 100);
            if (percent > maxScroll) {
                maxScroll = percent;
            }

            // Трекаем при 70%+ скролла
            if (maxScroll >= 70 && !tracked) {
                tracked = true;
                trackRead();
            }
        }
    });

    function trackRead() {
        var timeSpent = Math.round((Date.now() - startTime) / 1000);

        var body = new FormData();
        body.append('action', 'palime_track_read');
        body.append('nonce', data.nonce);
        body.append('article_id', articleId);
        body.append('scroll_percent', maxScroll);
        body.append('time_spent', timeSpent);

        fetch(data.ajaxUrl, { method: 'POST', body: body });
    }

    // Трекинг сессии: каждые 10 минут отправляем время
    var sessionInterval = setInterval(function () {
        var body = new FormData();
        body.append('action', 'palime_track_session');
        body.append('nonce', data.nonce);
        body.append('minutes', 10);

        fetch(data.ajaxUrl, { method: 'POST', body: body });
    }, 600000); // 10 минут

    // При закрытии страницы — финальный трек
    window.addEventListener('beforeunload', function () {
        clearInterval(sessionInterval);

        var minutesSpent = Math.round((Date.now() - startTime) / 60000);
        if (minutesSpent < 1) return;

        var body = new FormData();
        body.append('action', 'palime_track_session');
        body.append('nonce', data.nonce);
        body.append('minutes', minutesSpent % 10 || 1); // Остаток, не отправленный интервалом

        navigator.sendBeacon(data.ajaxUrl, body);
    });

})();
