document.addEventListener('DOMContentLoaded', function () {
    const startButtons = document.querySelectorAll('[data-start-quiz]');
    startButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            window.location.href = 'quiz.php?action=start';
        });
    });
});
