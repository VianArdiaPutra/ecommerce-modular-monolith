{{-- Dialog konfirmasi hapus: dipakai form yang punya atribut data-confirm-delete="nama item" --}}
<dialog id="confirm-dialog" class="confirm-dialog" aria-labelledby="confirm-title">
    <div class="confirm-body">
        <h3 id="confirm-title">Yakin ingin menghapus?</h3>
        <p data-confirm-text></p>
        <div class="confirm-actions">
            <button type="button" class="btn btn-outline" data-confirm-cancel>Batal</button>
            <button type="button" class="btn btn-danger" data-confirm-ok>Ya, hapus</button>
        </div>
    </div>
</dialog>

<script>
    (function() {
        var dialog = document.getElementById('confirm-dialog');
        if (!dialog) return;

        var textEl = dialog.querySelector('[data-confirm-text]');
        var pending = null;

        document.querySelectorAll('form[data-confirm-delete]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                pending = form;
                textEl.textContent = '“' + form.dataset.confirmDelete + '” akan dihapus.';
                dialog.showModal();
            });
        });

        dialog.querySelector('[data-confirm-ok]').addEventListener('click', function() {
            var form = pending;
            pending = null;
            dialog.close();
            if (form) form.submit();
        });

        dialog.querySelector('[data-confirm-cancel]').addEventListener('click', function() {
            pending = null;
            dialog.close();
        });

        // Klik area gelap menutup dialog tanpa menghapus
        dialog.addEventListener('click', function(e) {
            if (e.target === dialog) {
                pending = null;
                dialog.close();
            }
        });
    })();
</script>
