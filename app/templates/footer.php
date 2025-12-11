</main>
<footer>
    <p>&copy; <?= date('Y') ?> Responsi Pemograman Web Shift B</p>
</footer>
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
</script>
</body>

</html>