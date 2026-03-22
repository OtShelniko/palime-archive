/**
 * Palime Archive — auth.js
 * Логика страницы авторизации: табы, AJAX-формы, Telegram
 */

(function () {
    'use strict';

    var notice = document.getElementById('auth-notice');

    // ---------------------------------------------------------
    // ТАБЫ
    // ---------------------------------------------------------
    var tabBtns = document.querySelectorAll('.auth-tabs__btn');
    var forms   = document.querySelectorAll('.auth-form');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = btn.dataset.tab;

            tabBtns.forEach(function (b) { b.classList.remove('is-active'); });
            forms.forEach(function (f) { f.classList.remove('is-active'); });

            btn.classList.add('is-active');
            var target = document.querySelector('.auth-form[data-tab="' + tab + '"]');
            if (target) target.classList.add('is-active');

            hideNotice();

            // Обновляем URL без перезагрузки
            var url = new URL(window.location);
            url.searchParams.set('tab', tab);
            history.replaceState(null, '', url);
        });
    });

    // ---------------------------------------------------------
    // УВЕДОМЛЕНИЯ
    // ---------------------------------------------------------
    function showNotice(msg, type) {
        notice.textContent = msg;
        notice.className = 'auth-notice auth-notice--' + (type || 'error');
        notice.style.display = 'block';
    }

    function hideNotice() {
        notice.style.display = 'none';
    }

    // ---------------------------------------------------------
    // ФОРМА ВХОДА
    // ---------------------------------------------------------
    var loginForm = document.getElementById('auth-login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            hideNotice();

            var log = loginForm.querySelector('[name="log"]').value.trim();
            var pwd = loginForm.querySelector('[name="pwd"]').value;
            var rem = loginForm.querySelector('[name="rememberme"]');
            var redirect = loginForm.querySelector('[name="redirect_to"]').value;

            if (!log || !pwd) {
                showNotice('Заполните все поля.');
                return;
            }

            var submitBtn = loginForm.querySelector('.auth-form__submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'ВХОДИМ…';

            var fd = new FormData();
            fd.append('action', 'palime_login');
            fd.append('nonce', palimeData.authNonce);
            fd.append('log', log);
            fd.append('pwd', pwd);
            fd.append('rememberme', rem && rem.checked ? 'forever' : '');
            fd.append('redirect_to', redirect);

            fetch(palimeData.ajaxUrl, { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        showNotice('Успешный вход! Перенаправляем…', 'success');
                        window.location.href = res.data.redirect || redirect;
                    } else {
                        showNotice(res.data.message || 'Ошибка входа.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'ВОЙТИ';
                    }
                })
                .catch(function () {
                    showNotice('Ошибка сети. Попробуйте снова.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'ВОЙТИ';
                });
        });
    }

    // ---------------------------------------------------------
    // ФОРМА РЕГИСТРАЦИИ
    // ---------------------------------------------------------
    var regForm = document.getElementById('auth-register-form');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            e.preventDefault();
            hideNotice();

            var login    = regForm.querySelector('[name="user_login"]').value.trim();
            var email    = regForm.querySelector('[name="user_email"]').value.trim();
            var pass     = regForm.querySelector('[name="user_pass"]').value;
            var pass2    = regForm.querySelector('[name="user_pass2"]').value;
            var redirect = regForm.querySelector('[name="redirect_to"]').value;

            if (!login || !email || !pass || !pass2) {
                showNotice('Заполните все поля.');
                return;
            }

            if (pass.length < 6) {
                showNotice('Пароль должен быть не менее 6 символов.');
                return;
            }

            if (pass !== pass2) {
                showNotice('Пароли не совпадают.');
                return;
            }

            var submitBtn = regForm.querySelector('.auth-form__submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'РЕГИСТРИРУЕМ…';

            var fd = new FormData();
            fd.append('action', 'palime_register');
            fd.append('nonce', palimeData.authNonce);
            fd.append('user_login', login);
            fd.append('user_email', email);
            fd.append('user_pass', pass);
            fd.append('redirect_to', redirect);

            fetch(palimeData.ajaxUrl, { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        showNotice('Регистрация прошла успешно! Перенаправляем…', 'success');
                        window.location.href = res.data.redirect || redirect;
                    } else {
                        showNotice(res.data.message || 'Ошибка регистрации.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                    }
                })
                .catch(function () {
                    showNotice('Ошибка сети. Попробуйте снова.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                });
        });
    }

    // ---------------------------------------------------------
    // TELEGRAM AUTH CALLBACK
    // ---------------------------------------------------------
    window.palimeTelegramAuth = function (user) {
        hideNotice();
        showNotice('Авторизация через Telegram…', 'success');

        var fd = new FormData();
        fd.append('action', 'palime_telegram_auth');
        fd.append('nonce', palimeData.authNonce);

        Object.keys(user).forEach(function (key) {
            fd.append('telegram_data[' + key + ']', user[key]);
        });

        fetch(palimeData.ajaxUrl, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    window.location.href = res.data.redirect || '/profile/';
                } else {
                    showNotice(res.data.message || 'Ошибка авторизации через Telegram.');
                }
            })
            .catch(function () {
                showNotice('Ошибка сети. Попробуйте снова.');
            });
    };
})();
