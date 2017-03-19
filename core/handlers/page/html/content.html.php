<?php

require_once('../core/handlers/page/php/helper.php');

$t = $this;   # templater object
$request = $t->request;
$helper = new PageHelper($t->request);
$page = $t->data('page');

?>
      <!-- Tabs -->
      <div id="page-header">
        <?php echo $helper->build_page_tab_menu($page); ?>
      </div>

      <!-- Tab Panes -->
      <div id="main-content">
        <div class="tab-content">
          <?php if ( $page->exists() ): ?>
            <?php echo $helper->build_tab_panels($page->body_to_html()); ?>
          <?php else: ?>
            <p>This page doesn't exist yet.</p>

            <?php if ( $request->user->can_create_page() ): ?>
              <p>
                <a href="<?php echo $page->editor_url(); ?>">
                  Click here to create it.
                </a>
              </p>
            <?php endif ?>
          <?php endif ?>
        </div>
      </div>

      <!-- Page Controls -->
      <div id="page-controls" class="navbar">
        <div class="container">
          <ul class="nav navbar-nav">
            <li><p class="navbar-text"><?php echo $t->data('last-edited'); ?></p></li>
          </ul>
        </div>
      </div>
