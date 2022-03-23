<footer class="footer mt-auto py-3 bg-success bg-gradient">
    <div class="container">
        <span class="text-white"><?php echo date('Y') ?> Dewan Mesjid Indonesia</span>
    </div>
</footer>
<script type="text/javascript">
    var divs = ["login", "register"];
    var visibleId = null;

    function show(id) {
        if (visibleId !== id) {
            visibleId = id;
        }
        hide();
    }

    function hide() {
        var div, i, id;
        for (i = 0; i < divs.length; i++) {
            id = divs[i];
            div = document.getElementById(id);
            if (visibleId === id) {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>