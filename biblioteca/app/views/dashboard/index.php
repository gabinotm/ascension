<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>


<div class="content">

    <h1>Dashboard</h1>

    <div class="cards">

        <div class="card">
    <h3>Total Libros</h3>
    <span><?= $totalLibros['total'] ?? 0; ?></span>
</div>

        <div class="card">
            <h3>Lectores</h3>
            <span>30</span>
        </div>

        <div class="card">
            <h3>Préstamos</h3>
            <span>15</span>
        </div>

        <div class="card">
            <h3>Vencidos</h3>
            <span>2</span>
        </div>

    </div>

</div>

<?php require '../app/views/layouts/footer.php'; ?>