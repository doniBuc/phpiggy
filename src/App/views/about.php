<?php include $this->resolvePath('partials/_header.php') ?>
<!-- Start Main Content Area -->
<section
    class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <!-- Page Title -->
    <h3>About Page</h3>

    <hr />

    <!-- Escaping Data -->
    <p>Escaping Data:<?php echo escaping($dangerousData); ?></p>
</section>
<!-- End Main Content Area -->
<?php include $this->resolvePath('partials/_footer.php') ?>