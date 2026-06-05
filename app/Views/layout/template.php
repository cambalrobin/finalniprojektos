<html>
    <head> 
        <title>Titulek</title>
        <?= $this->include("layout/assets");?> 
 </head> 
 <body>
 <?= $this->include("layout/navbar");?>
 <!--Dynamický obsah -->
 <?= $this->renderSection("content"); ?> 
 <footer class="bg-dark text-white text-center py-3 mt-5 fixed-bottom">
    <p class="mb-0">© <?= date('Y') ?> vytvořil Fildas a Roubas</p>
</footer>
 <body>
</html>