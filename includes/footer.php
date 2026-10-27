<?php
// Layout footer penutup halaman aplikasi
require_once __DIR__ . '/auth.php';
?>

<?php if (is_logged_in()): ?>
            </div>
        </main>

        <footer class="app-footer">
            <div class="container-fluid px-3 px-lg-4 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; <?= date('Y') ?> Sistem Inventori.</span>
                <span class="text-muted small">Aplikasi Data Barang</span>
            </div>
        </footer>
    </div>
</div>
<?php else: ?>
        </div>
    </main>

    <footer class="guest-footer">
        <div class="container text-center py-3">
            <span>&copy; <?= date('Y') ?> Sistem Inventori.</span>
        </div>
    </footer>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
