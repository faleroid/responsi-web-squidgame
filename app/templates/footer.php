</main>
</footer>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.notification');

        alerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.style.display = 'none';
                }, 500);
            }, 3000);
        });
    });

    window.addEventListener('load', function () {
        const loader = document.querySelector('.loader-wrapper');
        loader.classList.add('loader-hidden');

        loader.addEventListener('transitionend', function () {
            document.body.removeChild(loader);
        });
    });
</script>
</body>

</html>