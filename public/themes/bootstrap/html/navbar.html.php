<?php
require_once('../public/themes/bootstrap/php/helper.php');


$t = $this;   # templater object
$helper = new BootstrapHelper($t->request);

?>
    <!-- Bootstrap Navbar -->
    <div role="navigation" class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse"
                  class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/" class="navbar-brand">Neechy</a>
        </div>

        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <!-- TODO: dynamically build menu -->
            <li class="<?php echo $helper->nav_tab_class('page-index'); ?>">
              <?php echo $helper->handler_link('PageIndex', 'admin', 'page-index'); ?>
            </li>
            <li class="<?php echo $helper->nav_tab_class('recent-changes'); ?>">
              <?php echo $helper->handler_link('RecentChanges', 'recent', 'changes'); ?>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <?php echo $helper->user_button(); ?>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
