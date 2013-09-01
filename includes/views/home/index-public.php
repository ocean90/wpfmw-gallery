<?php include APP_VIEWS_PATH . 'header.php'; ?>
<?php include APP_VIEWS_PATH . 'navbar.php'; ?>


<div class="page-header" style="text-align:center">
  <h1>Welcome! <small>and create your own gallery</small></h1>
</div>

   

<div class="span4 well">
  <div class="row">
    <div class="span3">
      <div id="slideshow" class="align: center">
        <ul>
          <li>
            <img src="pic1.jpg" title="Four-mast bark Passat" />
          </li>

          <li>
            <img src="pic2.jpg" title="Wood trunks" />
          </li>

          <li>
            <img src="pic3.jpg" title="Flower bouqet" />
          </li>

          <li>
            <img src="pic4.jpg" title="Tower Bridge, London" />
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<p style="text-align: center">Welcome to the online gallery.<br> 
    This gallery offers the opportunity to create your own galleries to manage images or see other galleries.<br><br>
    <a href="<?php site_url( '/login/')?>" class="btn btn-large btn-block btn-primary" type="button">Login</a>
    new? Create an <a href="<?php site_url( '/register/')?>">account</a>
</p>
 
<?php include APP_VIEWS_PATH . 'footer.php'; ?>



