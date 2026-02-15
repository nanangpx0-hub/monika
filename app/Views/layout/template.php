<?= $this->include('layout/header'); ?>
<?= $this->include('layout/sidebar'); ?>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <?= $this->renderSection('content'); ?>
        </div>
    </section>
</div>

<footer class="main-footer">
    <strong>Copyright &copy; 2026 BPS Jember</strong>
</footer>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<?= $this->renderSection('scripts'); ?>
</body>
</html>
