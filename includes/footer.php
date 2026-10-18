</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h3>Delicias los Lagos</h3>
            <p>Compra tu torta favorita y disfruta junto a tus seres queridos.</p>
        </div>
        <div>
            <h4>Horarios</h4>
            <p>Martes a Domingo: 14:00 - 21:00hrs</p>
        </div>
        <div>
            <h4>Contacto</h4>
            <p><a href="https://wa.me/<?= e(WHATSAPP_NUMBER); ?>" target="_blank" rel="noopener">WhatsApp</a></p>
            <p><a href="mailto:<?= e(ADMIN_EMAIL); ?>"><?= e(ADMIN_EMAIL); ?></a></p>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>© <?= date('Y'); ?> Delicias los Lagos. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="/assets/js/app.js"></script>
</body>
</html>
